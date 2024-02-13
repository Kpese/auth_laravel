<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function admin()
    {
        return view('admin');
    }

    public function register()
    {
        return view('register');
    }

    public function store(){
        $validate = request()->validate([
            'username' => 'required|min:4|max:20',
            'password' => 'required|min:5|max:20',
            'email' => 'email|unique:users,email',
        ]);

        User::create([
            'name' => $validate['username'],
            'email' => $validate['email'],
            'password' => Hash::make($validate['password']),
        ]);

        return redirect()->route('home')->with('success', "welcome, you have successfully registered");
    }

    public function login(){
        return view('login');
    }


    public function validates()
    {
        $validate = request()->validate([
            'password' => 'required|min:2|max:20',
            'email' => 'email',
        ]);


        if(Auth::attempt($validate)){
            request()->session()->regenerate();

            $usertype = Auth::user()->usertype;
            if($usertype == 'admin'){
                return redirect()->route('admin')->with('success', "welcome, you have successfully logged in");

            } else if ($usertype == 'user') {
                return redirect()->route('home')->with('success', "welcome, you have successfully logged in");
            }
        } else{
            return redirect()->route('login')->withErrors([
            'email' => 'No matching user found with the provided email and password']);
        }

  }


  public function logout(){
        Auth::logout();

        request()->session()->regenerate();
        request()->session()->regenerateToken();

        return redirect()->route('login');

  }
}