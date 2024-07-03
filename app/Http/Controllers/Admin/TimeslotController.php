<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timeslot;
use App\Models\Temple;
use Auth;

class TimeslotController extends Controller
{
    
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        
        $timeslots=Timeslot::join('temples','temples.id','=','timeslots.temp_id')->orderBy('timeslots.id', 'DESC')->select('*','timeslots.id')->get();
        return view('time_slot',compact('timeslots'));
    }
    public function create()
    {
         $temple=Temple::orderBy('id', 'DESC')->get();
        return view('time_slot_add_form',compact('temple'));
    }

    
    public function store(Request $request)
    {
         $user_id = Auth::user()->id;

        $validated = $request->validate([
            'temple' => 'required',
            'newdate' => 'required',
            
            
            ],
            [
            'temple.required' => 'This field is required',
            'newdate.required' => 'This field is required',
            
        
            ]
            
        );

       
        
       
        $time=json_encode($request->input('time'));
        $time_period=json_encode($request->input('time_period'));
        $tickets=json_encode($request->input('tickets'));
        
        $insertTimeslot= new Timeslot;
        $insertTimeslot->date=$request->input('newdate');
        $insertTimeslot->temp_id=$request->input('temple');
        $insertTimeslot->time=$time;
        $insertTimeslot->time_period=$time_period;
        $insertTimeslot->tickets=$tickets;
        $insertTimeslot->created_by=$user_id;
        $save= $insertTimeslot->save();
       
        
      if($save)
      {
      return redirect(route('time_slot'))->with('status','Detials Saved Successfully !');
				
         }

     else
      {
      return redirect()->back()->with('Fail','Something Went Wrong');
       }
    }

   

   
    public function edit($id)
    {
        $temple=Temple::orderBy('id', 'DESC')->get();
        $timeslot=Timeslot::where('id',$id)->first();
        return view('time_slot_edit_form',compact('timeslot','temple'));
    }

   
    public function update(Request $request, $id)
    {
         $validated = $request->validate([
            
            'temple' => 'required',
            'newdate' => 'required',
            
            ],
            [
            'temple.required' => 'This field is required',
            'newdate.required' => 'This field is required',
            
            ]
            
        );
        $time=json_encode($request->input('time'));
        $time_period=json_encode($request->input('time_period'));
        $tickets=json_encode($request->input('tickets'));
       

        $updatetimeslot= Timeslot::find($id);
        $updatetimeslot->date=$request->input('newdate');
        $updatetimeslot->temp_id=$request->input('temple');
        $updatetimeslot->time=$time;
        $updatetimeslot->time_period=$time_period;
        $updatetimeslot->tickets=$tickets;
        $update= $updatetimeslot->save();
      if($update)
      {
      return redirect()->back()->with('status','Detials Updated Successfully !');
				
         }

     else
      {
      return redirect()->back()->with('Fail','Something Went Wrong');
       }
    }

   
    public function destroy($id)
    {
        $delete=Timeslot::where('id',$id)->delete();
        
        
       

        if($delete)
      {
        
    

      return redirect(route('time_slot'))->with('status','Deleted Successfully !');
				
         }

       
        
        

     else
      {
      return redirect()->back()->with('Fail','Something Went Wrong');
       }
    }
    public function allocated_date($id)
    {
        // $temple=Temple::orderBy('id', 'DESC')->get();
        $allocated_date=Timeslot::where('temp_id',$id)->get();
        return view('timeslotdates',compact('allocated_date',));
    }
}
