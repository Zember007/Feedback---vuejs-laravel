<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function users(Request $request)
    {
        $users = DB::table('users')->where('id', '!=', $request->user()->id)->get();
        if($request->user()->role <= 1) {
            return Inertia::render('User/Users', [
                'users' => $users,
            ]);
        } else {
            return Redirect::route('dashboard');
        }
        
    }
}
