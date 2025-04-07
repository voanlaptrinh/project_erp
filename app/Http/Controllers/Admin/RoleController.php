<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RoleController extends Controller
{
    // Constructor to apply authorization middleware
    public function __construct()
    {
        $this->middleware('role:Super Admin');
    }

    // Display list of roles
    public function index()
    {
        // Get all roles with their permissions
        $roles = Role::with('permissions')->paginate(10);

        return view('admin.roles.index', compact('roles'));
    }

    // Show the form to create a new role
    public function create()
    {
        // Get all permissions
        $permissions = Permission::all();

        return view('admin.roles.create', compact('permissions'));
    }

    // Store a newly created role
    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array', // Kiểm tra nếu có ít nhất 1 quyền
        ]);

        // Tạo vai trò mới
        $role = Role::create(['name' => $request->name]);

        // Gán quyền cho vai trò, dùng tên quyền thay vì ID
        $permissions = Permission::whereIn('name', $request->permissions)->get();
        $role->givePermissionTo($permissions);
        Log::create([
            'message' => Auth::user()->name . 'Tạo vai trò mới: ' . $role->name
        ]);
        return redirect()->route('admin.roles.index')->with('success', 'Tạo vai trò và gán quyền thành công.');
    }

    
    // Show the form for editing the specified role
    public function edit(Role $role)
    {
        // Get all permissions
        $permissions = Permission::all();

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    // Update the specified role in storage
    public function update(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissions = Permission::whereIn('id', $request->permissions)->get();

        // Gán quyền cho vai trò
        $role->syncPermissions($permissions);
        Log::create([
            'message' => Auth::user()->name . ' đã cập nhật vai trò: ' . $role->name
        ]);
        return redirect()->route('admin.roles.index')->with('success', 'Cập nhật vai trò thành công.');
    }

    // Remove the specified role from storage
    public function destroy(Role $role)
    {
        // Delete the role
        $role->delete();
        Log::create([
            'message' => Auth::user()->name . ' đã xóa vai trò: ' . $role->name
        ]);
        // Redirect back with success message
        return redirect()->route('admin.roles.index')->with('success', 'Xóa vai trò thành công.');
    }
}
