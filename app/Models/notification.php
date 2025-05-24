<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notification extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'title', 'message', 'is_read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

     /**
     * Đánh dấu là đã đọc
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
    
    /**
     * Scope cho thông báo chưa đọc
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}