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
        $users = DB::table('users')->get();
        return view('/admin/home', compact('users'));
    }

    public function status(Request $request, $id)
    {
        $data = User::find($id);
        if($data->status == 0)
        {
            $data->status = 1;
        }
        else
        {
            $data->status = 0;
        }
        $data->save();
        return Redirect()->route('home')->with('success', 'Status updated successfully');
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect()->route('home')->with('success', 'User deleted successfully');
        }
        return redirect()->route('home')->with('error', 'User not found');
    }
}
