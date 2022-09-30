<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @param $request
     * @return User|Model
     */
    public function registerNewUser($request): User|Model
    {
        return User::query()->create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email
        ]);
    }
}
