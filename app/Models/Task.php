<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'sprint_id', 'tieu_de', 'mo_ta', 'do_uu_tien', 'trang_thai', 'assigned_to', 'han_hoan_thanh'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
