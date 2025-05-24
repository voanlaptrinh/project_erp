<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_name',
        'user_id',
        'registrar',
        'start_date',
        'expiry_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hostings()
    {
        return $this->hasMany(Hosting::class);
    }
}