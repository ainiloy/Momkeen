<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Ticket\Category;
use App\Models\Ticket\Ticket;
use App\Models\Groups;
use DB;
use Response;
use Str;
use DataTables;
use Auth;
class CategoryController extends Controller
{
    public function index(){
        $categories = DB::table('categories')->paginate();
            $data['categories'] = $categories;
            $d = Category::find(2);
           
            // dd($d->groupscategoryc()->count());
        if(request()->ajax()) {

           $data = Category::with('groupscategoryc')->get();
           return DataTables::of($data)->addColumn('name', function($data){
                    return Str::limit($data->name, '40');
                })->addColumn('priority', function($data){
                    if($data->priority != null){

                        if($data->priority == "Low"){

                            return '<span class="badge badge-success-light" >'.$data->priority.'</span>';
                                
                        }
                        elseif($data->priority == "High"){

                            return '<span class="badge badge-danger-light">'.$data->priority.'</span>';
                                
                        }elseif($data->priority == "Critical"){

                            return '<span class="badge badge-danger-dark">'.$data->priority.'</span>';
                            
                        }else{

                            return ' <span class="badge badge-warning-light">'.$data->priority.'</span>';
                                
                        }
                    }
                    else{
                        return '~';
                    }

                })->addColumn('groupcategory', function($data){
                    if(Auth::user()->can('Category Assign To Groups')){
                    
                        
                            $button = '<a href="javascript:void(0)" data-id="'.$data->id.'" id="assigneds" class="badge badge-pill badge-info mt-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Assign to group">
                        '.$data->groupscategoryc()->count().'
                        </a>';
                        return $button;
                        
                        
                    }else{
                        return '~';
                    }
                })->addColumn('status', function($data){
                    
                        if($data->status == '1'){
                            $button = '
                            <label class="custom-switch form-switch mb-0">
                                <input type="checkbox" name="status" data-id="'.$data->id.'" id="myonoffswitch'.$data->id.'" value="1" class="custom-switch-input tswitch" checked>
                                <span class="custom-switch-indicator"></span>
                            </label>';
                        }else{
                            $button = ' 
                                <label class="custom-switch form-switch mb-0">
                                    <input type="checkbox" name="status" data-id="'.$data->id.'" id="myonoffswitch'.$data->id.'" value="1" class="custom-switch-input tswitch">
                                    <span class="custom-switch-indicator"></span>
                                </label> ';
                        }
                        return $button;
                    
                })->addColumn('action', function($data){
                    
                  $button = '<div class = "d-flex"><a href="javascript:void(0)" data-id="'.$data->id.'" class="action-btns1 edit-testimonial"><i class="feather feather-edit text-primary" data-id="'.$data->id.'"data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"></i></a></div>';
                    
                  return $button;
                })->rawColumns(['name','priority','status','action','groupcategory',])
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.category.index')->with($data);
    }

    public function status(Request $request, $id)
    {
          $calID = Category::find($id);
          $calID ->status = $request->status;
          $calID ->save();
        return response()->json(['code'=>200, 'success'=> 'Update Successfully'], 200);
    }
    public function store(Request $request){
         
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);
            if($validator->passes()){
            $testiId = $request->testimonial_id;
            $categoryfind = Category::find($testiId);
            if($categoryfind){
                

                    $testi =  [
                        'name' => $request->name,
                        
                        'priority' => $request->priority,
                        
                        'status' => $request->status ?  '1' :  '0',
                    ];
               
                
            }
            if(!$categoryfind){
                $testi =  [
                    'name' => $request->name,
                  
                    'priority' => $request->priority,
                  
                    'status' => $request->status ?  '1' :  '0',
                ];
            }        
            $testimonial = Category::updateOrCreate(['id' => $testiId], $testi);
            return response()->json(['code'=>200, 'success'=> 'Create Successfully','data' => $testimonial], 200);
            }
            else
            {
                return Response::json(['errors' => $validator->errors()]);
            }
        
    }

    

    public function categorylist(Request $req, $ticket_id){
        if($req->ajax()){
            $output = '';
            $category = Category::where('status', '1')->get();

            $totalrow = $category->count();
            $ticket = DB::table('tickets')->where('ticket_id', $ticket_id)->first();
            if($totalrow > 0){
                $output .='<option label="Select Category"></option>';
                foreach($category as $categories){
                    $output .= '
                    <option  value="'.$categories->id.'"'.($categories->id == $ticket->category_id ? 'selected': '').'>'.$categories->name.'</option>
                    ';
                }
            }else{
                $output .= '
                <option label="No Data Found"></option>
                ';
            }


           
            
            $data = array(
                
                'table_data' => $output,
                'total_data' => $totalrow,
                'ticket' => $ticket,
                
            );
          return response()->json($data, 200);
        }
    }

    public function categorychange(Request $req)
    {

        $this->validate($req, [
            'category' => 'required',
        ]);

        $ticketcategory = Ticket::find($req->ticket_id);
        $ticketcategory->category_id = $req->category;
        
        
    
        
        
        $findcat = Category::find($req->category);
        $ticketcategory->priority = $findcat->priority;
        $ticketcategory->update();

        return response()->json(['success' => 'Update Successfully']);
        

    }

    public function groupshow(Request $req, $id)
    {

        if($req->ajax()){
            $output = '';

            $assign = Category::find($id);
            
            $data = Groups::get();

            $total_row = $data->count();

            $cat = DB::table("groupscategories")->where("groupscategories.category_id",$id)
            ->pluck('groupscategories.group_id','groupscategories.group_id')
            ->all();
           
            if($total_row > 0){
                foreach($data as $row){

                    
                    $output .= '
                        
                        
                        <option  value="'.$row->id.'" ' .( $row->id  ? in_array($row->id,$cat) ?  'selected' : '' : '').'>'.$row->groupname.'</option>
                            
                    ';
             
                   
                }                   
                                
            }else{
                $output = '
                <option label="Select Group"></option>
                <option >No Data Found</option>
                ';
            }
            $data = array(
                'assign_data'=> $assign,
                'table_data' => $output,
                'total_data' => $total_row,
                'data' => $cat
            );

           
            return response()->json($data);
        }
    }

    public function categorygroupassign(Request $request)
    {

        $data =  $request->category_id;
        $cat = Category::find($data);
        

        if($request->input('group_id')){
            foreach ($request->input('group_id') as $value) {
                $group_id[] = $value;

                
            }
        }
        
        $cat->groupscategory()->sync($request->get('group_id'));
        

        return response()->json(['success'=> 'Updated successfully']);

    }

    public function show($id)
    {
       
        $post = Category::find($id);
        
        $data = [
            'post' => $post,
        ];
        return response()->json($data);

    }

}
