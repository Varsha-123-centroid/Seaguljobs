<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Auth;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      
        return view('home');
    }
    public function change_password()
    {
        return view('changepassword');
    }
    public function update_password(Request $request)
    {
        $validated = $request->validate([
            
            'password1' => 'required',
            'password2' => 'required',
            
            ],
            [
            'password1.required' => 'This field is required',
            'password2.required' => 'This field is required',
        
            ]
            
        );
        if($request->input('password1')!=$request->input('password2')){
           return redirect()->back()->with('Fail','Password mismatch'); 
        }
         $id = Auth::user()->id;
        $updatepassword= User::find($id);
        $updatepassword->password=Hash::make($request->input('password1'));
        $update= $updatepassword->save();
        if($update)
        {
        return redirect(route('change_password'))->with('status','Detials Updated Successfully !');
                    
            }

        else
        {
        return redirect()->back()->with('Fail','Something Went Wrong');
        }
    }
}
