<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pipeline extends Model
{
    use HasFactory;
    protected $fillable = [
        'khach_hang_id', 'tieu_de', 'mo_ta', 'gia_tri_uoc_tinh', 'giai_doan', 'xac_suat_thanh_cong'
    ];

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class);
    }
}
