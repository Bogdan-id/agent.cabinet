<?php
// Admin controller
namespace App\Admin\Controllers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Admin;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $guard = 'admin';

    protected $redirectTo = '/admin';

    protected $loginPath = '/admin/login';

    public function __construct()
    {
        $this->redirectTo = '/admin';
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:admins',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        
        return redirect('/');
    }

    public function login(Request $request)
    {

        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return redirect($this->loginPath)->with('error', 'Admin bulunamadi.');
        }

        if (Hash::check($request->password, $admin->password)) {
            Auth::guard('admin')->login($admin);
            return redirect('/admin');
        }

        return redirect($this->loginPath)
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Incorrect email address or password']);
    }

    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/admin');
        }
        
        return view('admin.login');
    }
}