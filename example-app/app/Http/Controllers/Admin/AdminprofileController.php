<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Hash;
use File;
use Image;
use Illuminate\Support\Str;
use DB;
use DataTables;
use Session;
class AdminprofileController extends Controller
{
    public function index(){
        return view('admin.profile.adminprofile');
    }
    public function usersetting(Request $request)
    {
        $users = User::find($request->user_id);
        $users->darkmode = $request->dark;
        $users->update();
        return response()->json(['code'=>200, 'success'=> 'Update Successfully'], 200);

    }
    public function changePassword(Request $request)
    {
        
        $request->validate([
          'current_password' => 'required|max:255',
          'password' => 'required|string|min:8|confirmed|max:255',
          'password_confirmation' => 'required|max:255',
        ]);
        if($request->current_password == $request->password){
            return back()->with('error', 'Your new password can not be the same as your old password. Please choose a new password.');
        }else{
            $user = Auth::user();
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->save();

                Auth::logout();
        
                return  redirect()->route('login')->with('success', trans('langconvert.functions.changepassword'));
            }
            else{
                return back()->with('error', trans('langconvert.functions.changepasswordnotmatch'));
            }
        }

        
    }
    public function profileedit()
    {

        return view('admin.profile.adminprofileupdate');

    }
    public function profilesetup(Request $request){
        $this->validate($request, [
            'firstname' => 'max:255',
            'lastname' => 'max:255',
        ]);
         if($request->phone){
            $this->validate($request, [
                'phone' => 'numeric',
            ]);
         }

        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);

        $user->firstname = ucfirst($request->input('firstname'));
        $user->lastname = ucfirst($request->input('lastname'));
        $user->name = ucfirst($request->input('firstname')).' '.ucfirst($request->input('lastname'));
        $user->languagues = implode(', ', $request->input('languages'));
        $user->skills = implode(', ', $request->input('skills'));
        $user->phone = $request->input('phone');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileArray = array('image' => $file);
            $rules = array(
                'image' => 'mimes:jpeg,jpg,png|required|max:5120' // max 10000kb
              );
          
              // Now pass the input and rules into the validator
              $validator = Validator::make($fileArray, $rules);

              if ($validator->fails())
                {
                    return redirect()->back()->with('error', 'image error');
                }else{
                   
                        $destination = 'uploads/profile';
                        $image_name = time() . '.' . $file->getClientOriginalExtension();
                        $resize_image = Image::make($file->getRealPath());

                        $resize_image->resize(80, 80, function($constraint){
                        $constraint->aspectRatio();
                        })->save($destination . '/' . $image_name);

                        $destinations = 'uploads/profile/'.$user->image;
                        if(File::exists($destinations)){
                            File::delete($destinations);
                        }
                        $file = $request->file('image');
                        $user->update(['image'=>$image_name]);
                    }
            
            
        }
       
       
        $user->update(); 
        return redirect('admin/profile')->with('success', 'Update Successfully');

    }
    public function imageremove(Request $request, $id){

        $user = User::findOrFail($id);

        $user->image = null;
        $user->update();
        return response()->json(['success'=> 'Profile Delete Successfully']);
        
    }

     public function customers()
    {
        $this->authorize('Customers Access');
        


        if(request()->ajax()) {
            $data = Customer::latest()->get();
            return DataTables::of($data)
            ->addColumn('action', function($data){
                $button = '<div class = "d-flex">';
                if(Auth::user()->can('Customers Edit')){
        
                    $button .= '<a href="'.url('/admin/customer/' . $data->id).'" class="action-btns1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="feather feather-edit text-primary"></i></a>';
                }else{
                    $button .= '~';
                }
                if(Auth::user()->can('Customers Delete')){
                    $button .= '<a href="javascript:void(0)" class="action-btns1" data-id="'.$data->id.'" id="show-delete" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="feather feather-trash-2 text-danger"></i></a>';
                }else{
                    $button .= '~';
                }
                
                $button .= '</div>';
                return $button;
              })
              ->addColumn('checkbox', function($data){
                if(Auth::user()->can('Customers Delete')){
                    return '<input type="checkbox" name="customer_checkbox[]" class="checkall" value="'.$data->id.'" />';
                }else{
                    return '<input type="checkbox" name="customer_checkbox[]" class="checkall" value="'.$data->id.'" disabled />';
                }
            })
            ->addColumn('status', function($data){
                if($data->status == "1"){
                    return '<span class="badge badge-success">Active</span>';
                }
                else{
                    return '<span class="badge badge-danger">Inactive</span>';
                }
                
            
            })
            ->addColumn('verified', function($data){
                if($data->verified == 1){
                 return 'Verified';
                }
                else{
                    return 'Unverified';
                }
            
            })
            ->addColumn('created_at', function($data){
                return '<span class="badge badge-success-light">'.$created_at = $data->created_at->format('Y-m-d').'</span>';
            })
            ->addColumn('username', function($data){
                if(auth()->user()->can('Customers Login')){

                    return '<div><a href="#" class="h5">'.Str::limit($data->username, '40').'</a></div>
                    <small class="fs-12 text-muted"> <span class="font-weight-normal1">'.Str::limit($data->email, '40').'</span></small>
                    <a href="'.url("admin/customer/adminlogin/". $data->id).'"  target="_blank"><span class="badge badge-success text-white f-12">'.__('Login as').'</span></a>';
                }else{

                    return '<div><a href="#" class="h5">'.Str::limit($data->username, '40').'</a></div>
                    <small class="fs-12 text-muted"> <span class="font-weight-normal1">'.Str::limit($data->email, '40').'</span></small>';
                }
                
             
            })->addColumn('gender', function($data){
                return $data->gender;
                
            
            })->rawColumns(['action','checkbox','status','created_at','username','gender'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.customers.index');

       
    }
    public function customerscreate()
    {
        $this->authorize('Customers Create');
        return view('admin.customers.create');
    }


    public function customersstore(Request $request){
        $this->authorize('Customers Create');
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8',
        ]);

        if($request->phone){
            $request->validate([
                'phone' => 'string',
            ]);
        }
        
        $customer = Customer::create([
            'firstname' => Str::ucfirst($request->input('firstname')),
            'lastname' => Str::ucfirst($request->input('lastname')),
            'name' => Str::ucfirst($request->input('firstname')) . " " .Str::ucfirst($request->input('lastname')),
            'email' => $request->email,
            'status' => '1',
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'gender' => $request->gender,
            'image' => null,
            'verified' => '1',
            'userType' => 'Customer',
            
        ]);

        $customers = Customer::find($customer->id);
        $customers->username = $customer->firstname.' '.$customer->lastname;
        $customers->update();

        
        return redirect('admin/customer')->with('success','Customer create successfully');

    }
    public function customersshow($id){
        $this->authorize('Customers Edit');
        
         $user = Customer::where('id', $id)->first();
        $data['user'] = $user;
        return view('admin.customers.show')->with($data);

    }


    public function customersupdate(Request $request, $id)
    {
        $this->authorize('Customers Edit');
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        if($request->phone){
            $request->validate([
                'phone' => 'string',
            ]);
        }
        $user = Customer::where('id', $id)->findOrFail($id);
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->name = $request->input('firstname').' '.$request->input('lastname');
        $user->username = $request->input('firstname').' '.$request->input('lastname');
        $user->email = $request->input('email');
        $user->country = $request->input('country');
        $user->timezone = $request->input('timezone');
        $user->status = $request->input('status');
        $user->update();
        $request->session()->forget('email',$user->email);

        return redirect('/admin/customer')->with('success', trans('langconvert.functions.customerupdate'));
      
    }

    public function customersdelete($id){
        $this->authorize('Customers Delete');
        $user = Customer::findOrFail($id);
        $ticket = $user->tickets()->get();

            foreach ($ticket as $tickets) { 
                foreach($tickets->comments as $comment){
                    $comment->delete();
                }
            $tickets->delete();
        }
        $user->delete();

        return response()->json(['error'=> 'Customer Delete Successfully']);
    }
}
