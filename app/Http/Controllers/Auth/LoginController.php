<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // Add this import

class LoginController extends Controller
{
    use AuthenticatesUsers; // Move this to the top

    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $credentials['status'] = 1;
        return $credentials;
    }

    protected function authenticated(Request $request, $user)
    {
        switch($user->role) {
            case 1: // Admin
                return redirect()->route('home');
            case 2: // Lecturer
                return redirect()->route('lecturer.index');
            case 3: // Student
                return redirect()->route('student.index');
            default:
                return redirect('/');
        }
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}