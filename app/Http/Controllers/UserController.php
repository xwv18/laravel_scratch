<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //create register form
    public function create(){
        return view('users.register');
    }

    // store new users
    public function userData(Request $request){
        $this->dataValidationCheck($request);
        $formFields = $this->getdata($request);
        // hash password
        $formFields['password'] = bcrypt($formFields['password']);
        // user create
        $user = User::create($formFields);

        // login
        auth()->login($user);
        return redirect('/')->with('message','User Registation is successful !');

    }
    // logout user
    public function logout(Request $request){
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return \redirect('/')->with('message','You have been logged out!');
    }

    // login user
    public function login(){
        return view('users.login');
    }

    // login user auth
    public function userAuth(Request $request){
        $this->authValidationCheck($request);
        $formFields = $this->authUserData($request);
        if(auth()->attempt($formFields)){
            $request->session()->regenerate();
            return \redirect('/')->with('message','You are now logged in!');
        }
        return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
    }

    // private function
    // validation registation
    private function dataValidationCheck($request){
         Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => ['required','email',Rule::unique('users','email')],
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
    ])->validate();
    }
    // data store registation
    private function getdata($request){
        return [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,

        ];
    }

    // validation registation
    private function authValidationCheck($request){
         Validator::make($request->all(),[
            'email' => ['required','email'],
            'password' => 'required',
    ])->validate();
    }
    // data store registation
    private function authUserData($request){
        return [
            'email' => $request->email,
            'password' => $request->password,

        ];
    }
}