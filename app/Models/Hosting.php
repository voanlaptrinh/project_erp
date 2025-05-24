<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'user_id',
        'domain_id',
        'provider',
        'package',
        'ip_address',
        'start_date',
        'expiry_date',
        'control_panel',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}