<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Mockup;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class MockupPolicy
{
    use HandlesAuthorization;

    public function generate(User $user, Mockup $mockup): Response
    {
        return $this->allow();
    }
}
