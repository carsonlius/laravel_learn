<?php

namespace App\Http\Controllers;

class TokenController extends Controller
{
    public function clients()
    {
        return view('token.clients');
    }

    public function authorized()
    {
        return view('token.authorized');
    }

    // token列表
    public function accessTokens()
    {
        return view('token.tokens');
    }
}
