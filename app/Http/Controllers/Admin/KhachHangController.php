<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use App\Models\Log;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class KhachHangController extends Controller
{
    public function __construct()
    {
        // Kiểm tra quyền của người dùng để tạo, sửa, xóa hợp đồng
        $this->middleware('can:xem khách hàng')->only(['index']);
        $this->middleware('can:tạo khách hàng')->only(['create', 'store']);
        $this->middleware('can:sửa khách hàng')->only(['edit', 'update']);
        $this->middleware('can:xóa khách hàng')->only(['destroy']);
    }
    public function index(Request $request)
    {
        $query = KhachHang::with('project');

        if ($request->has('keyword') && $request->keyword) {
            $query->where('ten', 'like', '%' . $request->keyword . '%');
        }

        if ($request->has('project_id') && $request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        $khachHangs = $query->paginate(10); // giữ lại query khi phân trang
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
        ], [
            'ten.required' => 'Vui lòng nhập tên khách hàng.',
            'ten.string' => 'Tên khách hàng phải là chuỗi ký tự.',
            'ten.max' => 'Tên khách hàng không được vượt quá 255 ký tự.',
        
            'email.email' => 'Địa chỉ email không hợp lệ.',
        
            'so_dien_thoai.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'so_dien_thoai.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
        
            'dia_chi.string' => 'Địa chỉ phải là chuỗi ký tự.',
        
            'project_id.exists' => 'Dự án được chọn không tồn tại.',
        
            'ghi_chu.string' => 'Ghi chú phải là chuỗi ký tự.',
        ]);

        $ngay = Carbon::now()->format('Ymd');
        $tenDayDu = $request->ten;
        $alias = Str::slug($tenDayDu . '-' . $ngay, '-');

        KhachHang::create([
            'ten' => $tenDayDu,
            'alias' => $alias,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'dia_chi' => $request->dia_chi,
            'project_id' => $request->project_id,
            'ghi_chu' => $request->ghi_chu,
        ]);
        Log::create([
            'message' => Auth::user()->name . ' đã tạo mới khách hàng ' . $tenDayDu
        ]);
        return redirect()->route('khach-hangs.index')->with('success', 'Thêm khách hàng thành công!');
    }

    public function edit($alias)
    {
        $khachHang = KhachHang::where('alias', $alias)->firstOrFail(); // Tìm theo alias
        $projects = Project::all();
        return view('admin.khach_hangs.edit', compact('khachHang', 'projects'));
    }
    public function show($alias)
    {
        $khachHang = KhachHang::where('alias', $alias)->firstOrFail(); // Tìm theo alias
        $projects = Project::all();
        return view('admin.khach_hangs.show', compact('khachHang', 'projects'));
    }

    public function update(Request $request, $alias)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
            'email' => 'nullable|email',
            'so_dien_thoai' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'ghi_chu' => 'nullable|string',
        ], [
            'ten.required' => 'Vui lòng nhập tên khách hàng.',
            'ten.string' => 'Tên khách hàng phải là chuỗi ký tự.',
            'ten.max' => 'Tên khách hàng không được vượt quá 255 ký tự.',
        
            'email.email' => 'Địa chỉ email không hợp lệ.',
        
            'so_dien_thoai.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'so_dien_thoai.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
        
            'dia_chi.string' => 'Địa chỉ phải là chuỗi ký tự.',
        
            'project_id.exists' => 'Dự án được chọn không tồn tại.',
        
            'ghi_chu.string' => 'Ghi chú phải là chuỗi ký tự.',
        ]);

        $khachHang = KhachHang::where('alias', $alias)->firstOrFail();

        // Nếu tên thay đổi thì cập nhật alias mới
        $tenMoi = $request->ten;
        if ($tenMoi !== $khachHang->ten) {
            $ngay = Carbon::now()->format('Ymd');
            $aliasMoi = Str::slug($tenMoi . '-' . $ngay, '-');
            $khachHang->alias = $aliasMoi;
        }

        $khachHang->ten = $tenMoi;
        $khachHang->email = $request->email;
        $khachHang->so_dien_thoai = $request->so_dien_thoai;
        $khachHang->dia_chi = $request->dia_chi;
        $khachHang->project_id = $request->project_id;
        $khachHang->ghi_chu = $request->ghi_chu;
        $khachHang->save();

        Log::create([
            'message' => Auth::user()->name . ' đã cập nhật khách hàng ' . $tenMoi
        ]);

        return redirect()->route('khach-hangs.index')->with('success', 'Cập nhật khách hàng thành công!');
    }

    public function destroy($alias)
    {
        $khachHang = KhachHang::where('alias', $alias)->firstOrFail(); // Tìm theo alias
        Log::create([
            'message' => Auth::user()->name . ' đã xóa khách hàng ' . $khachHang->ten
        ]);
        $khachHang->delete();

        return redirect()->route('khach-hangs.index')->with('success', 'Xóa khách hàng thành công!');
    }
}
