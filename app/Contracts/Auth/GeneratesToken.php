<?php

namespace App\Contracts\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

interface GeneratesToken {
    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @return string|null
     */
    function createToken(?Authenticatable $user): ?string;
}
