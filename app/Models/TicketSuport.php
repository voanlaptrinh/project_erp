<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSuport extends Model
{
    use HasFactory;
    protected $fillable = [
        'khach_hang_id',
        'tieu_de',
        'noi_dung',
        'trang_thai',
        'do_uu_tien',
        'nguoi_xu_ly_id',
    ];

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class);
    }
    public function nguoiXuLy()
    {
        return $this->belongsTo(User::class, 'nguoi_xu_ly_id');
    }
}
