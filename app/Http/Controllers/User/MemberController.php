<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\BirthStar;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer_auth');
    }
    public function index()
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $members=Member::join('birth_stars', 'birth_stars.id', '=', 'members.star')
        ->where('created_by',$customer_id)->select('*','members.id')->get();
        $stars=BirthStar::get();
       
        return view('user.members',compact('members','stars'));
    }

    public function add_member(Request $request)
    {
        $customer_id = Auth::guard('customer')->user()->id;

        $validated = $request->validate([
            
            'name' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'star_id' => 'required',
            'gothram' => 'required',
            'id_card_type' => 'required',
            'id_card_no' => 'required',
            'contact' => 'required',
            'email' => 'required',
            
            
            ],
            [
            'name.required' => 'This field is required',
            'dob.required' => 'This field is required',
            'gender.required' => 'This field is required',
            'star_id.required' => 'This field is required',
            'gothram.required' => 'This field is required',
            'id_card_type.required' => 'This field is required',
            'id_card_no.required' => 'This field is required',
            'contact.required' => 'This field is required',
            'email.required' => 'This field is required',
            
        
            ]
            
        );

        $insertmember= new Member;
        $insertmember->full_name=$request->input('name');
        $insertmember->dob=$request->input('dob');
        $insertmember->gender=$request->input('gender');
        $insertmember->star=$request->input('star_id');
        $insertmember->gothram=$request->input('gothram');
        $insertmember->id_card_type=$request->input('id_card_type');
        $insertmember->id_card_no=$request->input('id_card_no');
        $insertmember->contact_mobile=$request->input('contact');
        $insertmember->contact_email=$request->input('email');
       
        $insertmember->created_by=$customer_id;
        $save= $insertmember->save();
        if($save)
        {
        return redirect(route('kovilakam.members'))->with('status','Detials Saved Successfully !');
                  
           }
  
       else
        {
        return redirect()->back()->with('Fail','Something Went Wrong');
         }
    }

    public function delete_member(Request $request)
    {
        $id=$request->input('id');
        $del=Member::where('id',$id)->delete();
        
       
        if($del)
        {
            return response()->json(['status'=>true,'message' => 'Removed successfully']);
                      
               }
      
           else
            {
                return response()->json(['status'=>false,'message' => 'Something went wrong']);
             }
    }
}
