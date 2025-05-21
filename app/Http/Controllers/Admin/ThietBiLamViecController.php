<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ThietBiLamViec;
use App\Models\User;

class ThietBiLamViecController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:xem thiết bị')->only(['index', 'show']);
        $this->middleware('can:thêm thiết bị')->only(['create', 'store']);
        $this->middleware('can:sửa thiết bị')->only(['edit', 'update']);
        $this->middleware('can:xóa thiết bị')->only(['destroy']);
    }

    public function index()
    {
        if (Auth::user()->hasRole('Super Admin')) {
            $devices = ThietBiLamViec::with('user')->latest()->paginate(10);
        } else {
            $devices = ThietBiLamViec::with('user')
                ->where('user_id', Auth::id())
                ->latest()
                ->paginate(10);
        }

        return view('admin.thiet_bi_lam_viec.index', compact('devices'));
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
            'ten_thiet_bi' => 'nullable|string|max:255',
            'he_dieu_hanh' => 'nullable|string|max:255',
            'cau_hinh' => 'nullable|string',
            'so_serial' => 'nullable|string|max:255',
            'ngay_ban_giao' => 'nullable|date',
            'ghi_chu' => 'nullable|string',
        ]);

        ThietBiLamViec::create($request->all());

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

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'loai_thiet_bi' => 'required|string|max:255',
            'ten_thiet_bi' => 'nullable|string|max:255',
            'he_dieu_hanh' => 'nullable|string|max:255',
            'cau_hinh' => 'nullable|string',
            'so_serial' => 'nullable|string|max:255',
            'ngay_ban_giao' => 'nullable|date',
            'ghi_chu' => 'nullable|string',
        ]);

        $device->update($request->all());

        return redirect()->route('thietbi.index')->with('success', 'Thiết bị đã được cập nhật.');
    }

    public function destroy($id)
    {
        $device = ThietBiLamViec::findOrFail($id);
        $device->delete();

        return redirect()->route('thietbi.index')->with('success', 'Thiết bị đã được xóa.');
    }
}