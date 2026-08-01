<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::withoutCompanyScope()->where('email', $email)->first();
    }

    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }

    public function recordLogin(User $user, string $ip, ?string $userAgent, bool $success = true): void
    {
        $user->loginHistories()->create([
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'success' => $success,
            'logged_in_at' => now(),
        ]);

        if ($success) {
            $user->forceFill([
                'last_login_at' => now(),
                'last_login_ip' => $ip,
            ])->save();
        }
    }
}
