<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ThongKeChamCong;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ThongkeChamCongController extends Controller
{
    public function __construct()
    {
        // Kiểm tra quyền của người dùng để tạo, sửa, xóa hợp đồng
        $this->middleware('can:thống kê chấm công')->only(['thongKeChamCong']);
        $this->middleware('can:thống kê chấm công')->only(['thongKeThang']);
       
    }
  // Xem thống kê theo tháng và năm
  public function thongKeChamCong(Request $request)
  {
    $thang = $request->thang ?? now()->month;  // Mặc định là tháng hiện tại
    $nam = $request->nam ?? now()->year;  // Mặc định là năm hiện tại

    // Lấy tất cả thống kê chấm công từ bảng thống kê
    $thongKeChamCong = ThongKeChamCong::where('thang', str_pad($thang, 2, '0', STR_PAD_LEFT))
                                      ->where('nam', $nam)
                                      ->get();

      return view('admin.chamcong.thongke', compact('thang', 'nam', 'thongKeChamCong'));
  }

  // Tạo thống kê mới cho tháng và năm đã chọn
 // ThongkeChamCongController.php

public function thongKeThang(Request $request)
{
    $thang = $request->thang; // Tháng được chọn từ form
    $nam = $request->nam; // Năm được chọn từ form

    // Lấy tất cả chấm công trong tháng và năm đã chọn
    $attendances = Attendance::where('thang', $thang)
                            ->where('nam', $nam)
                            ->get();

    // Lặp qua từng nhân viên để tính toán thống kê
    foreach ($attendances->groupBy('user_id') as $userId => $attendanceGroup) {
        $user = $attendanceGroup->first()->user;  // Lấy thông tin nhân viên

        $ngayDiMuon = $attendanceGroup->where('di_muon', true)->count();
        $ngayVeSom = $attendanceGroup->where('ve_som', true)->count();
        $tongCong = $attendanceGroup->count();
        $ngayTrongThang = Carbon::createFromDate($nam, $thang, 1)->daysInMonth;
        $ngayNghi = $ngayTrongThang - $tongCong;
        $ngayDu = $attendanceGroup->where('di_muon', false)->where('ve_som', false)->count();

        // Lưu vào bảng thống kê
        ThongKeChamCong::updateOrCreate(
            [
                'user_id' => $userId,
                'thang' => str_pad($thang, 2, '0', STR_PAD_LEFT),
                'nam' => $nam
            ],
            [
                'ngay_di_muon' => $ngayDiMuon,
                'ngay_ve_som' => $ngayVeSom,
                'ngay_nghi' => $ngayNghi,
                'ngay_du' => $ngayDu,
                'tong_cong' => $tongCong,
            ]
        );
    }

    return redirect()->route('admin.chamcong.thongke', ['thang' => $thang, 'nam' => $nam])->with('success', 'Thống kê đã được tạo!');
}

}
