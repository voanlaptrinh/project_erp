<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\notification;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function __construct()
    {
        // Kiểm tra quyền của người dùng để tạo, sửa, xóa dự án
        $this->middleware('can:tạo dự án')->only(['create', 'store']);
        $this->middleware('can:sửa dự án')->only(['edit', 'update']);
        $this->middleware('can:xóa dự án')->only(['destroy']);
        $this->middleware('can:xem dự án')->only(['index', 'show']);
        $this->middleware('can:xem toàn bộ dự án')->only(['index', 'show']);
    }

    // Xem tất cả dự án hoặc dự án được phân quyền
    public function index(Request $request)
    {
        if (Auth::user()->can('xem dự án') || Auth::user()->can('xem toàn bộ dự án')) {
            $search = $request->input('search');
            if (Auth::user()->can('xem toàn bộ dự án')) {
                $projects = Project::when($search, function ($query) use ($search) {
                    return $query->where('ten_du_an', 'like', '%' . $search . '%');
                })
                    ->paginate(20);
            } else {
                $projects = Auth::user()->projects()
                    ->when($search, function ($query) use ($search) {
                        return $query->where('ten_du_an', 'like', '%' . $search . '%');
                    })
                    ->paginate(20);
            }
            // Log::create([
            //     'message' => Auth::user()->name . ' đã xem danh sách dự án'
            // ]);
            return view('admin.projects.index', compact('projects', 'search'));
        }

        return abort(403, 'Bạn không có quyền xem dự án.');
    }




    public function create()
    {

        $users = User::all();
        return view('admin.projects.create', compact('users'));
    }

    // Lưu dự án mới
    public function store(Request $request)
    {
        $this->authorize('tạo dự án');  // Kiểm tra quyền

        $request->validate([
            'ten_du_an' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'required|in:Chưa bắt đầu,Đang thực hiện,Tạm dừng,Hoàn thành',
            'ngay_bat_dau' => 'nullable|date',
            'ngay_ket_thuc' => 'nullable|date|after_or_equal:ngay_bat_dau',
            'user_ids' => 'required|array', // Danh sách người phụ trách
            'user_ids.*' => 'exists:users,id', // Đảm bảo tất cả user_id là hợp lệ
        ], [
            // Các thông báo lỗi tùy chỉnh
            'ten_du_an.required' => 'Tên dự án là bắt buộc.',
            'ten_du_an.string' => 'Tên dự án phải là một chuỗi văn bản.',
            'ten_du_an.max' => 'Tên dự án không được quá 255 ký tự.',

            'mo_ta.string' => 'Mô tả phải là một chuỗi văn bản.',

            'trang_thai.required' => 'Trạng thái dự án là bắt buộc.',
            'trang_thai.in' => 'Trạng thái dự án phải là một trong các giá trị: Chưa bắt đầu, Đang thực hiện, Tạm dừng, Hoàn thành.',

            'ngay_bat_dau.date' => 'Ngày bắt đầu phải là một ngày hợp lệ.',

            'ngay_ket_thuc.date' => 'Ngày kết thúc phải là một ngày hợp lệ.',
            'ngay_ket_thuc.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'user_ids.required' => 'Bạn phải chọn ít nhất một người phụ trách cho dự án.',
            'user_ids.array' => 'Danh sách người phụ trách phải là một mảng.',
            'user_ids.*.exists' => 'Một hoặc nhiều người phụ trách không hợp lệ.',
        ]);
        $alias = Str::slug($request->ten_du_an) . '-' . now()->format('YmdHis');
        $project = Project::create([
            'ten_du_an' => $request->input('ten_du_an'),
            'mo_ta' => $request->input('mo_ta'),
            'alias' => $alias,
            'trang_thai' => $request->input('trang_thai'),
            'ngay_bat_dau' => $request->input('ngay_bat_dau'),
            'ngay_ket_thuc' => $request->input('ngay_ket_thuc'),
        ]);
        foreach ($request->user_ids as $userId) {
            notification::create([
                'user_id' => $userId,
                'title' => 'Bạn được thêm vào dự án mới',
                'message' => 'Bạn đã được thêm vào dự án "' . $project->ten_du_an . '".',
            ]);
        }
        Log::create([
            'message' => Auth::user()->name . ' đã tạo dự án ' . $request->ten_du_an
        ]);
        // Gán người phụ trách cho dự án
        $project->users()->sync($request->input('user_ids'));
        return redirect()->route('admin.projects.index')->with('success', 'Dự án đã được thêm thành công!');
    }

    // Chỉ Admin mới có quyền sửa dự án
    public function edit($alias)
    {

        $project = Project::where('alias', $alias)->firstOrFail();
        $users = User::all();
        $assignedUsers = $project->users->pluck('id')->toArray();

        return view('admin.projects.edit', compact('project', 'users', 'assignedUsers'));
    }
    public function show($alias)
    {

        $project = Project::where('alias', $alias)->firstOrFail();
        $users = User::all();
        $assignedUsers = $project->users->pluck('id')->toArray();
        Log::create([
            'message' => Auth::user()->name . ' đã xem dự án ' . $project->ten_du_an
        ]);
        return view('admin.projects.show', compact('project', 'users', 'assignedUsers'));
    }

    // Cập nhật dự án
    public function update(Request $request, $alias)
    {


        // Xác thực dữ liệu
        $request->validate([
            'ten_du_an' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'required|in:Chưa bắt đầu,Đang thực hiện,Tạm dừng,Hoàn thành',
            'ngay_bat_dau' => 'nullable|date',
            'ngay_ket_thuc' => 'nullable|date|after_or_equal:ngay_bat_dau',
            'user_ids' => 'required|array', // Danh sách người phụ trách
            'user_ids.*' => 'exists:users,id', // Đảm bảo tất cả user_id là hợp lệ
        ], [
            // Các thông báo lỗi tùy chỉnh
            'ten_du_an.required' => 'Tên dự án là bắt buộc.',
            'ten_du_an.string' => 'Tên dự án phải là một chuỗi văn bản.',
            'ten_du_an.max' => 'Tên dự án không được quá 255 ký tự.',

            'mo_ta.string' => 'Mô tả phải là một chuỗi văn bản.',

            'trang_thai.required' => 'Trạng thái dự án là bắt buộc.',
            'trang_thai.in' => 'Trạng thái dự án phải là một trong các giá trị: Chưa bắt đầu, Đang thực hiện, Tạm dừng, Hoàn thành.',

            'ngay_bat_dau.date' => 'Ngày bắt đầu phải là một ngày hợp lệ.',

            'ngay_ket_thuc.date' => 'Ngày kết thúc phải là một ngày hợp lệ.',
            'ngay_ket_thuc.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'user_ids.required' => 'Bạn phải chọn ít nhất một người phụ trách cho dự án.',
            'user_ids.array' => 'Danh sách người phụ trách phải là một mảng.',
            'user_ids.*.exists' => 'Một hoặc nhiều người phụ trách không hợp lệ.',
        ]);
        $project = Project::where('alias', $alias)->firstOrFail();
        // Cập nhật trạng thái và các trường còn lại
        $alias = Str::slug($request->ten_du_an) . '-' . now()->format('YmdHis');
        $project->update([
            'ten_du_an' => $request->input('ten_du_an'),
            'mo_ta' => $request->input('mo_ta'),
            'trang_thai' => $request->input('trang_thai'),
            'ngay_bat_dau' => $request->input('ngay_bat_dau'),
            'ngay_ket_thuc' => $request->input('ngay_ket_thuc'),
            'alias' => $alias,
        ]);

        // Cập nhật người phụ trách cho dự án
        $project->users()->sync($request->input('user_ids'));
        Log::create([
            'message' => Auth::user()->name . ' đã cập nhật dự án ' . $request->ten_du_an
        ]);
        return redirect()->route('admin.projects.index')->with('success', 'Dự án đã được cập nhật thành công!');
    }


    // Chỉ Admin mới có quyền xóa dự án
    public function destroy($alias)
    {


        $project = Project::where('alias', $alias)->firstOrFail();
        $users = $project->users;
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Dự án đã bị xóa',
                'message' => 'Dự án "' . $project->ten_du_an . '" mà bạn tham gia đã bị xóa.',
            ]);
        }
        $project->delete();
        Log::create([
            'message' => Auth::user()->name . ' đã xóa dự án ' . $project->ten_du_an
        ]);
        return redirect()->route('admin.projects.index')->with('success', 'Dự án đã được xóa thành công!');
    }
}