<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaoGia;
use App\Models\HopDong;
use Illuminate\Http\Request;

class BaoGiaController extends Controller
{

    // Danh sách báo giá (toàn bộ hoặc theo hợp đồng)
    public function index(Request $request, $hop_dong_id = null)
    {
        if ($hop_dong_id) {
            $hopDong = HopDong::with('project')->findOrFail($hop_dong_id);
            $baoGias = BaoGia::where('hop_dong_id', $hop_dong_id)->latest()->get();
        } else {
            $hopDong = null;
            $baoGias = BaoGia::with('hopDong.duAn')->latest()->get();
        }

        return view('admin.bao_gias.index', compact('baoGias', 'hopDong'));
    }

    // Form tạo báo giá cho 1 hợp đồng cụ thể
    public function create()
    {
       
           $hopDong = HopDong::all();

        return view('admin.bao_gias.create', compact('hopDong'));
    }

    // Lưu báo giá mới
    public function store(Request $request)
    {
        $request->validate([
            'hop_dong_id' => 'required|exists:hop_dongs,id',
            'so_bao_gia' => 'required|unique:bao_gias,so_bao_gia',
            'ngay_gui' => 'nullable|date',
            'tong_gia_tri' => 'nullable|integer',
            'chi_tiet' => 'nullable|string',
            'trang_thai' => 'nullable|string',
        ]);

        $hopDong = HopDong::findOrFail($request->hop_dong_id);

        if ($hopDong->baoGias()->exists()) {
            return redirect()->route('bao-gias.byHopDong', $hopDong->id)
                ->with('error', 'Hợp đồng này đã có báo giá. Không thể tạo thêm.');
        }

        BaoGia::create($request->all());

        return redirect()->route('bao-gias.byHopDong', $hopDong->id)
            ->with('success', 'Tạo báo giá thành công.');
    }

    // Form chỉnh sửa báo giá
    public function edit($id)
    {
        $baoGia = BaoGia::findOrFail($id);
        return view('bao_gias.edit', compact('baoGia'));
    }

    // Cập nhật báo giá
    public function update(Request $request, $id)
    {
        $baoGia = BaoGia::findOrFail($id);

        $request->validate([
            'so_bao_gia' => 'required|unique:bao_gias,so_bao_gia,' . $id,
            'ngay_gui' => 'nullable|date',
            'tong_gia_tri' => 'nullable|integer',
            'chi_tiet' => 'nullable|string',
            'trang_thai' => 'nullable|string',
        ]);

        $baoGia->update($request->all());

        return redirect()->route('bao-gias.byHopDong', $baoGia->hop_dong_id)
            ->with('success', 'Cập nhật báo giá thành công.');
    }

    // Xóa báo giá
    public function destroy($id)
    {
        $baoGia = BaoGia::findOrFail($id);
        $hop_dong_id = $baoGia->hop_dong_id;

        $baoGia->delete();

        return redirect()->route('bao-gias.byHopDong', $hop_dong_id)
            ->with('success', 'Xóa báo giá thành công.');
    }
}
