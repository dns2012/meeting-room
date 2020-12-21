<?php

namespace App\Repositories;

use App\Http\Requests\Api\User\AuthRegisterRequest;
use App\Services\ImageService;
use App\Services\ResponseService;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * UserRepository store user method.
     *
     * @param  AuthRegisterRequest $request
     * @param  boolean $isJson
     * @return object
     */
    public function store(AuthRegisterRequest $request, $isJson = false)
    {
        $user = new User();
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->photo = ImageService::upload($request->file('photo'));
        if ($user->save()) {
            return ($isJson) ? ResponseService::success($user) : $user;
        }
        return ResponseService::failure('Failed to create user.');
    }
}
