<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Server;
use Carbon\Carbon;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use App\Models\Log;

class ServerController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:xem server')->only(['index', 'show']);
        $this->middleware('can:thêm server')->only(['create', 'store']);
        $this->middleware('can:sửa server')->only(['edit', 'update']);
        $this->middleware('can:xóa server')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Server::where('user_id', auth()->id());

        // Tìm kiếm theo tên server
        if ($request->filled('search')) {
            $query->where('server_name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo nhà cung cấp
        if ($request->filled('provider')) {
            $query->where('provider', 'like', '%' . $request->provider . '%');
        }

        // Lọc theo ngày hết hạn
        if ($request->filled('expiry_date_from')) {
            $query->where('expiry_date', '>=', $request->expiry_date_from);
        }
        if ($request->filled('expiry_date_to')) {
            $query->where('expiry_date', '<=', $request->expiry_date_to);
        }

        $servers = $query->orderBy('expiry_date', 'asc')->paginate(10);

        // Ghi log hệ thống
        Log::create([
            'message' => auth()->user()->name . 'Vừa xem danh sách server"' . '.',
        ]);
        return view('admin.server.index', compact('servers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.server.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'server_name' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'ip_address' => 'required|ip',
            'os' => 'required|string|max:255',
            'login_user' => 'required|string|max:255',
            'login_password' => 'required|string|max:255',
            'start_date' => 'required|date',
            'expiry_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive,expired,suspended',
        ], [
            'server_name.required' => 'Vui lòng nhập tên server.',
            'provider.required' => 'Vui lòng chọn nhà cung cấp.',
            'ip_address.required' => 'Vui lòng nhập địa chỉ IP.',
            'os.required' => 'Vui thông nhà cung cấp.',
            'login_user.required' => 'Vui lòng nhập tài khoản đăng nhập.',
            'login_password.required' => 'Vui lòng nhập mật khẩu đăng nhập.',
            'start_date.required' => 'Vui nhập ngày bàn giao.',
            'expiry_date.required' => 'Vui chọn ngày hết hạn.',
            'expiry_date.after' => 'Ngày hết hạn phải lớn hơn ngày bàn giao.',
            'status.required' => 'Vui clich trạng thái.',
        ]);

        $server = Server::create([
            'user_id' => auth()->id(),
            'server_name' => $request->server_name,
            'provider' => $request->provider,
            'ip_address' => $request->ip_address,
            'os' => $request->os,
            'login_user' => $request->login_user,
            'login_password' => Hash::make($request->login_password),
            'start_date' => $request->start_date,
            'expiry_date' => $request->expiry_date,
            'status' => $request->status,
        ]);

        // Ghi log hệ thống
        Log::create([
            'message' => auth()->user()->name . 'đã tạo server"' . $server->server_name . '.',
        ]);
        // Kiểm tra ngày hết hạn sau khi tạo
        $this->checkExpiry($server);

        return redirect()->route('servers.index')->with('success', 'Server đã được thêm thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $server = Server::where('user_id', auth()->id())->findOrFail($id);
        return view('admin.server.show', compact('server'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $server = Server::where('user_id', auth()->id())->findOrFail($id);
        return view('admin.server.edit', compact('server'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $server = Server::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'server_name' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'ip_address' => 'required|ip',
            'os' => 'required|string|max:255',
            'login_user' => 'required|string|max:255',
            'login_password' => 'required|string|max:255',
            'start_date' => 'required|date',
            'expiry_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive,expired,suspended',
        ]);

        $data = $request->all();
        $data['login_password'] = Hash::make($request->login_password);
        $server->update($data);

        // Ghi log hệ thống
        Log::create([
            'message' => auth()->user()->name . 'đã cập nhật server"' . $server->server_name . '.',
        ]);
        // Kiểm tra ngày hết hạn sau khi cập nhật
        $this->checkExpiry($server);

        return redirect()->route('servers.index')->with('success', 'Server đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $server = Server::where('user_id', auth()->id())->findOrFail($id);
        $server->delete();

        // Ghi log hệ thống
        Log::create([
            'message' => auth()->user()->name . 'đã xóa server"' . $server->server_name . '.',
        ]);
        return redirect()->route('servers.index')->with('success', 'Server đã được xóa thành công.');
    }

    /**
     * Kiểm tra server sắp hết hạn và tạo thông báo
     */
    protected function checkExpiry(Server $server)
    {
        if (!$server->expiry_date || $server->status === 'inactive') {
            return;
        }

        $expiryDate = Carbon::parse($server->expiry_date);
        $today = Carbon::today();
        $daysUntilExpiry = $today->diffInDays($expiryDate, false);

        // Nếu còn 15 ngày hoặc ít hơn
        if ($daysUntilExpiry <= 15 && $daysUntilExpiry >= 0) {
            $notificationMessage = "Server {$server->server_name} sẽ hết hạn vào {$server->expiry_date} (còn {$daysUntilExpiry} ngày).";

            // Kiểm tra xem thông báo đã tồn tại chưa
            $existingNotification = Notification::where('user_id', auth()->id())
                ->where('title', 'Cảnh báo hết hạn server')
                ->where('message', $notificationMessage)
                ->where('is_read', false)
                ->first();

            if (!$existingNotification) {
                Notification::create([
                    'user_id' => auth()->id(),
                    'title' => 'Cảnh báo hết hạn server',
                    'message' => $notificationMessage,
                    'is_read' => false,
                ]);
            }
        }
    }
}