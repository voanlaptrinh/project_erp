<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Project $project = null)
    {
        // Nếu người dùng là Super Admin, họ có thể xem tất cả task
        if (auth()->user()->hasRole('Super Admin')) {
            // Nếu có project, lấy tất cả task của dự án đó
            if ($project) {
                $tasks = $project->tasks;
            } else {
                // Nếu không có project, lấy tất cả task trong hệ thống
                $tasks = Task::all();
            }
        } else {
            // Nếu không phải Super Admin, chỉ lấy các task mà người dùng đã được phân công
            if ($project) {
                // Nếu có project, lấy các task của dự án mà user được phân công
                $tasks = $project->tasks()->where('assigned_to', auth()->id())->get();
            } else {
                // Nếu không có project, lấy tất cả task mà người dùng được phân công
                $tasks = Task::where('assigned_to', auth()->id())->get();
            }
        }
    
        return view('admin.tasks.index', compact('project', 'tasks'));
    }
    // Phân quyền cho các phương thức    
    
    // Hiển thị form thêm task
public function create(Project $project)
{
    $this->authorize('tạo task', Task::class); // Phân quyền xem form

    // Lấy danh sách người dùng đã được thêm vào dự án này
    $users = $project->users;

    return view('admin.tasks.create', compact('project', 'users'));
}

// Lưu task vào CSDL
public function store(Request $request, Project $project)
{
    $this->authorize('tạo task', Task::class); // Phân quyền thêm task

    // Validate dữ liệu
    $request->validate([
        'tieu_de' => 'required|string|max:255',
        'mo_ta' => 'nullable|string',
        'do_uu_tien' => 'required|in:Thấp,Trung bình,Cao',
        'trang_thai' => 'required|in:Mới,Đang thực hiện,Hoàn thành',
        'assigned_to' => 'nullable|exists:users,id',
        'han_hoan_thanh' => 'nullable|date',
    ]);

    // Tạo task mới cho dự án
    Task::create([
        'project_id' => $project->id,
        'tieu_de' => $request->tieu_de,
        'mo_ta' => $request->mo_ta,
        'do_uu_tien' => $request->do_uu_tien,
        'trang_thai' => $request->trang_thai,
        'assigned_to' => $request->assigned_to,
        'han_hoan_thanh' => $request->han_hoan_thanh,
    ]);

    return redirect()->route('admin.projects.show', $project->id)
                     ->with('success', 'Task đã được thêm thành công!');
}

}
