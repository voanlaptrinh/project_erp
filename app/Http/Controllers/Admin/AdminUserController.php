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
    public function __construct()
    {
        // Kiểm tra quyền của người dùng để tạo, sửa, xóa hợp đồng
        $this->middleware('can:thêm người dùng')->only(['create', 'store']);
        $this->middleware('can:sửa người dùng')->only(['edit', 'update']);
        $this->middleware('can:xóa người dùng')->only(['destroy']);
        $this->middleware('can:xem người dùng')->only(['index']);
    }
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
            'roles' => 'required|array', // Đảm bảo là mảng
            'so_dien_thoai' => 'nullable|string|max:15',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|string|in:Nam,Nữ',
            'vi_tri' => 'nullable|string|max:255',
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
            'roles.*.exists' => 'Vai trò không hợp lệ.',
            'so_dien_thoai.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'so_dien_thoai.max' => 'Số điện thoại không được quá 15 ký tự.',
            'ngay_sinh.date' => 'Ngày sinh không hợp lệ.',
            'gioi_tinh.string' => 'Giới tính phải là chuỗi ký tự.',
            'gioi_tinh.in' => 'Giới tính không hợp lệ. Chỉ chấp nhận "Nam" hoặc "Nữ".',
            'vi_tri.string' => 'Vị trí phải là chuỗi ký tự.',
            'vi_tri.max' => 'Vị trí không được quá 255 ký tự.',
        ]);


        // Tạo người dùng
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'ngay_sinh' => $request->ngay_sinh,
            'gioi_tinh' => $request->gioi_tinh,
            'vi_tri' => $request->vi_tri,
            'ngay_vao_lam' => now(),
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
        $request->validate([
            'name' => 'required|string|max:255',
            'so_dien_thoai' => 'nullable|string|max:15',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|string|in:Nam,Nữ',
            'vi_tri' => 'nullable|string|max:255',
            'ngay_vao_lam' => 'nullable|date',
            'roles' => 'required|array',
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->so_dien_thoai = $request->so_dien_thoai;
        $user->ngay_sinh = $request->ngay_sinh;
        $user->gioi_tinh = $request->gioi_tinh;
        $user->vi_tri = $request->vi_tri;
        $user->ngay_vao_lam = $request->ngay_vao_lam;
        $user->save();

        // Gán lại vai trò
        $user->syncRoles($request->roles);

        Log::create([
            'message' => Auth::user()->name . ' đã cập nhật thông tin và quyền cho người dùng ' . $user->name
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công!');
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