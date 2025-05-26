<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\MessageGroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessageGroupPolicy
{
    use HandlesAuthorization;

    public function view(User $user, MessageGroup $group)
    {
        return $group->users->contains($user);
    }

    public function update(User $user, MessageGroup $group)
    {
        return $group->users->contains($user) && $group->is_group;
    }
}

class MessagePolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Message $message)
    {
        return $message->user_id === $user->id;
    }
}