<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HopDong;
use App\Models\Project;
use Illuminate\Http\Request;

class HopDongKhachHangController extends Controller
{
    public function index()
    {
        $hopDongs = HopDong::with('project')->latest()->paginate(10);
        return view('admin.hop_dong_khach_hang.index', compact('hopDongs'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('admin.hop_dong_khach_hang.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'so_hop_dong' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'ngay_ky' => 'nullable|date',
            'ngay_het_han' => 'nullable|date|after_or_equal:ngay_ky',
            'gia_tri' => 'nullable|numeric',
            'noi_dung' => 'nullable|string',
            'trang_thai' => 'nullable|string|max:255',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('hopdongs'), $filename);
            $filePath = 'hopdongs/' . $filename;
        }

        HopDong::create([
            'project_id' => $request->project_id,
            'so_hop_dong' => $request->so_hop_dong,
            'file' => $filePath,
            'ngay_ky' => $request->ngay_ky,
            'ngay_het_han' => $request->ngay_het_han,
            'gia_tri' => $request->gia_tri,
            'noi_dung' => $request->noi_dung,
            'trang_thai' => $request->trang_thai ?? 'đang hiệu lực',
        ]);

        return redirect()->route('hop_dong_khach_hang.index')->with('success', 'Thêm hợp đồng thành công!');
    }

    public function edit($alias)
    {
        $hopDong = HopDong::whereHas('project', function ($q) use ($alias) {
            $q->where('alias', $alias);
        })->firstOrFail();

        $projects = Project::all();
        return view('admin.hop_dong_khach_hang.edit', compact('hopDong', 'projects'));
    }

    public function update(Request $request, $alias)
    {
        $hopDong = HopDong::whereHas('project', function ($q) use ($alias) {
            $q->where('alias', $alias);
        })->firstOrFail();

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'so_hop_dong' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'ngay_ky' => 'nullable|date',
            'ngay_het_han' => 'nullable|date|after_or_equal:ngay_ky',
            'gia_tri' => 'nullable|numeric',
            'noi_dung' => 'nullable|string',
            'trang_thai' => 'nullable|string|max:255',
        ]);

        // File mới nếu có
        if ($request->hasFile('file')) {
            if ($hopDong->file && file_exists(public_path($hopDong->file))) {
                unlink(public_path($hopDong->file));
            }
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('hopdongs'), $filename);
            $hopDong->file = 'hopdongs/' . $filename;
        }

        // Cập nhật thủ công
        $hopDong->project_id = $request->project_id;
        $hopDong->so_hop_dong = $request->so_hop_dong;
        $hopDong->ngay_ky = $request->ngay_ky;
        $hopDong->ngay_het_han = $request->ngay_het_han;
        $hopDong->gia_tri = $request->gia_tri;
        $hopDong->noi_dung = $request->noi_dung;
        $hopDong->trang_thai = $request->trang_thai ?? 'đang hiệu lực';

        $hopDong->save();

        return redirect()->route('hop_dong_khach_hang.index')->with('success', 'Cập nhật hợp đồng thành công!');
    }

    public function destroy($alias)
    {
        $hopDong = HopDong::whereHas('project', function ($q) use ($alias) {
            $q->where('alias', $alias);
        })->firstOrFail();

        if ($hopDong->file && file_exists(public_path($hopDong->file))) {
            unlink(public_path($hopDong->file));
        }

        $hopDong->delete();
        return redirect()->route('hop_dong_khach_hang.index')->with('success', 'Xóa hợp đồng thành công!');
    }
}
