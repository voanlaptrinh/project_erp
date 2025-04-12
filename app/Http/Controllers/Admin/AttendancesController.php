<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Log;
use App\Models\notification;
use App\Models\ThongKeChamCong;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendancesController extends Controller
{
    public function __construct()
    {
        // Kiểm tra quyền của người dùng để tạo, sửa, xóa hợp đồng
        $this->middleware('can:xem toàn bộ chấm công')->only(['index']);
        $this->middleware('can:xem chấm công')->only(['index']);
        $this->middleware('can:xem chấm công')->only(['chamCongRa', 'chamCongVao']);
    }

    public function index(Request $request)
    {
        $query = Attendance::with('nhanVien')->orderBy('ngay', 'desc');

        // Nếu không có quyền xem toàn bộ -> chỉ lọc của chính mình
        if (!Auth::user()->can('xem toàn bộ chấm công')) {
            $query->where('user_id', Auth::id());
        } else {
            // Lọc theo user_id (nếu có)
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        }

        // Lọc theo ngày (nếu có)
        if ($request->filled('ngay')) {
            $query->whereDate('ngay', $request->ngay);
        }

        $chamCongs = $query->paginate(50);
        $users = \App\Models\User::all(); // Để chọn nhân viên trong dropdown

        return view('admin.attendances.index', compact('chamCongs', 'users'));
    }


    public function chamCongVao()
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $today = $now->toDateString();

        $chamCong = Attendance::where('user_id', Auth::id())
            ->where('ngay', $today)
            ->first();

        if ($chamCong && $chamCong->gio_vao) {
            return redirect()->back()->with('error', 'Bạn đã chấm công giờ vào hôm nay rồi.');
        }

        Attendance::updateOrCreate(
            ['user_id' => Auth::id(), 'ngay' => $today],
            [
                'gio_vao' => $now->toTimeString(),
                'thang' => $now->month,
                'nam' => $now->year,
            ]
        );
        notification::create([
            'user_id' => Auth::id(),
            'title' => 'Bạn mới chấm công giờ vào',
            'message' => 'Bạn đã chấm công giờ vào làm lúc ' . $now->toTimeString(),
        ]);
        Log::create([
            'message' => Auth::user()->name . ' đã chấm công vào làm lúc ' . $now->toTimeString()
        ]);
        return redirect()->back()->with('success', 'Đã chấm công giờ vào.');
    }

    public function chamCongRa()
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $today = $now->toDateString();

        $chamCong = Attendance::where('user_id', Auth::id())
            ->where('ngay', $today)
            ->first();

        if (!$chamCong) {
            return redirect()->back()->with('error', 'Bạn chưa chấm công giờ vào.');
        }

        if ($chamCong->gio_ra) {
            return redirect()->back()->with('error', 'Bạn đã chấm công giờ ra hôm nay rồi.');
        }

        $chamCong->update([
            'gio_ra' => $now->toTimeString(),
        ]);
        notification::create([
            'user_id' => Auth::id(),
            'title' => 'Bạn mới chấm công giờ ra',
            'message' => 'Bạn đã chấm công giờ ra lúc ' . $now->toTimeString(),
        ]);
        Log::create([
            'message' => Auth::user()->name . ' đã chấm công ra lúc ' . $now->toTimeString()
        ]);
        return redirect()->back()->with('success', 'Đã chấm công giờ ra.');
    }
   
}
