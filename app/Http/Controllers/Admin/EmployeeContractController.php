<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeContract;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class EmployeeContractController extends Controller
{
    public function __construct()
    {
        // Kiểm tra quyền của người dùng để tạo, sửa, xóa hợp đồng
        $this->middleware('can:tạo hợp đồng')->only(['create', 'store']);
        $this->middleware('can:sửa hợp đồng')->only(['edit', 'update']);
        $this->middleware('can:xóa hợp đồng')->only(['destroy']);
        $this->middleware('can:xem hợp đồng')->only(['index', 'show']);
    }
    public function index()
    {
        if (Auth::user()->hasRole('Super Admin')) {
            $contracts = EmployeeContract::with('user')->latest()->paginate(10);
        } else {
            $contracts = EmployeeContract::with('user')
                ->where('user_id', Auth::id())
                ->latest()
                ->paginate(10);
        }

        return view('admin.employee_contracts.index', compact('contracts'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.employee_contracts.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'loai_hop_dong' => 'required|string|max:255',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'nullable|date|after_or_equal:ngay_bat_dau',
            'luong_thoa_thuan' => 'required|numeric|min:0',
            'file_hop_dong' => 'nullable|file|mimes:pdf,doc,docx',
        ], [
            'user_id.required' => 'Vui lòng chọn nhân viên.',
            'user_id.exists' => 'Nhân viên không tồn tại trong hệ thống.',
            'loai_hop_dong.required' => 'Vui lòng nhập loại hợp đồng.',
            'loai_hop_dong.string' => 'Loại hợp đồng không hợp lệ.',
            'loai_hop_dong.max' => 'Loại hợp đồng không được vượt quá 255 ký tự.',
            'ngay_bat_dau.required' => 'Vui lòng chọn ngày bắt đầu.',
            'ngay_bat_dau.date' => 'Ngày bắt đầu không hợp lệ.',
            'ngay_ket_thuc.date' => 'Ngày kết thúc không hợp lệ.',
            'ngay_ket_thuc.after_or_equal' => 'Ngày kết thúc phải bằng hoặc sau ngày bắt đầu.',
            'luong_thoa_thuan.required' => 'Vui lòng nhập lương thỏa thuận.',
            'luong_thoa_thuan.numeric' => 'Lương thỏa thuận phải là số.',
            'luong_thoa_thuan.min' => 'Lương thỏa thuận không được nhỏ hơn 0.',
            'file_hop_dong.file' => 'Tệp hợp đồng phải là một tệp.',
            'file_hop_dong.mimes' => 'Tệp hợp đồng chỉ được phép là tệp PDF, DOC, DOCX.',
        ]);



        $data = $request->only([
            'user_id',
            'loai_hop_dong',
            'ngay_bat_dau',
            'ngay_ket_thuc',
            'luong_thoa_thuan',
        ]);

        $data['alias'] = Str::slug($request->loai_hop_dong . '-' . uniqid());

        $filePath = null;

        if ($request->hasFile('file_hop_dong')) {
            $file = $request->file('file_hop_dong');
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
        EmployeeContract::create([
            'user_id' => $request->user_id,
            'loai_hop_dong' => $request->loai_hop_dong,
            'ngay_bat_dau' => $request->ngay_bat_dau,
            'ngay_ket_thuc' => $request->ngay_ket_thuc,
            'luong_thoa_thuan' => $request->luong_thoa_thuan,
            'file_hop_dong' => $filePath,
            'alias' => $data['alias'],
        ]);

        return redirect()->route('admin.employee-contracts.index')->with('success', 'Tạo hợp đồng thành công!');
    }

    public function show(EmployeeContract $contract)
    {

        return view('admin.employee_contracts.show', compact('contract'));
    }

    public function edit(EmployeeContract $contract)
    {
      

        $users = User::all();
        return view('admin.employee_contracts.edit', compact('contract', 'users'));
    }

    public function update(Request $request, EmployeeContract $contract)
    {
       

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'loai_hop_dong' => 'required|string|max:255',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'nullable|date|after_or_equal:ngay_bat_dau',
            'luong_thoa_thuan' => 'required|numeric|min:0',
            'file_hop_dong' => 'nullable|file|mimes:pdf,doc,docx',
        ], [
            'user_id.required' => 'Vui lòng chọn nhân viên.',
            'user_id.exists' => 'Nhân viên không tồn tại trong hệ thống.',
            'loai_hop_dong.required' => 'Vui lòng nhập loại hợp đồng.',
            'loai_hop_dong.string' => 'Loại hợp đồng không hợp lệ.',
            'loai_hop_dong.max' => 'Loại hợp đồng không được vượt quá 255 ký tự.',
            'ngay_bat_dau.required' => 'Vui lòng chọn ngày bắt đầu.',
            'ngay_bat_dau.date' => 'Ngày bắt đầu không hợp lệ.',
            'ngay_ket_thuc.date' => 'Ngày kết thúc không hợp lệ.',
            'ngay_ket_thuc.after_or_equal' => 'Ngày kết thúc phải bằng hoặc sau ngày bắt đầu.',
            'luong_thoa_thuan.required' => 'Vui lòng nhập lương thỏa thuận.',
            'luong_thoa_thuan.numeric' => 'Lương thỏa thuận phải là số.',
            'luong_thoa_thuan.min' => 'Lương thỏa thuận không được nhỏ hơn 0.',
            'file_hop_dong.file' => 'Tệp hợp đồng phải là một tệp.',
            'file_hop_dong.mimes' => 'Tệp hợp đồng chỉ được phép là tệp PDF.',
        ]);
        if ($request->hasFile('file_hop_dong')) {
            // Xóa file cũ nếu có
            if ($contract->file_hop_dong && file_exists(public_path($contract->file_hop_dong))) {
                unlink(public_path($contract->file_hop_dong));
            }
    
            $file = $request->file('file_hop_dong');
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

                Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
                Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));
                
    
                $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');
                $pdfWriter->save($pdfPath);
    
                // Xoá file Word sau khi convert
                unlink($wordPath);
    
                $contract->file_hop_dong = 'hopdongs/' . $filename . '.pdf';
            } else {
                // Nếu là PDF thì lưu như thường
                $file->move($storagePath, $filename . '.pdf');
                $contract->file_hop_dong = 'hopdongs/' . $filename . '.pdf';
            }
        }






        $contract->loai_hop_dong = $request->loai_hop_dong;
        $contract->ngay_bat_dau = $request->ngay_bat_dau;
        $contract->ngay_ket_thuc = $request->ngay_ket_thuc;
        $contract->luong_thoa_thuan = $request->luong_thoa_thuan;

        // if ($request->hasFile('file_hop_dong')) {
        //     if ($contract->file_hop_dong && file_exists(public_path($contract->file_hop_dong))) {
        //         unlink(public_path($contract->file_hop_dong));
        //     }
        //     $file = $request->file('file_hop_dong');
        //     $filename = time() . '-' . $file->getClientOriginalName();
        //     $file->move(public_path('contracts'), $filename);
        //     $contract->file_hop_dong = 'contracts/' . $filename;
        // }

        $contract->save();

        return redirect()->route('admin.employee-contracts.index')->with('success', 'Cập nhật hợp đồng thành công!');
    }

    public function destroy(EmployeeContract $contract)
    {
       

        if ($contract->file_hop_dong && file_exists(public_path($contract->file_hop_dong))) {
            unlink(public_path($contract->file_hop_dong));
        }

        $contract->delete();

        return redirect()->route('admin.employee-contracts.index')->with('success', 'Xoá hợp đồng thành công!');
    }
    public function view(EmployeeContract $contract)
    {
        return view('admin.employee_contracts.viewer', compact('contract'));
    }
}
