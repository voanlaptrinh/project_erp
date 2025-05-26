<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MessageGroup extends Model
{
    protected $fillable = ['name', 'is_group'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'message_group_users');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
{
    return $this->hasOne(Message::class)->latestOfMany();
}
}