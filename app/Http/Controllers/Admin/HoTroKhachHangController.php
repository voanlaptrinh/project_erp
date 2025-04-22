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
        $tickets = TicketSuport::with('khachHang', 'nguoiXuLy')->latest()->paginate(10);
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

        $request->validate([
            'khach_hang_id' => 'required|exists:khach_hangs,id',
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string',
            'trang_thai' => 'required|in:mới,đang xử lý,đã xử lý,đã đóng',
            'uu_tien' => 'required|in:thấp,trung bình,cao,khẩn cấp',
        ]);

        $ticket->update($request->all());

        return redirect()->route('ho_tro_khach_hangs.index')->with('success', 'Cập nhật yêu cầu thành công.');
    }

    // Xóa ticket
    public function destroy($id)
    {
        $ticket = TicketSuport::findOrFail($id);
        $ticket->delete();

        return redirect()->route('ho_tro_khach_hangs.index')->with('success', 'Đã xóa yêu cầu hỗ trợ.');
    }
}
