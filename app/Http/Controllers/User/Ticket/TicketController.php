<?php

namespace App\Http\Controllers\User\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Response;
use Str;
use DataTables;
use App\Models\Ticket\Category;
use App\Models\Ticket\Ticket;
use App\Models\Ticketnote;
use App\Models\User;
use File;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
Use Auth;
use App\Notifications\TicketCreateNotifications;
class TicketController extends Controller
{
    public function create(){
        $categories = Category::where('status', '1')
            ->get();
        return view('user.ticket.create', compact('categories'));
    }
    public function store(Request $request){
        $this->validate($request, [
            'subject' => 'required|max:255',
            'category' => 'required',
            'message' => 'required',
            
        ]);
        $file = null;
        if ( $request->file ) 
            {
                // if ($img->video) {
                //  unlink($img->video);
                // }
                $file                   = $request->file('file');
                $fileName = time().'.'.$request->file->extension();
                $request->file->move(public_path('uploads/ticket/'), $fileName);
                $file             = 'uploads/ticket/' . $fileName;
               
            }
        $ticket = Ticket::create([
            'subject' => $request->input('subject'),
            'cust_id' => Auth::guard('customer')->user()->id,
            'category_id' => $request->input('category'),
            'message' => $request->input('message'),
            
            'status' => 'New',
            'file' => $file,
        ]);
        $ticket = Ticket::find($ticket->id);
        $ticket->ticket_id = 'cus'.'-'.$ticket->id;
        $categoryfind = Category::find($request->category);
        $ticket->priority = $categoryfind->priority;
        $ticket->update();
        $admins = User::all();
        foreach($admins as $admin){
            $admin->notify(new TicketCreateNotifications($ticket));
        }
        return response()->json(['success' => 'Ticket create successfully' . $ticket->ticket_id], 200);
           
    }
    public function activeticket(){
        if(request()->ajax()) {
                 $data = Ticket::where('cust_id', Auth::guard('customer')->user()->id)->whereIn('status', ['New', 'Re-Open','Inprogress'])->latest('updated_at')->get();
        
                return DataTables::of($data)
           
        
            ->addColumn('ticket_id', function($data){
                
                return '<a href="'.route('loadmore.load_data',$data->ticket_id).'">'.$data->ticket_id.'</a>';
            })
             ->addColumn('subject', function($data){
                $subject = '<a href="'.route('loadmore.load_data',$data->ticket_id).'">'.Str::limit($data->subject, '10').'</a>';
                return $subject;
            })
            
            ->addColumn('priority',function($data){
                if($data->priority != null){
                    if($data->priority == "Low"){
                        $priority = '<span class="badge badge-success-light">'.$data->priority.'</span>';
                    }
                    elseif($data->priority == "High"){
                        $priority = '<span class="badge badge-danger-light">'.$data->priority.'</span>';
                    }
                    elseif($data->priority == "Critical"){
                        $priority = '<span class="badge badge-danger-dark">'.$data->priority.'</span>';
                    }
                    else{
                        $priority = '<span class="badge badge-warning-light">'.$data->priority.'</span>';
                    }
                }else{
                    $priority = '~';
                }
                return $priority;
            })
            ->addColumn('category_id', function($data){
                if($data->category_id != null){
                    $category_id = Str::limit($data->category->name, '10');
                    return $category_id;
                }else{
                    return '~';
                }
            })
            ->addColumn('created_at',function($data){
                $created_at = $data->created_at->format('Y-m-d');
                return $created_at;
            })
            ->addColumn('status', function($data){
    
                if($data->status == "New"){
                    $status = '<span class="badge badge-burnt-orange"> '.$data->status.' </span>';
    
                }
                elseif($data->status == "Re-Open"){
                    $status = '<span class="badge badge-teal">'.$data->status.'</span> ';
                }
                elseif($data->status == "Inprogress"){
                    $status = '<span class="badge badge-info">'.$data->status.'</span>';
                }
                elseif($data->status == "On-Hold"){
                    $status = '<span class="badge badge-warning">'.$data->status.'</span>';
                }
                else{
                    $status = '<span class="badge badge-danger">'.$data->status.'</span>';
                }
    
                return $status;
            })
            ->addColumn('last_reply', function($data){
                if($data->last_reply == null){
                    $last_reply = $data->created_at->diffForHumans();
                }else{
                    $last_reply = $data->created_at->diffForHumans();
                }
    
                return $last_reply;
            })
            ->addColumn('action', function($data){
    
                $button = '<div class = "d-flex">';
                $button .= '<a href="'.route('loadmore.load_data',$data->ticket_id).'" class="action-btns1" data-bs-toggle="tooltip" data-placement="top" title="View Ticket"><i class="feather feather-edit text-primary"></i></a>
                            <a href="javascript:void(0)" class="action-btns1" data-id="'.$data->id.'" id="show-delete" data-bs-toggle="tooltip" data-placement="top" title="Delete Ticket"><i class="feather feather-trash-2 text-danger"></i></a>';
                $button .= '</div>';
              return $button;
            })
              ->rawColumns(['ticket_id','subject','priority','category_id','created_at','status','last_reply','action'])
              ->addIndexColumn()
              ->make(true);
            }
        return view('user.ticket.viewticket.activeticket');
    }

    public function closedticket(){
        if(request()->ajax()) {
                  $data = Ticket::where('cust_id', Auth::guard('customer')->user()->id)->where('status', 'Closed')->latest('updated_at')->get();
        
                return DataTables::of($data)
           
        
            ->addColumn('ticket_id', function($data){
                
                return '<a href="'.route('loadmore.load_data',$data->ticket_id).'">'.$data->ticket_id.'</a>';
            })
             ->addColumn('subject', function($data){
                $subject = '<a href="'.route('loadmore.load_data',$data->ticket_id).'">'.Str::limit($data->subject, '10').'</a>';
                return $subject;
            })
            
            ->addColumn('priority',function($data){
                if($data->priority != null){
                    if($data->priority == "Low"){
                        $priority = '<span class="badge badge-success-light">'.$data->priority.'</span>';
                    }
                    elseif($data->priority == "High"){
                        $priority = '<span class="badge badge-danger-light">'.$data->priority.'</span>';
                    }
                    elseif($data->priority == "Critical"){
                        $priority = '<span class="badge badge-danger-dark">'.$data->priority.'</span>';
                    }
                    else{
                        $priority = '<span class="badge badge-warning-light">'.$data->priority.'</span>';
                    }
                }else{
                    $priority = '~';
                }
                return $priority;
            })
            ->addColumn('category_id', function($data){
                if($data->category_id != null){
                    $category_id = Str::limit($data->category->name, '10');
                    return $category_id;
                }else{
                    return '~';
                }
            })
            ->addColumn('created_at',function($data){
                $created_at = $data->created_at->format('Y-m-d');
                return $created_at;
            })
            ->addColumn('status', function($data){
    
                if($data->status == "New"){
                    $status = '<span class="badge badge-burnt-orange"> '.$data->status.' </span>';
    
                }
                elseif($data->status == "Re-Open"){
                    $status = '<span class="badge badge-teal">'.$data->status.'</span> ';
                }
                elseif($data->status == "Inprogress"){
                    $status = '<span class="badge badge-info">'.$data->status.'</span>';
                }
                elseif($data->status == "On-Hold"){
                    $status = '<span class="badge badge-warning">'.$data->status.'</span>';
                }
                else{
                    $status = '<span class="badge badge-danger">'.$data->status.'</span>';
                }
    
                return $status;
            })
            ->addColumn('last_reply', function($data){
                if($data->last_reply == null){
                    $last_reply = $data->created_at->diffForHumans();
                }else{
                    $last_reply = $data->created_at->diffForHumans();
                }
    
                return $last_reply;
            })
            ->addColumn('action', function($data){
    
                $button = '<div class = "d-flex">';
                $button .= '<a href="'.route('loadmore.load_data',$data->ticket_id).'" class="action-btns1" data-bs-toggle="tooltip" data-placement="top" title="View Ticket"><i class="feather feather-edit text-primary"></i></a>
                            <a href="javascript:void(0)" class="action-btns1" data-id="'.$data->id.'" id="show-delete" data-bs-toggle="tooltip" data-placement="top" title="Delete Ticket"><i class="feather feather-trash-2 text-danger"></i></a>';
                $button .= '</div>';
              return $button;
            })
              ->rawColumns(['ticket_id','subject','priority','category_id','created_at','status','last_reply','action'])
              ->addIndexColumn()
              ->make(true);
            }
          return view('user.ticket.viewticket.closedticket');
    }
    public function onholdticket(){
        if(request()->ajax()) {
                   $data = Ticket::where('cust_id', Auth::guard('customer')->user()->id)->where('status', 'On-Hold')->latest('updated_at')->get();
                return DataTables::of($data)
           
        
            ->addColumn('ticket_id', function($data){
                
                return '<a href="'.route('loadmore.load_data',$data->ticket_id).'">'.$data->ticket_id.'</a>';
            })
             ->addColumn('subject', function($data){
                $subject = '<a href="'.route('loadmore.load_data',$data->ticket_id).'">'.Str::limit($data->subject, '10').'</a>';
                return $subject;
            })
            
            ->addColumn('priority',function($data){
                if($data->priority != null){
                    if($data->priority == "Low"){
                        $priority = '<span class="badge badge-success-light">'.$data->priority.'</span>';
                    }
                    elseif($data->priority == "High"){
                        $priority = '<span class="badge badge-danger-light">'.$data->priority.'</span>';
                    }
                    elseif($data->priority == "Critical"){
                        $priority = '<span class="badge badge-danger-dark">'.$data->priority.'</span>';
                    }
                    else{
                        $priority = '<span class="badge badge-warning-light">'.$data->priority.'</span>';
                    }
                }else{
                    $priority = '~';
                }
                return $priority;
            })
            ->addColumn('category_id', function($data){
                if($data->category_id != null){
                    $category_id = Str::limit($data->category->name, '10');
                    return $category_id;
                }else{
                    return '~';
                }
            })
            ->addColumn('created_at',function($data){
                $created_at = $data->created_at->format('Y-m-d');
                return $created_at;
            })
            ->addColumn('status', function($data){
    
                if($data->status == "New"){
                    $status = '<span class="badge badge-burnt-orange"> '.$data->status.' </span>';
    
                }
                elseif($data->status == "Re-Open"){
                    $status = '<span class="badge badge-teal">'.$data->status.'</span> ';
                }
                elseif($data->status == "Inprogress"){
                    $status = '<span class="badge badge-info">'.$data->status.'</span>';
                }
                elseif($data->status == "On-Hold"){
                    $status = '<span class="badge badge-warning">'.$data->status.'</span>';
                }
                else{
                    $status = '<span class="badge badge-danger">'.$data->status.'</span>';
                }
    
                return $status;
            })
            ->addColumn('last_reply', function($data){
                if($data->last_reply == null){
                    $last_reply = $data->created_at->diffForHumans();
                }else{
                    $last_reply = $data->created_at->diffForHumans();
                }
    
                return $last_reply;
            })
            ->addColumn('action', function($data){
    
                $button = '<div class = "d-flex">';
                $button .= '<a href="'.route('loadmore.load_data',$data->ticket_id).'" class="action-btns1" data-bs-toggle="tooltip" data-placement="top" title="View Ticket"><i class="feather feather-edit text-primary"></i></a>
                            <a href="javascript:void(0)" class="action-btns1" data-id="'.$data->id.'" id="show-delete" data-bs-toggle="tooltip" data-placement="top" title="Delete Ticket"><i class="feather feather-trash-2 text-danger"></i></a>';
                $button .= '</div>';
              return $button;
            })
              ->rawColumns(['ticket_id','subject','priority','category_id','created_at','status','last_reply','action'])
              ->addIndexColumn()
              ->make(true);
            }
          return view('user.ticket.viewticket.onholdticket');
    }


    public function show(Request $req, $ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->with('comments','category')->firstOrFail();
        $comments = $ticket->comments()->paginate(5);
        $category = $ticket->category;


        if (request()->ajax()) {
            $view = view('user.ticket.showticketdata',compact('comments'))->render();
            return response()->json(['html'=>$view]);
        }
        return view('user.ticket.showticket', compact('ticket','category', 'comments'));
        
       
    }

    public function close(Request $request,$ticket_id){
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

        $ticket->status = 'Re-Open';

        $ticket->update();
        return redirect()->back()->with("success", 'The ticket has been successfully reopened.');
    }
}
