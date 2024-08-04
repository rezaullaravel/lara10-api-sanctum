<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserAuthApiController extends Controller
{
    //register user
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|max:255|unique:users',
            'password'  => 'required|string'
          ]);

        if ($validator->fails()) {
        return response()->json($validator->errors());
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password)
        ]);


        return response()->json([
            'success'=>true,
            'message'=>'Registration successfull. Now you can login.',
            'user'=> $user,
        ]);
    }//end method


    //register multiple user with json
    public function multipleRegister(Request $request){
        $data = $request->all();

        //form validation
        $validator = Validator::make($request->all(), [
            'users.*.name'      => 'required|string|max:255',
            'users.*.email'     => 'required|string|max:255|unique:users',
            'users.*.password'  => 'required|string'
          ]);

        if ($validator->fails()) {
        return response()->json($validator->errors());
        }

        foreach($data['users'] as $data){
              $user = new User();
              $user->name = $data['name'];
              $user->email = $data['email'];
              $user->password = Hash::make($data['password']);
              $user->save();
        }

        return response()->json([
            'success'=>true,
            'message'=>'Registration successfull. Now the users can login.',
        ]);
    }//end method


    //login user
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email'     => 'required|string|max:255',
            'password'  => 'required|string'
          ]);

        if ($validator->fails()) {
        return response()->json($validator->errors());
        }

        $credentials    =   $request->only('email', 'password');

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Oops! your credentials does not match our records.'
            ], 401);
        }

        $user   = User::where('email', $request->email)->firstOrFail();
        $token  = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'       => 'Login successfull.',
            'access_token'  => $token,
            'token_type'    => 'Bearer'
        ]);
    }//end method


    //logout user
    public function logout(Request $request){
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout successfull',
        ]);
    }//end method
}
