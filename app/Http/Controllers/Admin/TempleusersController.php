<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Auth;

class TempleusersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $users=User::join('roles','roles.id','=','users.role')->orderBy('users.id', 'DESC')->select('*','users.id')->get();
        return view('users',compact('users'));
    }

    public function create()
    {
        $role=Role::orderBy('id', 'DESC')->get();
        return view('user_add_form',compact('role'));
    }
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;

        $validated = $request->validate([
            
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'phone' => 'required|unique:users,mobile',
            'password' => 'required',
            'role' => 'required',
            
            ],
            [
            'name.required' => 'This field is required',
            'email.required' => 'This field is required',
            'phone.required' => 'This field is required',
            'password.required' => 'This field is required',
            'role.required' => 'This field is required',

        
            ]
            
        );

       

        $insertUser= new User;
        $insertUser->name=$request->input('name');
        $insertUser->email =$request->input('email');
        $insertUser->mobile=$request->input('phone');
        $insertUser->password=Hash::make($request->input('password'));
        $insertUser->role=$request->input('role');
        
        $insertUser->created_by=$user_id;

      
      

      $save= $insertUser->save();
      if($save)
      {
      return redirect(route('user'))->with('status','Detials Saved Successfully !');
				
         }

     else
      {
      return redirect()->back()->with('Fail','Something Went Wrong');
       }

       
       
     
    }

   
    public function edit($id)
    {
        $user=User::where('id',$id)->first();
        $role=Role::orderBy('id', 'DESC')->get();
        return view('user_edit_form',compact('user','role'));
    }

   
    public function update(Request $request, $id)
    {
         $validated = $request->validate([
            
            'name' => 'required',
            'email' => 'required|unique:users,email,'. $id,
            'phone' => 'required|unique:users,mobile,'. $id,
            'role' => 'required',
            
            ],
            [
            'name.required' => 'This field is required',
            'email.required' => 'This field is required',
            'phone.required' => 'This field is required',
            'role.required' => 'This field is required',

        
            ]
            
        );
        
       
        
        $updateUser= User::find($id);
        $updateUser->name=$request->input('name');
        $updateUser->email =$request->input('email');
        $updateUser->mobile=$request->input('phone');
        if(!empty($request->input('password'))){
        $updateUser->password=Hash::make($request->input('password'));
        }
        $updateUser->role=$request->input('role');
     

      $update= $updateUser->save();
      if($update)
      {
      return redirect(route('user'))->with('status','Detials Updated Successfully !');
				
         }

     else
      {
      return redirect()->back()->with('Fail','Something Went Wrong');
       }
    }

    
    public function destroy($id)
    {
       
        $del=User::where('id',$id)->delete();

        if($del)
      {
      return redirect(route('user'))->with('status','Deleted Successfully !');
				
         }
     else
      {
      return redirect()->back()->with('Fail','Something Went Wrong');
       }
    }



    public function jobskils()
    {
        return view('jobskils');
    }
    public function jobskils_add()
    {
        return view('jobskils_add_form');
    }
    public function jobskils_edit()
    {
        return view('jobskils_edit_form');
    }
    public function departments()
    {
        return view('departments');
    }
    public function departments_add()
    {
        return view('departments_add_form');
    }
    public function departments_edit()
    {
        return view('departments_edit_form');
    }

    public function designation()
    {
        return view('designation');
    }
    public function designation_add()
    {
        return view('designation_add_form');
    }
    public function designation_edit()
    {
        return view('designation_edit_form');
    }


}
