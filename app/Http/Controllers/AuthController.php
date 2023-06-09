<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Authenticate;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TodoController;
use App\Models\Todo;


class AuthController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('guest')->except([
    //         'logout', 'dashboard'
    //     ]);
    // }



    public function register(){
        return view('auth.register');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success','Registeration done!');
    }



    public function login(){
        return view('auth.login');
    }



    public function authenticate(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (Auth::attempt($credentials)) {
            
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }
        
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    




    public function dashboard(){
        if(Auth::check()){
            return redirect()->route('index');
        }
        else{
            return redirect()->route('login')->with('Please Enter the correct cridentials!');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');;
    }    


    
}
