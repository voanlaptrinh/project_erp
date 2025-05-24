<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThietBiLamViec extends Model
{
    use HasFactory;

    protected $table = 'thiet_bi_lam_viec';

    protected $fillable = [
        'user_id',
        'loai_thiet_bi',
        'ten_thiet_bi',
        'he_dieu_hanh',
        'cau_hinh',
        'so_serial',
        'ngay_ban_giao',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_ban_giao' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}