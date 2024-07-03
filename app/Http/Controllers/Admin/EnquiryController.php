<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Auth;

class EnquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $enquiry=Contact::orderBy('id', 'DESC')->get();
        return view('enquiries',compact('enquiry'));
    }

    


   
    public function view($id)
    {
        
        $enquiry=Contact::where('id',$id)->first();
        return view('enquirydetail',compact('enquiry'));
    }

   
    
    
    public function destroy($id)
    {
        
        $del=Contact::where('id',$id)->delete();
        
       

        if($del)
      {
        
    

      return redirect(route('enquiry'))->with('status','Deleted Successfully !');
				
         }

       
        
        

     else
      {
      return redirect()->back()->with('Fail','Something Went Wrong');
       }
    }
    public function get_deity(Request $request,$id)
    {
        $deity=Deity::where('temple_id',$id)->orderBy('id', 'DESC')->get();
        return json_encode($deity);
    }
}
