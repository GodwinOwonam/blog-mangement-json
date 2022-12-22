<?php

namespace App\Http\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthRepository {
    private $authData;

    public function __construct()
    {
        $this->authData =
        json_decode(file_get_contents(storage_path() . "/data/auth_data.json"), true);
    }

    public function login($credentials)
    {
        if(
            $credentials['email'] == $this->authData['email'] &&
            Hash::check($credentials['password'], $this->authData['password'])
        ) {
            session(['is_admin' => true]);
            return true;
        }

        return false;
    }

    public function update_auth_credentials($credentials)
    {
        try {

            $credentials_path = storage_path() . '/data/auth_data.json';

            $inputData = [
                'email' => $credentials['email'],
                'password' => Hash::make($credentials['password'])
            ];

            if($this->authData['update_count'] < 1)
            {
                file_put_contents($credentials_path, json_encode(array_merge($inputData, ["update_count" => $this->authData['update_count'] + 1])));
            }
            return true;
        } catch (\Exception $e) {

            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public function logout()
    {
        if (
            !(
                request()->session()->get('is_admin') &&
                request()->session()->get('is_admin') === true
            )
        ) {
            return false;
        }
        session(['is_admin' => false]);
        return true;
    }
}
