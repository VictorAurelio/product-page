<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\User\UserController;

class LoginUserController extends UserController
{
    public function __construct(UserController $userController)
    {
        parent::__construct();
    }
    public function login(array $data): array
    {
        $data = $this->sanitizer->clean($data);

        $this->validator->validate($data, [
            'email' => ['required'],
            'password' => ['required']
        ]);

        $userId = $this->userModel->checkCredentials($data['email'], $data['password']);
        if (!$userId) {
            return ['message' => 'Invalid email or password', 'status' => 401];
        }
        var_dump($userId);
        $jwt = $this->userModel->createJwt($userId);
        return [
            'message' => 'User logged in successfully',
            'userId' => $userId,
            'jwt' => $jwt,
            'status' => 200
        ];
        // return ['message' => 'User logged in successfully', 'jwt' => $jwt, 'status' => 200];
    }
}
