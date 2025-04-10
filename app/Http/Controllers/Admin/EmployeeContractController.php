<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeContract;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        'file_hop_dong' => 'nullable|file|mimes:pdf',
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
    
    

    $data = $request->only([
        'user_id', 'loai_hop_dong', 'ngay_bat_dau', 'ngay_ket_thuc', 'luong_thoa_thuan',
    ]);

    $data['alias'] = Str::slug($request->loai_hop_dong . '-' . uniqid());

    if ($request->hasFile('file_hop_dong')) {
        $file = $request->file('file_hop_dong');

        // Làm sạch tên file
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $cleanName = Str::slug($originalName); // loại bỏ dấu, khoảng trắng, ký tự lạ
        $filename = time() . '-' . $cleanName . '.' . $extension;

        // Lưu vào thư mục public/contracts
        $file->move(public_path('contracts'), $filename);
        $data['file_hop_dong'] = 'contracts/' . $filename;
    }

    EmployeeContract::create($data);

    return redirect()->route('admin.employee-contracts.index')->with('success', 'Tạo hợp đồng thành công!');
}

    public function show(EmployeeContract $contract)
    {
        if (!Auth::user()->hasRole('Super Admin') && $contract->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập hợp đồng này.');
        }

        return view('admin.employee_contracts.show', compact('contract'));
    }

    public function edit(EmployeeContract $contract)
    {
        if (!Auth::user()->hasRole('Super Admin') && $contract->user_id !== Auth::id()) {
            abort(403);
        }

        $users = User::all();
        return view('admin.employee_contracts.edit', compact('contract', 'users'));
    }

    public function update(Request $request, EmployeeContract $contract)
    {
        if (!Auth::user()->hasRole('Super Admin') && $contract->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'loai_hop_dong' => 'required|string|max:255',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'nullable|date|after_or_equal:ngay_bat_dau',
            'luong_thoa_thuan' => 'required|numeric|min:0',
            'file_hop_dong' => 'nullable|file|mimes:pdf',
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
        

        $contract->loai_hop_dong = $request->loai_hop_dong;
        $contract->ngay_bat_dau = $request->ngay_bat_dau;
        $contract->ngay_ket_thuc = $request->ngay_ket_thuc;
        $contract->luong_thoa_thuan = $request->luong_thoa_thuan;

        if ($request->hasFile('file_hop_dong')) {
            if ($contract->file_hop_dong && file_exists(public_path($contract->file_hop_dong))) {
                unlink(public_path($contract->file_hop_dong));
            }
            $file = $request->file('file_hop_dong');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->move(public_path('contracts'), $filename);
            $contract->file_hop_dong = 'contracts/' . $filename;
        }

        $contract->save();

        return redirect()->route('admin.employee-contracts.index')->with('success', 'Cập nhật hợp đồng thành công!');
    }

    public function destroy(EmployeeContract $contract)
    {
        if (!Auth::user()->hasRole('Super Admin') && $contract->user_id !== Auth::id()) {
            abort(403);
        }

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
