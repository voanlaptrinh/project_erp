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
            'luong_thoa_thuan' => 'required|string|max:255',
            'file_hop_dong' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $data = $request->only([
            'user_id', 'loai_hop_dong', 'ngay_bat_dau', 'ngay_ket_thuc', 'luong_thoa_thuan',
        ]);

        $data['alias'] = Str::slug($request->loai_hop_dong . '-' . uniqid());

        if ($request->hasFile('file_hop_dong')) {
            $file = $request->file('file_hop_dong');
            $filename = time() . '-' . $file->getClientOriginalName();
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
            'loai_hop_dong' => 'required|string|max:255',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'nullable|date|after_or_equal:ngay_bat_dau',
            'luong_thoa_thuan' => 'required|string|max:255',
            'file_hop_dong' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
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
}
