<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HopDong;
use App\Models\Project;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Settings;
class HopDongKhachHangController extends Controller
{
    public function index(Request $request)
    {
        $project_id = $request->input('project_id');

        $projects = Project::all(); // Dùng để hiển thị select box

        $hopDongs = HopDong::with('project')
            ->when($project_id, function ($query) use ($project_id) {
                $query->where('project_id', $project_id);
            })
            ->latest()
            ->paginate(10);

        return view('admin.hop_dong_khach_hang.index', compact('hopDongs', 'projects', 'project_id'));
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
        ], [
            'project_id.required' => 'Vui lòng chọn dự án.',
            'project_id.exists' => 'Dự án đã chọn không tồn tại.',
            'so_hop_dong.max' => 'Số hợp đồng không được vượt quá 255 ký tự.',
            'file.mimes' => 'Tệp phải có định dạng: pdf, doc, hoặc docx.',
            'file.max' => 'Tệp không được lớn hơn 2MB.',
            'ngay_het_han.after_or_equal' => 'Ngày hết hạn phải sau hoặc bằng ngày ký.',
            'gia_tri.numeric' => 'Giá trị hợp đồng phải là số.',
        ]);


        $filePath = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = time() . '_' . Str::slug($originalName);
            $storagePath = public_path('hopdongs');

            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }

            if (in_array($extension, ['doc', 'docx'])) {
                // Chuyển file Word sang PDF
                $wordPath = $storagePath . '/' . $filename . '.' . $extension;
                $file->move($storagePath, $filename . '.' . $extension);

                $phpWord = IOFactory::load($wordPath);
                $pdfPath = $storagePath . '/' . $filename . '.pdf';

                $domPdfPath = base_path('vendor/dompdf/dompdf');
                \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
                \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');

                $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
                $pdfWriter->save($pdfPath);
                unlink($wordPath);
                $filePath = 'hopdongs/' . $filename . '.pdf';
            } else {
                // Nếu là PDF thì chỉ lưu lại
                $file->move($storagePath, $filename . '.pdf');
                $filePath = 'hopdongs/' . $filename . '.pdf';
            }
        }

        $project = Project::findOrFail($request->project_id);
        $alias = Str::slug($project->ten_du_an . '-' . now()->timestamp);

        HopDong::create([
            'project_id' => $request->project_id,
            'so_hop_dong' => $request->so_hop_dong,
            'file' => $filePath,
            'alias' => $alias,
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
        $hopDong = HopDong::where('alias', $alias)->firstOrFail();

        $projects = Project::all();
        return view('admin.hop_dong_khach_hang.edit', compact('hopDong', 'projects'));
    }

    public function update(Request $request, $alias)
    {
        $hopDong = HopDong::where('alias', $alias)->firstOrFail();
    
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'so_hop_dong' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'ngay_ky' => 'nullable|date',
            'ngay_het_han' => 'nullable|date|after_or_equal:ngay_ky',
            'gia_tri' => 'nullable|numeric',
            'noi_dung' => 'nullable|string',
            'trang_thai' => 'nullable|string|max:255',
        ], [
            'project_id.required' => 'Vui lòng chọn dự án.',
            'project_id.exists' => 'Dự án đã chọn không tồn tại.',
            'so_hop_dong.max' => 'Số hợp đồng không được vượt quá 255 ký tự.',
            'file.mimes' => 'Tệp phải có định dạng: pdf, doc, hoặc docx.',
            'file.max' => 'Tệp không được lớn hơn 2MB.',
            'ngay_het_han.after_or_equal' => 'Ngày hết hạn phải sau hoặc bằng ngày ký.',
            'gia_tri.numeric' => 'Giá trị hợp đồng phải là số.',
        ]);
    
        if ($request->hasFile('file')) {
            // Xóa file cũ nếu có
            if ($hopDong->file && file_exists(public_path($hopDong->file))) {
                unlink(public_path($hopDong->file));
            }
    
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = time() . '_' . Str::slug($originalName);
            $storagePath = public_path('hopdongs');
    
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }
    
            if (in_array($extension, ['doc', 'docx'])) {
                // Lưu file word tạm
                $wordPath = $storagePath . '/' . $filename . '.' . $extension;
                $file->move($storagePath, $filename . '.' . $extension);
    
                // Chuyển sang PDF
                $phpWord = IOFactory::load($wordPath);
                $pdfPath = $storagePath . '/' . $filename . '.pdf';
    
                Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));
                Settings::setPdfRendererName('DomPDF');
    
                $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');
                $pdfWriter->save($pdfPath);
    
                // Xoá file Word sau khi convert
                unlink($wordPath);
    
                $hopDong->file = 'hopdongs/' . $filename . '.pdf';
            } else {
                // Nếu là PDF thì lưu như thường
                $file->move($storagePath, $filename . '.pdf');
                $hopDong->file = 'hopdongs/' . $filename . '.pdf';
            }
        }
    
        // Cập nhật thông tin khác
        $hopDong->project_id = $request->project_id;
        $hopDong->so_hop_dong = $request->so_hop_dong;
        $hopDong->ngay_ky = $request->ngay_ky;
        $hopDong->ngay_het_han = $request->ngay_het_han;
        $hopDong->gia_tri = $request->gia_tri;
        $hopDong->noi_dung = $request->noi_dung;
        $hopDong->trang_thai = $request->trang_thai ?? 'đang hiệu lực';
    
        // Cập nhật alias mới
        $project = Project::findOrFail($request->project_id);
        $hopDong->alias = Str::slug($project->ten_du_an . '-' . now()->timestamp);
    
        $hopDong->save();
    
        return redirect()->route('hop_dong_khach_hang.index')->with('success', 'Cập nhật hợp đồng thành công!');
    }

    public function destroy($alias)
    {
        $hopDong = HopDong::where('alias', $alias)->firstOrFail();

        if ($hopDong->file && file_exists(public_path($hopDong->file))) {
            unlink(public_path($hopDong->file));
        }

        $hopDong->delete();
        return redirect()->route('hop_dong_khach_hang.index')->with('success', 'Xóa hợp đồng thành công!');
    }
}
