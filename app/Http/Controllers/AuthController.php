<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use JWTAuth;
use Auth;
class AuthController extends Controller
{
    public function login(Request $request){
        $token = null;
        $this->validate($request, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6', 
        ]);

        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                     'errors' => [
                       'message' => [
                          'Email or Password Invalid'
                       ]
                     ],
                  ]);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                   'message' => [
                      'Couldnt create token'
                    ]
                 ],
             ]);
        }
        return $this->reply($token);

    }

    protected function reply($token)
    {
        $user = Auth::user();
 
        return response()->json([
          'success' => true,
          'token_code' => $token,
          'user' => $user,
          'token_type' => 'bearer',
        ]);
    }
    public function user(Request $request)
    {
        $user= JWTAuth::parseToken()->toUser()->id;

        return response([
            'status' => 'success',
            'data' => $user
        ]);
    }
    
    public function userInfo(){
        $user= JWTAuth::parseToken()->toUser();
         return response()->json(compact('user'),200);
    }

    public function register(Request $request){

    	$v = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password'  => 'required|min:3|confirmed',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['status' => 'success'], 200);


    }

    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'successs'], 200)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], 401);
    }


    private function guard()
    {
        return Auth::guard();
    }

    public function logout()
    {
        \JWTAuth::invalidate(\JWTAuth::getToken());
        return response()->json([
            'success' => true
        ]);
    }
}
