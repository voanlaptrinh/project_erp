<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
class KhachHangController extends Controller
{
    public function index(Request $request)
{
    $query = KhachHang::with('project');

    if ($request->has('keyword') && $request->keyword) {
        $query->where('ten', 'like', '%' . $request->keyword . '%');
    }

    if ($request->has('project_id') && $request->project_id) {
        $query->where('project_id', $request->project_id);
    }

    $khachHangs = $query->latest()->paginate(10)->withQueryString(); // giữ lại query khi phân trang
    $projects = Project::all(); // để đổ ra dropdown

    return view('admin.khach_hangs.index', compact('khachHangs', 'projects'));
}

    public function create()
    {
        $projects = Project::all();
        return view('admin.khach_hangs.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
            'email' => 'nullable|email',
            'so_dien_thoai' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $ngay = Carbon::now()->format('Ymd');
        $tenDayDu = $request->ten . ' ' . $ngay;
        $alias = Str::slug($tenDayDu);

        KhachHang::create([
            'ten' => $tenDayDu,
            'alias' => $alias,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'dia_chi' => $request->dia_chi,
            'project_id' => $request->project_id,
            'ghi_chu' => $request->ghi_chu,
        ]);

        return redirect()->route('khach-hangs.index')->with('success', 'Thêm khách hàng thành công!');
    }

    public function edit($alias)
{
    $khachHang = KhachHang::where('alias', $alias)->firstOrFail(); // Tìm theo alias
    $projects = Project::all();
    return view('admin.khach_hangs.edit', compact('khachHang', 'projects'));
}

public function update(Request $request, $alias)
{
    $request->validate([
        'ten' => 'required|string|max:255',
        'email' => 'nullable|email',
        'so_dien_thoai' => 'nullable|string|max:20',
        'dia_chi' => 'nullable|string',
        'project_id' => 'nullable|exists:projects,id',
    ]);

    $khachHang = KhachHang::where('alias', $alias)->firstOrFail(); // Tìm theo alias
    $khachHang->update($request->all());

    return redirect()->route('khach-hangs.index')->with('success', 'Cập nhật khách hàng thành công!');
}


public function destroy($alias)
{
    $khachHang = KhachHang::where('alias', $alias)->firstOrFail(); // Tìm theo alias
    $khachHang->delete();

    return redirect()->route('khach-hangs.index')->with('success', 'Xóa khách hàng thành công!');
}
}
