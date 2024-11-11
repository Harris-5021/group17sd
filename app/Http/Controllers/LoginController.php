<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller 
{
    // Show login form
    public function index()
    {
        return view('login');
    }

    // Handle login submission
    public function customLogin(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')
                        ->withSuccess('Signed in');
        }
        
        $validator['emailPassword'] = 'Email address or password is incorrect.';
        return redirect("login")->withErrors($validator);
    }

    // Show registration form
    public function registration()
    {
        return view('registration');  // This should point to your registration.blade.php
    }

    // Handle registration submission
    public function customRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'member',  // Default role for new registrations
            'created_at' => now(),
            'updated_at' => now(),
            'last_login' => null
        ]);

        if($user) {
            Auth::login($user);
            return redirect("dashboard")->withSuccess('Registration successful!');
        }

        return redirect()->back()->withErrors(['error' => 'Registration failed. Please try again.']);
    }

    // Show dashboard
    public function dashboard()
    {
        if(Auth::check()){
            return view('dashboard');
        }
        return redirect("login")->withSuccess('You are not allowed to access');
    }

    // Handle logout
    public function signOut() {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
}