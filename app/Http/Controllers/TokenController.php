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

    public function accessTokens()
    {
        return view('token.tokens');
    }
}
