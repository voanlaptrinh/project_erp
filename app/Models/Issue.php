<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'tieu_de', 'mo_ta', 'muc_do', 'trang_thai', 'nguoi_bao_cao'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'nguoi_bao_cao');
    }
}
