<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaoGia extends Model
{
    use HasFactory;
    protected $fillable = [
        'hop_dong_id', 'ten_bao_gia', 'ngay_bao_gia', 'tong_tien', 'trang_thai', 'ghi_chu'
    ];

    public function hopDong()
    {
        return $this->belongsTo(HopDong::class);
    }
}
