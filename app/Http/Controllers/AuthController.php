<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\AuthService;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function show_login()
    {
        return view('auth.login');
    }

    public function login(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $loginResponse = $this->authService->login($req->only(['email', 'password']), $req);

        // dd($loginResponse);
        if($loginResponse['success']) {
            return redirect()->route('admin.index');
        }

        return view('auth.login', $loginResponse);
    }

    public function update_credentials(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        return $this->authService->update_credentials($req->only(['email', 'password']));
    }

    public function logout()
    {
        $logoutResponse = $this->authService->logout();
        return view('auth.logout', $logoutResponse);
    }
}
