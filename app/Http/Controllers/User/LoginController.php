<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VerificationCode;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller 
{
    
    public function index()
    {
       
        return view('user.login'); 
    }

   
    public function getotp(Request $request)
    {
        $customer = Customer::where('mobile', $request->number)->first();
        // print_r($customer);
        // exit;
        if(empty($customer)){
            $insertcustomer= new Customer;
            $insertcustomer->mobile=$request->input('number');
            $save= $insertcustomer->save();
            $verificationCode = $this->generateOtp($insertcustomer->id);
   Auth::guard('customer')->login($customer);
            if($verificationCode)
            {
              
            return response()->json(['status'=>true,'message' => 'OTP Send successfully','otp' => $verificationCode->otp,'user' => $verificationCode->user_id]);
                      
               }
      
           else
            {
                return response()->json(['status'=>false,'message' => 'Something went wrong']);
             }

        }else{
              Auth::guard('customer')->login($customer);
            $verificationCode = $this->generateOtp($customer->id);
            if($verificationCode)
            {
               
            return response()->json(['status'=>true,'message' => 'OTP Send successfully','otp' => $verificationCode->otp,'user' => $verificationCode->user_id]);
                      
               }
      
           else
            {
                return response()->json(['status'=>false,'message' => 'Something went wrong']);
             }
        }

    }
    public function generateOtp($user)
    {
        
        $verificationCode = VerificationCode::where('user_id', $user)->latest()->first();

        $now = Carbon::now();

        if($verificationCode && $now->isBefore($verificationCode->expire_at)){
            return $verificationCode;
        }

        // Create a New OTP
        
        return VerificationCode::create([
            'user_id' => $user,
            'otp' => rand(123456, 999999),
            'expire_at' => Carbon::now()->addMinutes(10)
        ]);
    }

    public function verifyotp(Request $request)
    {
       
         
    
        #Validation Logic
        $user_id=$request->input('user_id');
        $otp=$request->input('otp');

        $verificationCode   = VerificationCode::where('user_id', $user_id)->where('otp', $otp)->first();

        $now = Carbon::now();
        if (!$verificationCode) {
            return response()->json(['status'=>false,'message' => 'Your OTP is not correct']);
        }elseif($verificationCode && $now->isAfter($verificationCode->expire_at)){
            return response()->json(['status'=>false,'message' => 'Your OTP has been expired']);
        }

        $customer = Customer::whereId($user_id)->first();
        if($customer){
            // Expire The OTP
            $verificationCode->update([
                'expire_at' => Carbon::now()
            ]);

            Auth::guard('customer')->login($customer);

            return response()->json(['status'=>true,'message' => 'Successfully logged in']);
        }

        return response()->json(['status'=>false,'message' => 'Your OTP is not correct']);
    }
    public function customer_logout(Request $request) {
        auth()->guard('customer')->logout();
      return redirect()->route('kovilakam.login');
     }
}
