<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getCurrentUser()
    {
        $user = User::find(Auth::user()->id);

        return response()->json($user);
    }

}
