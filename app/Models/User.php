<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'google_id',
        'ngay_vao_lam',
        'so_dien_thoai',
        'ngay_sinh',
        'gioi_tinh',
        'vi_tri',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user');
    }
    public function notifications()
    {
        return $this->hasMany(notification::class);
    }

    public function thietBiLamViec()
    {
        return $this->hasMany(ThietBiLamViec::class);
    }

    public function messageGroups()
    {
        return $this->belongsToMany(MessageGroup::class, 'message_group_users');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class);
    }

    public function messageReads()
    {
        return $this->hasMany(MessageRead::class);
    }

    public function thongBaoChats()
    {
        return $this->hasMany(\App\Models\ThongBaoChat::class, 'user_id');
    }
}
