<?php

namespace App\Http\Controllers;

use App\Models\MessageGroup;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageGroupController extends Controller
{

    function __construct()
    {
        // Middleware to check permissions for various actions
        $this->middleware('can:tạo nhóm chat')->only(['createGroup']);
        $this->middleware('can:cập nhật nhóm chat')->only(['addUsers', 'removeUser']);
        // $this->middleware('can:xóa nhóm chat')->only(['destroy']);
    }
    // List all chat groups/conversations for the authenticated user
    public function index(Request $request)
    {
        $users = User::where('id', '!=', Auth::id())->get();

        // Lấy tất cả các nhóm chat của user hiện tại với thông tin tin nhắn mới nhất và số lượng tin nhắn chưa đọc
        // Sắp xếp theo thời gian tin nhắn mới nhất
        $groups = Auth::user()->messageGroups()
            ->with(['users', 'latestMessage'])
            ->withCount(['messages as unreadCount' => function ($query) {
                $query->whereDoesntHave('reads', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            }])
            ->get()
            ->sortByDesc(function ($group) {
                return optional($group->latestMessage)->created_at;
            })
            ->values();

        // Nếu có nhóm chat được chọn, lấy thông tin chi tiết
        $selectedGroup = null;
        $messages = [];

        if ($request->has('group')) {
            $selectedGroup = MessageGroup::with(['users', 'messages.sender'])->find($request->group);
            if ($selectedGroup) {
                $messages = $selectedGroup->messages()
                    ->with(['sender', 'reads'])
                    ->orderBy('created_at', 'asc')
                    ->get();

                // Đánh dấu tin nhắn là đã đọc
                foreach ($messages as $message) {
                    if (!$message->reads->contains('user_id', Auth::id())) {
                        $message->reads()->create([
                            'user_id' => Auth::id(),
                            'read_at' => now(),
                        ]);
                    }
                }
            }
        }
        $totalUnreadMessages = $groups->sum('unreadCount');
        return view('admin.chat.index', compact('groups', 'users', 'selectedGroup', 'messages', 'totalUnreadMessages'));
    }

    // Show a specific chat group/conversation

    public function show(MessageGroup $group)
    {
        $this->authorize('view', $group);

        $messages = $group->messages()
            ->with(['sender', 'reads'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Lấy danh sách user chưa có trong group (tránh trùng lặp)
        $users = User::where('id', '!=', Auth::id())
            ->whereNotIn('id', $group->users->pluck('id'))
            ->get();

        // Mark messages as read
        foreach ($messages as $message) {
            if (!$message->reads->contains('user_id', Auth::id())) {
                $message->reads()->create([
                    'user_id' => Auth::id(),
                    'read_at' => now(),
                ]);
            }
        }

        return view('admin.chat.show', compact('group', 'messages', 'users'));
    }

    // Create a new group chat
    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        $group = MessageGroup::create([
            'name' => $request->name,
            'is_group' => true,
        ]);

        $group->users()->attach(array_merge([Auth::id()], $request->users));

        return redirect()->route('chat.index', $group)->with('success', 'Group created successfully');
    }

    // Start a private conversation
    public function startPrivate(User $user)
    {
        $authId = Auth::id();

        // Tìm group 1:1 đã tồn tại
        $existingGroup = MessageGroup::where('is_group', false)
            ->whereHas('users', function ($q) use ($authId) {
                $q->where('users.id', $authId);
            })
            ->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->withCount('users')
            ->having('users_count', 2)
            ->first();

        if ($existingGroup) {
            return redirect()->route('chat.index', ['group' => $existingGroup->id]);
        }

        // Nếu chưa có, tạo mới
        $group = MessageGroup::create([
            'name' => null,
            'is_group' => false,
        ]);
        $group->users()->attach([$authId, $user->id]);

        return redirect()->route('chat.index', ['group' => $group->id]);
    }

    // Thêm người dùng vào nhóm chat
    // Chỉ cho phép thêm người dùng vào nhóm, không cho phép thêm vào cuộc trò chuyện riêng tư
    public function addUsers(Request $request, MessageGroup $group)
    {
        $this->authorize('update', $group);

        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        // Lấy danh sách user mới được thêm (chỉ những người chưa có trong nhóm)
        $currentUserIds = $group->users()->pluck('users.id')->toArray();
        $newUserIds = array_diff($request->users, $currentUserIds);

        $group->users()->syncWithoutDetaching($request->users);

        // Gửi tin nhắn hệ thống nếu có thành viên mới
        if (!empty($newUserIds)) {
            $newUsers = User::whereIn('id', $newUserIds)->pluck('name')->toArray();
            $content = 'Đã thêm ' . implode(', ', $newUsers) . ' vào nhóm.';

            $group->messages()->create([
                'user_id' => Auth::id(),
                'content' => $content,
            ]);
        }

        return back()->with('success', 'Users added to group');
    }

    // Xóa thành viên khỏi nhóm (cập nhật phương thức hiện có)
    public function removeUser(MessageGroup $group, User $user)
    {
        $this->authorize('update', $group);

        // Kiểm tra nếu không phải là nhóm
        if (!$group->is_group) {
            return back()->with('error', 'Không thể xóa thành viên khỏi cuộc trò chuyện riêng tư');
        }

        // Kiểm tra số lượng thành viên tối thiểu
        if ($group->users()->count() <= 2) {
            return back()->with('error', 'Nhóm phải có ít nhất 2 thành viên');
        }

        // Không cho xóa chính mình
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Bạn không thể tự xóa mình khỏi nhóm');
        }

        $group->users()->detach($user->id);
        

        return back()->with('success', 'Đã xóa thành viên khỏi nhóm');
    }

    // Xóa nhóm chat
    public function destroy(MessageGroup $group)
    {
        // Kiểm tra nếu là private chat thì không cho xóa
        if (!$group->is_group) {
            return redirect()->route('chat.index')
                ->with('error', 'Không thể xóa cuộc trò chuyện riêng tư');
        }

        $group->delete();

        return redirect()->route('chat.index')
            ->with('success', 'Nhóm chat đã được xóa thành công');
    }

    // Handle typing indicator
    public function typing(Request $request, MessageGroup $group)
    {
        $this->authorize('view', $group);

        broadcast(new UserTyping(Auth::id(), $group->id, $request->is_typing))->toOthers();

        return response()->json(['status' => 'success']);
    }
}