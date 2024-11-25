<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller 
{
   public function index()
   {
       return view('login');
   }

   public function customLogin(Request $request)
   {
       $validator = $request->validate([
           'email' => 'required|email',
           'password' => 'required|min:6',
       ]);

       $credentials = $request->only('email', 'password');

       if (Auth::attempt($credentials)) {
           $user = Auth::user();
           
           switch ($user->role) {
               case 'accountant':
                   return redirect()->route('dashboard.accountant')->withSuccess('Welcome, Accountant!');
               case 'purchase_manager':
                   return redirect()->route('dashboard.purchase_manager')->withSuccess('Welcome, Purchase Manager!');
               case 'branch_manager':
                   return redirect()->route('dashboard.branch_manager')->withSuccess('Welcome, Branch Manager!');
               case 'librarian':
                   return redirect()->route('dashboard.librarian')->withSuccess('Welcome, Librarian!');
               case 'member':
                   return redirect()->route('dashboard.member')->withSuccess('Welcome, Member!');
               default:
                   return redirect()->route('user.dashboard')->withSuccess('Welcome!');
           }
       }

       $validator['emailPassword'] = 'Email address or password is incorrect.';
       return redirect("login")->withErrors($validator);
   }

   public function registration()
   {
       $branches = Branch::orderBy('name')->get();
       return view('registration', compact('branches'));
   }

   public function customRegistration(Request $request)
   {
       $request->validate([
           'name' => 'required',
           'email' => 'required|email|unique:users',
           'password' => 'required|min:6',
           'branch_id' => 'required|exists:branches,id'
       ]);

       $user = User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => Hash::make($request->password),
           'role' => 'member',
           'branch_id' => $request->branch_id,
           'created_at' => now(),
           'updated_at' => now(),
           'last_login' => null
       ]);

       if($user) {
           Auth::login($user);
           return redirect("login")->withSuccess('Registration successful!');
       }

       return redirect()->back()->withErrors(['error' => 'Registration failed. Please try again.']);
   }

   public function dashboard()
   {
       if(Auth::check()){
           return view('dashboard');
       }
       return redirect("login")->withSuccess('You are not allowed to access');
   }

   public function signOut() {
       Session::flush();
       Auth::logout();
       return Redirect('login');
   }

   public function getBranches(Request $request)
   {
       $search = $request->search;
       
       $branches = Branch::where('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orderBy('name')
                        ->get();
                        
       return response()->json($branches);
   }
}