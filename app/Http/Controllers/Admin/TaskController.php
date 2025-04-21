<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\notification;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        // Kiểm tra quyền của người dùng để tạo, sửa, xóa dự án
        $this->middleware('can:tạo task')->only(['create', 'store']);
        $this->middleware('can:sửa task')->only(['edit', 'update']);
        $this->middleware('can:xóa task')->only(['destroy']);
        $this->middleware('can:xem task')->only(['index', 'show']);
        $this->middleware('can:xem toàn bộ task')->only(['index', 'show']);
    }

    public function index(Project $project = null)
    {
        $query = Task::query();

        // Nếu là Super Admin
        if (auth()->user()->can('xem toàn bộ task')) {
            if ($project) {
                $query->where('project_id', $project->id);
            }
        } else {
            // Người thường chỉ được thấy task mình được giao
            if ($project) {
                $query->where('project_id', $project->id)
                    ->where('assigned_to', auth()->id());
            } else {
                $query->where('assigned_to', auth()->id());
            }
        }

        // Tìm kiếm theo tiêu đề
        if (request()->filled('tieu_de')) {
            $query->where('tieu_de', 'like', '%' . request('tieu_de') . '%');
        }

        // Lọc theo độ ưu tiên
        if (request()->filled('do_uu_tien')) {
            $query->where('do_uu_tien', request('do_uu_tien'));
        }

        // Lọc theo trạng thái
        if (request()->filled('trang_thai')) {
            $query->where('trang_thai', request('trang_thai'));
        }

        // Lấy kết quả (thêm paginate nếu muốn)
        $tasks = $query->paginate(20);

        return view('admin.tasks.index', compact('project', 'tasks'));
    }

    // Phân quyền cho các phương thức    

    // Hiển thị form thêm task
    public function create(Project $project)
    {
      

        return view('admin.tasks.create', compact('project'));
    }

    // Lưu task vào CSDL
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'do_uu_tien' => 'required|in:Thấp,Trung bình,Cao',
            'trang_thai' => 'required|in:Mới,Đang thực hiện,Hoàn thành',
            'han_hoan_thanh' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ], [
            'tieu_de.required' => 'Tiêu đề không được để trống',
            'do_uu_tien.required' => 'Độ ưu tiên không được để trống',
            'trang_thai.required' => 'Trạng thái không được để trống',
            'assigned_to.exists' => 'Người được giao không tồn tại trong hệ thống',
            'han_hoan_thanh.date' => 'Hạn hoàn thành phải là một ngày hợp lệ',
            'mo_ta.string' => 'Mô tả phải là một chuỗi',
            'do_uu_tien.in' => 'Độ ưu tiên không hợp lệ',
            'trang_thai.in' => 'Trạng thái không hợp lệ',
            'tieu_de.string' => 'Tiêu đề phải là một chuỗi',
            'tieu_de.max' => 'Tiêu đề không được vượt quá 255 ký tự',


        ]);

        $task = new Task([
            'tieu_de' => $request->tieu_de,
            'mo_ta' => $request->mo_ta,
            'do_uu_tien' => $request->do_uu_tien,
            'trang_thai' => $request->trang_thai,
            'han_hoan_thanh' => $request->han_hoan_thanh,
            'assigned_to' => $request->assigned_to,
        ]);
        $task->project()->associate($project);
        $task->save();
        notification::create([
            'user_id' => $task->assigned_to,
            'title' => 'Bạn được giao một task mới',
            'message' => 'Bạn vừa được giao công việc "' . $task->tieu_de . '" trong dự án "' . $task->project->ten_du_an . '".',
        ]);

        Log::create([
            'message' => Auth::user()->name . ' đã tạo task "' . $task->tieu_de . '" trong dự án "' . $project->ten_du_an . '"'
        ]);
        return redirect()->route('admin.projects.tasks', $project->alias)->with('success', 'Tạo task thành công!');
    }
    public function edit(Project $project, Task $task)
    {
        
        // Lấy danh sách người dùng thuộc dự án để chọn làm "assigned_to"
        $users = $project->users;

        return view('admin.tasks.edit', [
            'project' => $project,
            'task' => $task,
            'users' => $users,
        ]);
    }
    public function show(Project $project, Task $task)
    {
      

        // Lấy danh sách người dùng thuộc dự án để chọn làm "assigned_to"
        $users = $project->users;

        return view('admin.tasks.show', [
            'project' => $project,
            'task' => $task,
            'users' => $users,
        ]);
    }

    public function update(Request $request, Project $project, Task $task)
    {
        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'do_uu_tien' => 'required|in:Thấp,Trung bình,Cao',
            'trang_thai' => 'required|in:Mới,Đang thực hiện,Hoàn thành',
            'han_hoan_thanh' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ], [
            'tieu_de.required' => 'Tiêu đề không được để trống',
            'do_uu_tien.required' => 'Độ ưu tiên không được để trống',
            'trang_thai.required' => 'Trạng thái không được để trống',
            'assigned_to.exists' => 'Người được giao không tồn tại trong hệ thống',
            'han_hoan_thanh.date' => 'Hạn hoàn thành phải là một ngày hợp lệ',
            'mo_ta.string' => 'Mô tả phải là một chuỗi',
            'do_uu_tien.in' => 'Độ ưu tiên không hợp lệ',
            'trang_thai.in' => 'Trạng thái không hợp lệ',
            'tieu_de.string' => 'Tiêu đề phải là một chuỗi',
            'tieu_de.max' => 'Tiêu đề không được vượt quá 255 ký tự',

        ]);

        $oldAssignee = $task->assigned_to;

        $task->update([
            'tieu_de' => $request->tieu_de,
            'mo_ta' => $request->mo_ta,
            'do_uu_tien' => $request->do_uu_tien,
            'trang_thai' => $request->trang_thai,
            'han_hoan_thanh' => $request->han_hoan_thanh,
            'assigned_to' => $request->assigned_to,
        ]);

        // Nếu có thay đổi người được giao
        if ($oldAssignee != $request->assigned_to && $request->assigned_to) {
            Notification::create([
                'user_id' => $request->assigned_to,
                'title' => 'Bạn được giao lại một công việc',
                'message' => 'Bạn vừa được giao task "' . $task->tieu_de . '" trong dự án "' . $project->ten_du_an . '".',
            ]);
        }

        Log::create([
            'message' => Auth::user()->name . ' đã cập nhật task "' . $task->tieu_de . '" trong dự án "' . $project->ten_du_an . '"'
        ]);

        return redirect()->route('admin.projects.tasks', $project->alias)->with('success', 'Cập nhật task thành công!');
    }
    public function destroy(Project $project, Task $task)
    {
    

        // Thông báo cho người được giao (nếu có)
        if ($task->assigned_to) {
            Notification::create([
                'user_id' => $task->assigned_to,
                'title' => 'Một task đã bị xóa',
                'message' => 'Task "' . $task->tieu_de . '" trong dự án "' . $project->ten_du_an . '" đã bị xóa.',
            ]);
        }

        $task->delete();

        Log::create([
            'message' => Auth::user()->name . ' đã xóa task "' . $task->tieu_de . '" trong dự án "' . $project->ten_du_an . '"'
        ]);

        return redirect()->route('admin.projects.tasks', $project->alias)->with('success', 'Xóa task thành công!');
    }
}
