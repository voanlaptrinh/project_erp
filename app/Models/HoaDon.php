<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    use HasFactory;
    protected $fillable = [
        'hop_dong_id', 'so_hoa_don', 'ngay_hoa_don', 'so_tien', 'trang_thai', 'ghi_chu'
    ];

    public function hopDong()
    {
        return $this->belongsTo(HopDong::class);
    }

    public function thanhToans()
    {
        return $this->hasMany(ThanhToan::class);
    }
}
