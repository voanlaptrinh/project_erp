<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeContract extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'loai_hop_dong',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'luong_thoa_thuan',
        'file_hop_dong',
        'alias',
    ];

    /**
     * Liên kết tới người dùng (nhân viên) sở hữu hợp đồng
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getRouteKeyName()
    {
        return 'alias';
    }
}
