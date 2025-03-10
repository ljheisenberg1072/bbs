<?php

namespace Tests\Traits;

use App\Models\User;

trait ActingJWTUser
{
    public function JWTActingAs(User $user)
    {
        $token = auth('api')->fromUser($user);

        $this->withHeader('Authorization', 'Bearer '.$token);

        return $this;
    }
}
