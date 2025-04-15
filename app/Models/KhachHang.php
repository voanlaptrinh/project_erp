<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
class KhachHang extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id', 'ten', 'email', 'so_dien_thoai', 'dia_chi', 'ghi_chu'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function pipelines()
    {
        return $this->hasMany(Pipeline::class);
    }

    public function hopDongs()
    {
        return $this->hasMany(HopDong::class);
    }

    public function tickets()
    {
        return $this->hasMany(TicketSuport::class);
    }
  
}
