<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponse;
use App\Http\Requests\userStoreRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    use HttpResponse;

    public function login(LoginRequest $request){
         $request->validated($request->all());

         if(!Auth::attempt($request->only(['email','password']))){
            return $this->error('','Credentials not match',401);
         } 

         $user = User::where('email',$request->email)->first();

         return $this->success([
            'user'  => $user,
            'token' => $user->createToken('Api Token of'.$user->name)->plainTextToken
         ]);       
    }

    public function register(userStoreRequest $request){

    	$request->validated($request->all());

    	$user = User::create([
    		'name' => $request->name,
    		'email'=> $request->email,
    		'password' => Hash::make($request->password),
    	]);

    	return $this->success([
    			'user' => $user,
    			'token'=> $user->createToken('Api Token of'.$user->name)->plainTextToken
    	]);

    }
}
