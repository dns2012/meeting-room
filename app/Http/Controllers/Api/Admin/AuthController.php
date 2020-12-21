<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\AuthRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    private $authService;

    public function __construct()
    {
        $this->authService = new AuthService;
    }

    /**
     * AuthController login method for admin.
     *
     * @param  AuthRequest $request
     * @return object
     */
    public function login(AuthRequest $request)
    {
        $request->validated();
        return $this->authService->authenticate([
            'email'     => $request->input('email'),
            'password'  => $request->input('password'),
            'guard'     => 'api_admin'
        ]);
    }
}
