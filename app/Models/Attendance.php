<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'ngay',
        'thang',
        'nam',
        'gio_vao',
        'gio_ra',
        'di_muon',
        've_som',
    ];

    protected $casts = [
        'ngay' => 'date',
        'gio_vao' => 'datetime:H:i',
        'gio_ra' => 'datetime:H:i',
        'di_muon' => 'boolean',
        've_som' => 'boolean',
    ];

    public function nhanVien()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setGioVaoAttribute($value)
    {
        $gioVao = Carbon::parse($value, 'Asia/Ho_Chi_Minh'); 
        $this->attributes['gio_vao'] = $gioVao;
    
        $diMuon = $gioVao->gt(Carbon::createFromTime(8, 30, 0, 'Asia/Ho_Chi_Minh'));
        $this->attributes['di_muon'] = $diMuon;
    }
    
    public function setGioRaAttribute($value)
    {
        $gioRa = Carbon::parse($value, 'Asia/Ho_Chi_Minh');
        $this->attributes['gio_ra'] = $gioRa;
    
        $veSom = $gioRa->lt(Carbon::createFromTime(17, 30, 0, 'Asia/Ho_Chi_Minh'));
        $this->attributes['ve_som'] = $veSom;
    }

    // Optional: format ngày
    public function getNgayFormatAttribute()
    {
        return Carbon::parse($this->ngay)->format('d/m/Y');
    }
}
