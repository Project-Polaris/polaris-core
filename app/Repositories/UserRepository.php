<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository {
    public function create(string $email, string $password): User {
        return User::create([
            'email' => $email,
            'password' => $password,
        ]);
    }
}
