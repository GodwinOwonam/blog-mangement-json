<?php

namespace App\Http\Services;

use App\Http\Repositories\AuthRepository;

class AuthService {
    protected $authRepository;

    public function __construct()
    {
        $this->authRepository = new AuthRepository();
    }

    public function login($credentials, $req)
    {
        $loginResponse = $this->authRepository->login($credentials);
        $req->session()->put('is_admin', $loginResponse);

        return $loginResponse === true ? [
            'success' => true,
            'message' => 'Login successful'
        ] : [
            'success' => false,
            'message' => 'Invalid Credentials'
        ];
    }

    public function update_credentials($credentials)
    {
        return $this->authRepository->update_auth_credentials($credentials);
    }

    public function logout()
    {
        return $this->authRepository->logout() ? [
            'success' => true,
            'message' => 'Logout Successful'
        ] : [
            'success' => false,
            'message' => 'Unauthorized'
        ];
    }

}
