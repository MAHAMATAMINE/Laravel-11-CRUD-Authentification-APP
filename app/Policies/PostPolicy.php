<?php

namespace App\Policies;

use App\Models\User;
use App\Models\post;
use Illuminate\Auth\Access\Response;

class PostPolicy
{

    public function modify(User $user, Post $post): Response
    {
        return $user->id ===$post->user_id
        ?Response::allow()
        : Response::deny('You do not Own This post');
    }
}
