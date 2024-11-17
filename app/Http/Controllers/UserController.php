<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
class UserController extends Controller
{
	public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect()->route('home')->with('success', 'User deleted successfully');
        }
        return redirect()->route('home')->with('error', 'User not found');
    }

	public function editUser($id)
    {
        $user = User::find($id);
        if ($user) {
            return view('admin.user.editUser', compact('user'));
        }
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string', 
            'role' => 'required|string',
        ]);

        $user = User::find($id);
        if ($user) {
            $user->update($request->only(['name',  'email', 'role']));
            return redirect()->route('home')->with('success', 'User detail updated successfully.');
        }
    }
}
