<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(auth()->check()){
            switch(auth()->user()->role){
                case "1":
                    $users = DB::table('users')->get();
                    return view('/admin/home', compact('users'));
                    break;
                case "2":
                    return redirect()->route('lecturer.index');
                    break;
                case "3":
                    return redirect()->route('student.index');
                    break;
            }
        }
    }
}
