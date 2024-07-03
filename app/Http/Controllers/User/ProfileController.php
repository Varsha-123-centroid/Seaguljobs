<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Member;
use App\Models\BirthStar;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer_auth');
    }
    public function index()
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $user=Member::join('birth_stars', 'birth_stars.id', '=', 'members.star')
        ->where('created_by',$customer_id)->where('is_admin',1)->select('created_by','dob','gender','star','id_card_no','id_card_type')->first();
        $stars=BirthStar::get();
        return view('user.profile',compact('user','stars'));
    }

    public function update_profile(Request $request)
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $validated = $request->validate([
            
            'customer_name' => 'required',
            'dob' => 'required',
            'email' => 'required|unique:customers,email,' . $customer_id,
            'id_card_type' => 'required',
            'id_card_no' => 'required',
            'gender' => 'required',
            'star_name' => 'required',
            
            ],
            [
            'customer_name.required' => 'This field is required',
            'dob.required' => 'This field is required',
            'email.required' => 'This field is required',
            'id_card_type.required' => 'This field is required',
            'id_card_no.required' => 'This field is required',
            'gender.required' => 'This field is required',
            'star_name.required' => 'This field is required',
            ]
            
        );
        
        $member=Member::where('created_by',$customer_id)->where('is_admin',1)->first();
        if(!empty($member)){
            $member->full_name=$request->input('customer_name');
            $member->dob=$request->input('dob');
            $member->gender=$request->input('gender');
            $member->star=$request->input('star_name');
            $member->id_card_type=$request->input('id_card_type');
            $member->id_card_no=$request->input('id_card_no');
            $member->contact_mobile=$request->input('mobile');
            $member->contact_email=$request->input('email');
            $member->is_admin=1;
            $member->created_by=$customer_id;
            $member->save();
        }else{
            $member= new Member;
            $member->full_name=$request->input('customer_name');
            $member->dob=$request->input('dob');
            $member->gender=$request->input('gender');
            $member->star=$request->input('star_name');
            $member->id_card_type=$request->input('id_card_type');
            $member->id_card_no=$request->input('id_card_no');
            $member->contact_mobile=$request->input('mobile');
            $member->contact_email=$request->input('email');
            $member->is_admin=1;
            $member->created_by=$customer_id;
            $member->save();

        }

        $updatecustomer= Customer::find($customer_id);
        $updatecustomer->customer_name=$request->input('customer_name');
        $updatecustomer->email =$request->input('email');
        $updatecustomer->member_id =$member->id;
        $update= $updatecustomer->save();
      if($update)
      {
      return redirect(route('kovilakam.profile'))->with('status','Detials Updated Successfully !');
				
         }

     else
      {
      return redirect()->back()->with('Fail','Something Went Wrong');
       }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
