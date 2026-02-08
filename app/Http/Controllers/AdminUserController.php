<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;


class AdminUserController extends Controller
{
    //
    public function index(){
        $users = User::latest()->get();
        return view('admin.users', compact('users'));   
    }

    public function approve(User $user){
        $user->update([
            'approved' => true,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with("success","User approved succesfully");
    }
}
