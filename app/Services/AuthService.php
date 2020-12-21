<?php

namespace App\Services;

use App\Http\Requests\Api\User\AuthRegisterRequest;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class AuthService
{
    /**
     * AuthService user / admin login method.
     *
     * @param  array $data
     * @return object
     */
    public function authenticate($data)
    {
        $guard = (isset($data['guard'])) ? $data['guard'] : 'api';
        $authenticated = Auth::guard($guard)->attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ]);
        if ($authenticated) {
            return ResponseService::success([
                'access_token' => $authenticated,
                'token_type' => 'Bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
        }
        return ResponseService::failure('Unauthenticated user.');
    }

    /**
     * AuthService register user method.
     *
     * @param  AuthRegisterRequest $request
     * @return object
     */
    public function registerUser(AuthRegisterRequest $request)
    {
        $userRepository = new UserRepository;
        $register = $userRepository->store($request);
        if ($register->id) {
            return ResponseService::success([
                'access_token' => Auth::login($register),
                'token_type' => 'Bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
        }
        return ResponseService::failure('Register failed, call us right now.');
    }
}
