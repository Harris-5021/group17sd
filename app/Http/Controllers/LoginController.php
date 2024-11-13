<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
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
            'email' => 'required|email',
            'password' => 'required|min:6',  // Add password validation rules
        ]);

        $credentials = $request->only('email', 'password');

        // Check if credentials are valid
        if (Auth::attempt($credentials)) {
            // After successful login, check the user's role and redirect accordingly
            $user = Auth::user();
            
            // Redirect based on the user's role
            switch ($user->role) {
                case 'accountant':
                    return redirect()->route('accountant.dashboard')->withSuccess('Welcome, Accountant!');
                case 'purchase_manager':
                    return redirect()->route('purchase_manager.dashboard')->withSuccess('Welcome, Purchase Manager!');
                case 'branch_manager':
                    return redirect()->route('branch_manager.dashboard')->withSuccess('Welcome, Branch Manager!');
                case 'librarian':
                    return redirect()->route('librarian.dashboard')->withSuccess('Welcome, Librarian!');
                case 'member':
                    return redirect()->route('dashboard.member')->withSuccess('Welcome, Member!');
                default:
                    // Redirect to a default dashboard if role does not match any above
                    return redirect()->route('user.dashboard')->withSuccess('Welcome!');
            }
        }

        // If login fails, add error message
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