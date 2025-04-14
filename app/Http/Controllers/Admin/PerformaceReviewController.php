<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PerformaceReviewController extends Controller
{
    public function danhGia(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $date = Carbon::parse($month . '-01');
    
        $users = User::all();
        $data = [];
    
        foreach ($users as $user) {
            $attendances = Attendance::where('user_id', $user->id)
                ->whereMonth('ngay', $date->month)
                ->whereYear('ngay', $date->year)
                ->get();
    
            $ngayCong = $attendances->filter(fn($a) => $a->gio_vao && $a->gio_ra)->count();
            $diMuon = $attendances->where('di_muon', true)->count();
            $veSom = $attendances->where('ve_som', true)->count();
    
            $soTask = Task::where('assigned_to', $user->id)
                ->whereMonth('updated_at', $date->month)
                ->whereYear('updated_at', $date->year)
                ->where('trang_thai', 'Hoàn thành')
                ->count();
    
            // Giả định: hiệu suất = task hoàn thành / ngày công * 10 (%)
            $hieuSuat = $ngayCong > 0 ? min(100, round($soTask / $ngayCong * 10, 2)) : 0;
    
            $data[] = [
                'user' => $user,
                'ngay_cong' => $ngayCong,
                'so_task_hoan_thanh' => $soTask,
                'di_muon' => $diMuon,
                've_som' => $veSom,
                'hieu_suat' => $hieuSuat,
            ];
        }
    
        return view('admin.reviews.index', compact('data', 'date'));
    }
    
}
