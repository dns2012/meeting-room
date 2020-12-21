<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\AuthRequest;
use App\Http\Requests\Api\User\AuthRegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authService;

    public function __construct()
    {
        $this->authService = new AuthService;
    }

    /**
     * AuthController user register method.
     *
     * @param  mixed $request
     * @return void
     */
    public function register(AuthRegisterRequest $request)
    {
        $request->validated();
        return $this->authService->registerUser($request);
    }

    /**
     * AuthController user login method.
     *
     * @param  AuthRequest $request
     * @return object
     */
    public function login(AuthRequest $request)
    {
        $request->validated();
        return $this->authService->authenticate([
            'email'     => $request->input('email'),
            'password'  => $request->input('password')
        ]);
    }
}
