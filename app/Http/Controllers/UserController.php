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

    public function getDontActiveUsers()
    {
        $users = User::dontActive()->get();

        return response()->json($users);
    }

    public function activateUser($id)
    {
        $user = User::find($id);
        $user->is_active = 1;
        $user->save();

        return response()->json([
            'status' => 200
        ]);
    }

    public function deactivateUser($id)
    {
        $user = User::find($id);
        $user->is_active = 0;
        $user->save();
        
        return response()->json([
            'status' => 200
        ]);
    }
}
