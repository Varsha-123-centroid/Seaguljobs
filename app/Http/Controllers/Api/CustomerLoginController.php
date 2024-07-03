<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;

class CustomerLoginController extends Controller
{
    public function customer_registration(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|unique:customers,email',
                'password' => 'required',
                
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message_en' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $user = Customer::create([
                'customer_name' => $request->name,
                'email' => $request->email,
                 'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message_en' => 'User Registered Successfully',
                // 'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function customer_login(Request $request)
    {
        
       // $rest_id=$request->rest_id;
 
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
                 $input=['email' => $request->email, 'password' => $request->password];
                 
            if(auth()->guard('customer')->attempt($input,true)){
                
                 $user = Customer::where('email', $request->email)->first();
                return response()->json([
                'status' => true,
                'message_en' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'token_type' => 'Bearer'
            ], 200);
            }

        else{
             return response()->json([
                    'status' => false,
                    'message_en' => 'Email & Password does not match with our record.',
                ], 200);
        }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
