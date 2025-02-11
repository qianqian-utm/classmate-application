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
    public function index(Request $request)
    {
        if(auth()->check()){
            switch(auth()->user()->role){
                case "1":
                    $query = User::query();

                    // Filter by role
                    if ($request->filled('role')) {
                        $query->where('role', $request->role);
                    }

                    // Search by name
                    if ($request->filled('search')) {
                        $query->where('name', 'like', '%' . $request->search . '%');
                    }

                    // Paginate results
                    $users = $query ->orderBy('role', 'asc')
                        ->orderBy('name', 'asc')
                        ->paginate(10);

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
