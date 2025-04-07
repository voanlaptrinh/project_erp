<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');
    
        // Nếu có từ khóa tìm kiếm, lọc theo email
        if ($request->has('search') && !empty($request->search)) {
            $query->where('email', 'LIKE', '%' . $request->search . '%');
        }
    
        $users = $query->paginate(10); // Phân trang
        //ghi lại log khi sử dụng
        Log::create([
            'message' => Auth::user()->name . ' đã xem danh sách người dùng'
        ]);
        return view('admin.users.index', compact('users'));
    }
    
    public function create()
    {
        $roles = Role::all(); // Lấy tất cả vai trò
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'roles' => 'required|array' // Đảm bảo là mảng
        ], [
            'name.required' => 'Tên người dùng là bắt buộc.',
            'name.string' => 'Tên người dùng phải là chuỗi ký tự.',
            'name.max' => 'Tên người dùng không được quá 255 ký tự.',
            
            'email.required' => 'Địa chỉ email là bắt buộc.',
            'email.email' => 'Vui lòng nhập một địa chỉ email hợp lệ.',
            'email.unique' => 'Email đã được đăng ký.',
            
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            
            'roles.required' => 'Vai trò là bắt buộc.',
            'roles.array' => 'Vai trò phải là một mảng.',
        ]);
        
    
        // Tạo người dùng
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        // Gán vai trò cho người dùng
        $user->syncRoles($request->roles); // Sử dụng syncRoles thay vì assignRole
    Log::create([
        'message' => Auth::user()->name . ' đã tạo người dùng ' . $request->name
    ]);
        return redirect()->route('admin.users.index')->with('success', 'Tạo user thành công!');
    }
    

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->syncRoles($request->roles);
        Log::create([
            'message' => Auth::user()->name . ' đã cập nhật quyền cho người dùng ' . $user->name
        ]);
        return redirect()->route('admin.users.index')->with('success', 'Cập nhật quyền thành công!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        Log::create([
            'message' => Auth::user()->name . ' đã xóa người dùng ' . $user->name
        ]);
        return redirect()->route('admin.users.index')->with('success', 'Xóa user thành công!');
    }
}
