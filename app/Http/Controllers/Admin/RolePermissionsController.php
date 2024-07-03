<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role_permission_mapping;
use App\Models\Permission;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Auth;
use DB;

class RolePermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $roles=Role::orderBy('id', 'DESC')->get();
        return view('roles',compact('roles'));
    }

    public function create()
    {
      
        return view('role_add_form');
    }
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;

        $validated = $request->validate([
            
            'role' => 'required|unique:temples,temple',
            
            ],
            [
            'role.required' => 'This field is required',
            
        
            ]
            
        );

        
        $values = array('role' => $request->input('role'));
$id=DB::table('roles')->insertGetId($values);
$section_num=count($request->input('section'));

        for($i = 0; $i < $section_num; $i++)
        {
            $section=$request->input('section')[$i];
            if(!empty($section)){
        $p_values = array(
            'section' => $request->input('section')[$i],
            'role_id' => $id,
            'p_view' => $request->input($section.'view'),
            'p_add' => $request->input($section.'add'),
            'p_edit' => $request->input($section.'edit'),
            'p_delete' => $request->input($section.'delete'),
             );
             
             $save= DB::table('permissions')->insert($p_values);
            }
    }

      if($save)
      {
      return redirect(route('role'))->with('status','Detials Saved Successfully !');
				
         }

     else
      {
      return redirect()->back()->with('Fail','Something Went Wrong');
       }

       
       
     
    }

   
    public function edit($id)
    {
        
        $role=Role::where('id', $id)->first();
        $p_dashboard=Permission::where('role_id', $id)->where('section', 'Dashboard')->first();
        $p_thambran=Permission::where('role_id', $id)->where('section', 'Thambran')->first();
        $p_temple=Permission::where('role_id', $id)->where('section', 'Temple')->first();
        $p_deity=Permission::where('role_id', $id)->where('section', 'Deity')->first();
        $p_pooja=Permission::where('role_id', $id)->where('section', 'Pooja')->first();
        $p_slider=Permission::where('role_id', $id)->where('section', 'Slider')->first();
        $p_slot=Permission::where('role_id', $id)->where('section', 'Slot')->first();
        $p_user=Permission::where('role_id', $id)->where('section', 'User')->first();
        $p_role=Permission::where('role_id', $id)->where('section', 'Role')->first();
        $p_doshanivaranam=Permission::where('role_id', $id)->where('section', 'Doshanivaranam')->first();
        $p_news=Permission::where('role_id', $id)->where('section', 'News')->first();
        $p_PoojaBookings=Permission::where('role_id', $id)->where('section', 'PoojaBookings')->first();
        $p_DarshanBookings=Permission::where('role_id', $id)->where('section', 'DarshanBookings')->first();
        $p_Festival=Permission::where('role_id', $id)->where('section', 'Festival')->first();
        $p_Enquiry=Permission::where('role_id', $id)->where('section', 'Enquiry')->first();
        
        return view('role_edit_form',compact('role','p_dashboard','p_slot','p_temple','p_deity','p_pooja','p_user','p_role','p_doshanivaranam','p_news','p_slider','p_PoojaBookings','p_DarshanBookings','p_Festival','p_Enquiry','p_thambran'));
    }

   
    public function update(Request $request, $id)
    {
         $validated = $request->validate([
            
            'role' => 'required|unique:temples,temple',
            
            ],
            [
            'role.required' => 'This field is required',
            
        
            ]
            
        );
        
       
        
        $updateRole= Role::find($id);
        $updateRole->role=$request->input('role');
        $update=$updateRole->save();
        $permission=Permission::where('role_id', $id)->select('section')->get();
        $old_section=[];
        foreach($permission as $row){
            $old_section[]=$row->section;
        }
        $new_section=$request->input('section');
        $del_section = array_diff($old_section, $new_section);
        $ins_section = array_diff($new_section, $old_section);
        $up_section = array_intersect($new_section, $old_section);
        
        foreach($del_section as $del){
            
            $delete=Permission::where('role_id',$id)->where('section',$del)->delete();
        }
        foreach($ins_section as $ins){
            if(!empty($ins)){
                $p_values = array(
                    'section' => $ins,
                    'role_id' => $id,
                    'p_view' => $request->input($ins.'view'),
                    'p_add' => $request->input($ins.'add'),
                    'p_edit' => $request->input($ins.'edit'),
                    'p_delete' => $request->input($ins.'delete'),
                     );
                     
                     $save= DB::table('permissions')->insert($p_values);
                    }
        }
        foreach($up_section as $up){

            $up_values = array(
                'section' => $up,
                'role_id' => $id,
                'p_view' => $request->input($up.'view'),
                'p_add' => $request->input($up.'add'),
                'p_edit' => $request->input($up.'edit'),
                'p_delete' => $request->input($up.'delete'),
                 );

           $p_update= DB::table('permissions')->where('role_id', $id)->where('section',$up)->update($up_values);

        }
       
      if($update)
      {
      return redirect(route('role'))->with('status','Detials Updated Successfully !');
				
         }

     else
      {
      return redirect()->back()->with('Fail','Something Went Wrong');
       }
    }

    
    public function destroy($id)
    {
       
        $del=Role::where('id',$id)->delete();
        

        if($del)
      {
        $delete=Permission::where('role_id',$id)->delete();
        if($delete){
      return redirect(route('role'))->with('status','Deleted Successfully !');
        }else{
            return redirect()->back()->with('Fail','Something Went Wrong');
        }
				
         }
     else
      {
      return redirect()->back()->with('Fail','Something Went Wrong');
       }
    }
}
