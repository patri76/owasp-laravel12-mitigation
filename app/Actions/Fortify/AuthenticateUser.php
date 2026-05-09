<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticateUser
{
    public function __invoke(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && $user->salt) {

            $pepper = config('app.pepper');

            $passwordWithSaltPepper =
                $credentials['password'] . $user->salt . $pepper;

            if (Hash::check($passwordWithSaltPepper, $user->password)) {

                Auth::login($user);

                Log::info('User logged in', [
                    'email' => $user->email,
                    'ip' => $request->ip(),
                ]);

                return $user;
            }
        }

        Log::warning('Failed login attempt', [
            'email' => $credentials['email'] ?? null,
            'ip' => $request->ip(),
        ]);

        return null;
    }
}
