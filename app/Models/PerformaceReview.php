<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformaceReview extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'thang', 'nam', 'ngay_cong', 'so_task_hoan_thanh', 'di_muon', 've_som', 'hieu_suat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
