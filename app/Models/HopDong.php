<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HopDong extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id', 'so_hop_dong', 'ngay_ky', 'ngay_het_han', 'gia_tri', 'trang_thai', 'noi_dung', 'alias','file'
    ];
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
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
