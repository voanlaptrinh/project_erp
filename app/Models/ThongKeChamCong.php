<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongKeChamCong extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'thang',
        'nam',
        'ngay_di_muon',
        'ngay_ve_som',
        'ngay_nghi',
        'ngay_du',
        'tong_cong',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
