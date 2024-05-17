<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Validator;
use DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
    	$request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $remember_me = ($request->remember_me) ? true : false;
        $email = $request->email;
        $userObj = User::where('email',$email)->exists();
        if($userObj) {
        	$credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials, $remember_me)) {
            	$device_name = ($request->device_name) ? $request->device_name : config("app.name");
            	return response()->json(['status'=>1,'msg'=>'User logged in successfully!','data'=>Auth::user()->createToken($device_name)->plainTextToken]);
            }else{
                // throw ValidationException::withMessages(['The credentials are incorrect']);
                return response()->json(['status'=>0,'msg'=>'Invalid credentials','data'=>null]);
            }
        } else {
        	return response()->json(['status'=>0,'msg'=>'User not found!','data'=>null]);
        }
    }

    public function register(Request $request)
    {
    	$valid = Validator::make($request->all(), [
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required|confirmed|digits:4'
        ]);

        if ($valid->fails()) {
            if( $request->is('api/*')){
                return response()->json(['status'=>0,'msg'=>'Something went wrong!','data'=>null]);
            } else {
                return response()->json(['status'=>0,'msg'=>'Something went wrong!','data'=>null]);
            }
        } else {
        	$user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json(['status'=>1,'msg'=>'User registered!','data'=>null]);
        }
    }

    public function logout(Request $request)
    {
    	Auth::user()->currentAccessToken()->delete();
    	return response()->json(['status'=>1,'msg'=>'User logged out successfully!','data'=>null]);
    }
}
