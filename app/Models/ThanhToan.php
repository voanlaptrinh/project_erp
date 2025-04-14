<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThanhToan extends Model
{
    use HasFactory;
    protected $fillable = [
        'hoa_don_id', 'hop_dong_id', 'so_tien', 'ngay_thanh_toan', 'phuong_thuc', 'trang_thai', 'ghi_chu'
    ];

    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class);
    }

    public function hopDong()
    {
        return $this->belongsTo(HopDong::class);
    }
}
