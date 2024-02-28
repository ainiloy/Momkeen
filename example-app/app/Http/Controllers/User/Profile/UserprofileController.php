<?php

namespace App\Http\Controllers\User\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Image;
use Auth;
use Hash;
use File;
use App\Models\Customer;
class UserprofileController extends Controller
{
    public function profile(){
        $user = Customer::get();
        $data['users'] = $user;
        return view ('user.profile.userprofile')->with($data);
    }

    public function profilesetup(Request $request){

        $this->validate($request, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
        ]);
        if($request->phone){
            $this->validate($request, [
                'phone' => 'min:10',
            ]);
        }


        $user_id = Auth::guard('customer')->user()->id;

        $user = Customer::findOrFail($user_id);

        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->username = $request->input('firstname') .' '. $request->input('lastname');
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
        return redirect()->back()->with('success', 'Profile Update Successfully');

    }


    public function profiledelete($id){
        
        $user = Customer::findOrFail($id);

        
        Auth::guard('customer')->logout();
        $ticket = $user->tickets()->get();

            foreach ($ticket as $tickets) {
                foreach($tickets->comments()->get() as $comment ){
                    $comment->delete();
                }  
                $tickets->delete();
            }
       
  
        $user->delete();
        
        return response()->json(['error'=> 'Accout Delete Successfully']);

    }
}
