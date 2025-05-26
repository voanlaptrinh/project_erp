<?php

namespace App\Http\Controllers;

use App\Models\MessageGroup;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageGroupController extends Controller
{
    // List all chat groups/conversations for the authenticated user
    public function index()
    {
        // All users except the current user (for group creation)
        $users = User::where('id', '!=', Auth::id())->get();

        // All groups the current user is a member of
        $groups = Auth::user()->messageGroups()->with(['users', 'latestMessage'])->get();

        return view('admin.chat.index', compact('groups', 'users'));
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

        return redirect()->route('chat.show', $group)->with('success', 'Group created successfully');
    }

    // Start a private conversation
    public function startPrivate(User $user)
    {
        // Check if a private conversation already exists
        $existingGroup = Auth::user()->messageGroups()
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->where('is_group', false)
            ->first();

        if ($existingGroup) {
            return redirect()->route('chat.show', $existingGroup);
        }

        $group = MessageGroup::create([
            'name' => 'Private Chat',
            'is_group' => false,
        ]);

        $group->users()->attach([Auth::id(), $user->id]);

        return redirect()->route('chat.show', $group);
    }

    // Add users to a group
    public function addUsers(Request $request, MessageGroup $group)
    {
        $this->authorize('update', $group);

        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        $group->users()->syncWithoutDetaching($request->users);

        return back()->with('success', 'Users added to group');
    }

    // Remove user from group
    public function removeUser(MessageGroup $group, User $user)
    {
        $this->authorize('update', $group);

        if ($group->users()->count() <= 2 && $group->is_group) {
            return back()->with('error', 'A group must have at least 2 members');
        }

        $group->users()->detach($user->id);

        return back()->with('success', 'User removed from group');
    }
}