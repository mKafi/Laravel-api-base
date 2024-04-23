<?php
namespace App\Http\Controllers\Api;

use Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Logging user by Api
     * @param Request $request
     * @return $user
     */    
    public function apiLoginUser(Request $request){        
        $validateUser = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required'
        ]);
  
        if($validateUser->fails()){
            return response()->json([
                'status' => FALSE,
                'message' => 'Validation error',
                'errors' => $validateUser->errors() 
            ],401);
        }

        if(!Auth::attempt($request->only(['email','password']))){
            return response()->json([
                'status' => FALSE,
                'message' => 'Email or password is wrong'
            ],401);
        }
       
        $user = User::where('email',$request->email)->first();

        return response()->json([
            'status' => TRUE,
            'message' => 'Logged in successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ],200);
        
    }

    
}
