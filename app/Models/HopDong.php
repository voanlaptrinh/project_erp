<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HopDong extends Model
{
    use HasFactory;
    protected $fillable = [
        'khach_hang_id', 'ten_hop_dong', 'ngay_ky', 'ngay_het_han', 'gia_tri', 'trang_thai', 'ghi_chu'
    ];

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class);
    }

    public function baoGias()
    {
        return $this->hasMany(BaoGia::class);
    }

    public function hoaDons()
    {
        return $this->hasMany(HoaDon::class);
    }

    public function thanhToans()
    {
        return $this->hasMany(ThanhToan::class);
    }
}
