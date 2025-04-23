<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use App\Models\Log;
use App\Models\notification;
use App\Models\TicketSuport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HoTroKhachHangController extends Controller
{
    // Danh sách ticket
    public function index()
{
    $user = auth()->user();

    $query = TicketSuport::with('khachHang', 'nguoiXuLy')->latest();

    // Nếu không phải super admin và không có quyền xem toàn bộ
    if (!$user->hasRole('Super Admin') && !$user->can('xem toàn bộ hỗ trợ khách hàng')) {
        $query->where('nguoi_xu_ly_id', $user->id);
    }

    $tickets = $query->paginate(10);

    return view('admin.ho_tro_khach_hangs.index', compact('tickets'));
}


    // Form tạo mới
    public function create()
    {
        $khachHangs = KhachHang::all();
        $users = User::all();
        return view('admin.ho_tro_khach_hangs.create', compact('khachHangs', 'users'));
    }

    // Lưu ticket mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'khach_hang_id' => 'required|exists:khach_hangs,id',
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string',
            'uu_tien' => 'nullable|string',
            'trang_thai' => 'nullable|string',
            'nguoi_xu_ly_id' => 'nullable|exists:users,id',
        ], [
            'khach_hang_id.required' => 'Vui lòng chọn khách hàng.',
            'khach_hang_id.exists' => 'Khách hàng không tồn tại.',
            'tieu_de.required' => 'Vui lòng nhập tiêu đề.',
            'tieu_de.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'noi_dung.required' => 'Vui lòng nhập nội dung.',
            'nguoi_xu_ly_id.exists' => 'Người xử lý không hợp lệ.',
        ]);
        

        $khachhang = KhachHang::find($validated['khach_hang_id']);
        Log::create([
            'message' => Auth::user()->name . ' đã thêm một hỗ trợ khách hàng cho ' . $khachhang->ten,
        ]);
        TicketSuport::create($validated);
        if ($validated['nguoi_xu_ly_id']) {
            notification::create([
                'user_id' => $validated['nguoi_xu_ly_id'],
                'title' => 'Bạn đã được thêm vào xử lý cho yêu cầu hỗ trợ.',
                'message' => 'Bạn được yêu cầu hỗ trợ cho "' . $khachhang->ten . '".',
            ]);
        }

        return redirect()->route('ho_tro_khach_hangs.index')->with('success', 'Tạo yêu cầu hỗ trợ thành công.');
    }

    // Chi tiết ticket
    public function show($id)
    {
        $ticket = TicketSuport::with('khachHang', 'nguoiXuLy')->findOrFail($id);
        return view('ho_tro_khach_hangs.show', compact('ticket'));
    }

    // Form chỉnh sửa
    public function edit($id)
    {
        $ticket = TicketSuport::findOrFail($id);
        $khachHangs = KhachHang::all();
        $users = User::all();
        return view('admin.ho_tro_khach_hangs.edit', compact('ticket', 'khachHangs', 'users'));
    }

    // Lưu cập nhật
    public function update(Request $request, $id)
    {
        $ticket = TicketSuport::findOrFail($id);

        $validated = $request->validate([
            'khach_hang_id' => 'required|exists:khach_hangs,id',
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string',
            'trang_thai' => 'nullable|string',
            'uu_tien' => 'nullable|string',
            'nguoi_xu_ly_id' => 'nullable|exists:users,id',
        ], [
            'khach_hang_id.required' => 'Vui lòng chọn khách hàng.',
            'khach_hang_id.exists' => 'Khách hàng không tồn tại.',
            'tieu_de.required' => 'Vui lòng nhập tiêu đề.',
            'tieu_de.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'noi_dung.required' => 'Vui lòng nhập nội dung.',
            'nguoi_xu_ly_id.exists' => 'Người xử lý không hợp lệ.',
        ]);
        $khachhang = KhachHang::find($validated['khach_hang_id']);
        Log::create([
            'message' => Auth::user()->name . ' đã thêm một hỗ trợ khách hàng cho ' . $khachhang->ten,
        ]);
        if ($validated['nguoi_xu_ly_id']) {
            notification::create([
                'user_id' => $validated['nguoi_xu_ly_id'],
                'title' => 'Bạn đã được thêm vào xử lý cho yêu cầu hỗ trợ.',
                'message' => 'Bạn được yêu cầu hỗ trợ cho "' . $khachhang->ten . '".',
            ]);
        }
        $ticket->update($validated);

        return redirect()->route('ho_tro_khach_hangs.index')->with('success', 'Cập nhật yêu cầu thành công.');
    }

    // Xóa ticket
    public function destroy($id)
    {
        $ticket = TicketSuport::findOrFail($id);
        $ticket->delete();
        $khachhang = KhachHang::find($ticket->khach_hang_id);
        if ($ticket->nguoi_xu_ly_id) {
            notification::create([
                'user_id' => $ticket->nguoi_xu_ly_id,
                'title' => 'Bạn đã được thêm vào xử lý cho yêu cầu hỗ trợ.',
                'message' => 'Bạn được yêu cầu hỗ trợ cho "' . $khachhang->ten . '".',
            ]);
        }
        Log::create([
            'message' => Auth::user()->name . ' đã thêm một hỗ trợ khách hàng cho ' . $khachhang->ten,
        ]);
        return redirect()->route('ho_tro_khach_hangs.index')->with('success', 'Đã xóa yêu cầu hỗ trợ.');
    }
}
