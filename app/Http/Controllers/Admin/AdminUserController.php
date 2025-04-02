<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get(); // Lấy danh sách user và vai trò
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
        ]);
    
        // Tạo người dùng
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        // Gán vai trò cho người dùng
        $user->syncRoles($request->roles); // Sử dụng syncRoles thay vì assignRole
    
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
        return redirect()->route('admin.users.index')->with('success', 'Cập nhật quyền thành công!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Xóa user thành công!');
    }
}
