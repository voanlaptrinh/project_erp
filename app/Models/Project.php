<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_du_an', 'mo_ta', 'trang_thai', 'ngay_bat_dau', 'ngay_ket_thuc'
    ];

    public function sprints()
    {
        return $this->hasMany(Sprint::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
