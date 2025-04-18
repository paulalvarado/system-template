<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('login');
    }
    public function loginPage(): string
    {
        return view('login', [
            'title' => 'Login'
        ]);
    }
    public function registerPage(): string
    {
        return view('register', [
            'title' => 'Register'
        ]);
    }
}
