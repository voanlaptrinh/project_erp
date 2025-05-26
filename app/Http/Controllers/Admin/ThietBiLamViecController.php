<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ThietBiLamViec;
use App\Models\User;
use App\Models\Log;
use App\Models\notification;

class ThietBiLamViecController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:xem thiết bị')->only(['index', 'show']);
        $this->middleware('can:thêm thiết bị')->only(['create', 'store']);
        $this->middleware('can:sửa thiết bị')->only(['edit', 'update']);
        $this->middleware('can:xóa thiết bị')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = ThietBiLamViec::with('user');

        if (Auth::user()->hasRole('Super Admin')) {
            // Super Admin xem tất cả
        } else {
            $query->where('user_id', Auth::id());
        }

        // Tìm kiếm theo tên thiết bị
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('ten_thiet_bi', 'like', '%' . $request->search . '%')
                    ->orWhere('loai_thiet_bi', 'like', '%' . $request->search . '%');
            });
        }

        // Lọc theo khoảng ngày bàn giao
        if ($request->filled('start_date')) {
            $query->whereDate('ngay_ban_giao', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('ngay_ban_giao', '<=', $request->end_date);
        }

        // Lọc theo loại thiết bị
        if ($request->filled('loai_thiet_bi')) {
            $query->where('loai_thiet_bi', $request->loai_thiet_bi);
        }
        // Lấy danh sách loại thiết bị duy nhất để đổ vào select
        $loaiThietBis = ThietBiLamViec::select('loai_thiet_bi')->distinct()->pluck('loai_thiet_bi');

        $devices = $query->latest()->paginate(10);

        // Ghi log hệ thống
        Log::create([
            'message' => auth()->user()->name . 'đã xem danh sách thiết bị"' . '.',
        ]);

        return view('admin.thiet_bi_lam_viec.index', compact('devices', 'loaiThietBis'));
    }

    public function show($id)
    {
        $device = ThietBiLamViec::with('user')->findOrFail($id);
        return view('admin.thiet_bi_lam_viec.show', compact('device'));
    }

    public function create()
    {
        $users = User::all(); // Chọn user được bàn giao thiết bị
        return view('admin.thiet_bi_lam_viec.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'loai_thiet_bi' => 'required|string|max:255',
            'ten_thiet_bi' => 'required|string|max:255',
            'he_dieu_hanh' => 'required|string|max:255',
            'cau_hinh' => 'required|string',
            'so_serial' => 'required|string|max:255',
            'ngay_ban_giao' => 'required|date',
            'ghi_chu' => 'nullable|string',
        ], [
            'loai_thiet_bi.required' => 'Vui lòng nhập loại thiết bị.',
            'ten_thiet_bi.required' => 'Vui lòng nhập tên thiết bị.',
            'he_dieu_hanh.required' => 'Vui lòng nhập hệ đều hành.',
            'cau_hinh.required' => 'Vui lòng nhập cấu hình.',
            'so_serial.required' => 'Vui lòng nhập số seri.',
        ]);

        $device = ThietBiLamViec::create($request->all());

        // Tạo thống báo cho người bán giao
        notification::create([
            'user_id' => $device->user_id,
            'title' => 'Bạn được bàn giao thiết bị mới',
            'message' => 'Bạn vừa được bàn giao thiết bị "' . $device->ten_thiet_bi . '" vào ngày ' . \Carbon\Carbon::parse($device->ngay_ban_giao)->format('d/m/Y') . '.',
            'is_read' => false,
        ]);

        // Ghi log hệ thống
        Log::create([
            'message' => auth()->user()->name . 'đã tạo thiết bị"' . $device->ten_thiet_bi . '.',
        ]);

        return redirect()->route('thietbi.index')->with('success', 'Thiết bị đã được thêm.');
    }

    public function edit($id)
    {
        $device = ThietBiLamViec::findOrFail($id);
        $users = User::all();

        return view('admin.thiet_bi_lam_viec.edit', compact('device', 'users'));
    }

    public function update(Request $request, $id)
    {
        $device = ThietBiLamViec::findOrFail($id);
        $oldUserId = $device->user_id;

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'loai_thiet_bi' => 'required|string|max:255',
            'ten_thiet_bi' => 'nullable|string|max:255',
            'he_dieu_hanh' => 'nullable|string|max:255',
            'cau_hinh' => 'nullable|string',
            'so_serial' => 'nullable|string|max:255',
            'ngay_ban_giao' => 'nullable|date',
            'ghi_chu' => 'nullable|string',
        ], [
            'loai_thiet_bi.required' => 'Vui lòng nhập loại thiết bị.',
            'ten_thiet_bi.required' => 'Vui lòng nhập tên thiết bị.',
            'he_dieu_hanh.required' => 'Vui lòng nhập hệ đều hành.',
            'cau_hinh.required' => 'Vui lòng nhập cấu hình.',
            'so_serial.required' => 'Vui lòng nhập số seri.',
        ]);

        $device->update($request->all());
        // Ghi log hệ thống

        // Nếu có thay đổi người được giao thì gửi notification
        if ($oldUserId != $request->user_id) {
            notification::create([
                'user_id' => $request->user_id,
                'title' => 'Bạn được bàn giao thiết bị mới',
                'message' => 'Bạn vừa được bàn giao thiết bị "' . $device->ten_thiet_bi . '" vào ngày ' . \Carbon\Carbon::parse($device->ngay_ban_giao)->format('d/m/Y') . '.',
                'is_read' => false,
            ]);
        }

        Log::create([
            'message' => auth()->user()->name . 'đã cập nhật thiết bị"' . $device->ten_thiet_bi . '.',
        ]);

        return redirect()->route('thietbi.index')->with('success', 'Thiết bị đã được cập nhật.');
    }

    public function destroy($id)
    {
        $device = ThietBiLamViec::findOrFail($id);
        notification::create([
            'user_id' => $device->user_id,
            'title' => 'Thiết bị đã bị thu hồi',
            'message' => 'Thiết bị "' . $device->ten_thiet_bi . '" đã bị thu hồi khỏi tài khoản của bạn.',
            'is_read' => false,
        ]);
        $device->delete();

        // Ghi log hệ thống
        Log::create([
            'message' => auth()->user()->name . 'đã xóa thiết bị"' . $device->ten_thiet_bi . '.',
        ]);

        return redirect()->route('thietbi.index')->with('success', 'Thiết bị đã được xóa.');
    }
}