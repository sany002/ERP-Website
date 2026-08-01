<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(private UserRepository $users)
    {
    }

    /**
     * @throws ValidationException
     */
    public function login(string $email, string $password, Request $request): array
    {
        $user = $this->users->findByEmail($email);

        if (!$user || !Auth::guard('web')->validate(['email' => $email, 'password' => $password])) {
            if ($user) {
                $this->users->recordLogin($user, $request->ip(), $request->userAgent(), success: false);
            }

            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['This account has been deactivated. Contact your administrator.'],
            ]);
        }

        $token = auth('api')->login($user);

        $this->users->recordLogin($user, $request->ip(), $request->userAgent());

        AuditLog::create([
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'action' => 'login',
            'ip_address' => $request->ip(),
            'url' => $request->fullUrl(),
        ]);

        return $this->respondWithToken($token, $user);
    }

    public function logout(): void
    {
        auth('api')->logout();
    }

    public function refresh(): array
    {
        $token = auth('api')->refresh();

        return $this->respondWithToken($token, auth('api')->user());
    }

    private function respondWithToken(string $token, $user): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user->load(['company', 'branch', 'roles.permissions']),
        ];
    }
}
