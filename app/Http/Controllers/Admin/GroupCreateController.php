<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\User;
use App\Models\CategoryUser;
use App\Models\Groups;
use Str;
use Auth;
use DB;
class GroupCreateController extends Controller
{
    public function index(){

        
        $this->authorize('Groups List Access');
            
            if(request()->ajax()) {
                $data = Groups::get();
                
                return DataTables::of($data)
                ->addColumn('action', function($data){
                    if(Auth::user()->can('Groups Edit')){
                    $button = '<div class = "d-flex"><a href="'.url('admin/groups/view/'.$data->id).'" data-id="'.$data->id.'" class="action-btns1 edit-testimonial"><i class="feather feather-edit text-primary" data-id="'.$data->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"></i></a></div>';
                    }else{
                        $button .= '~';
                    }
                    return $button;
                })
                ->addColumn('groupname', function($data){
                    return Str::limit($data->groupname, '40');
                })
                ->addColumn('groupcount', function($data){
                    return '<span class="badge badge-info">'.$data->groupsuser()->count().'</span>';
                })

                ->rawColumns(['action','groupname','groupcount'])
                ->addIndexColumn()
                ->make(true);

            }

            return view('admin.groups.index');
    }

    public function create(){
        $this->authorize('Groups Create');
        
        
        $users = User::get();
        $data['users'] = $users;
        return view('admin.groups.create')->with($data);
    }

    public function store(Request $request){
        $this->authorize('Groups Create');
        $request->validate([
            'groupname' => 'required|string|max:255|unique:groups',
            
        ]);
        $grop = new Groups;

        $grop->groupname = $request->input('groupname');
        $grop->save();

        if($request->input('user_id')){
            foreach ($request->input('user_id') as $value) {
                $user_id[] = $value;

                
            }
        }
        
        $grop->groupsusers()->sync($request->get('user_id'));

        return redirect('admin/groups')->with('success', 'A group was successfully created.');
    }

    public function show($id){
        $this->authorize('Groups Edit');
        

        $grop = Groups::find($id);
        $data['group'] = $grop;

        $group = DB::table("groupsusers")->where("groupsusers.groups_id",$id)
            ->pluck('groupsusers.users_id','groupsusers.users_id')
            ->all();
        $data['grop'] = $group;

        $users = User::get();
        $data['users'] = $users;

        return view('admin.groups.edit')->with($data);
    }


    public function update(Request $request, $id){
        $this->authorize('Groups Edit');
        
        $grop = Groups::find($id);
        if($grop->groupname == $request->groupname){
            
            if($request->input('user_id')){
                foreach ($request->input('user_id') as $value) {
                    $user_id[] = $value;
    
                    
                }
            }
            
            $grop->groupsusers()->sync($request->get('user_id'));
        }else{
            $request->validate([
                'groupname' => 'required|string|max:255|unique:groups',
            ]);

            $grop->groupname = $request->input('groupname');
            $grop->update();

            if($request->input('user_id')){
                foreach ($request->input('user_id') as $value) {
                    $user_id[] = $value;
                }
            }
            $grop->groupsusers()->sync($request->get('user_id'));
        }
        
        return redirect('admin/groups')->with('success', 'The group updated successfully.');
    }
}
