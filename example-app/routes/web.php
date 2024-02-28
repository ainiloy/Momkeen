<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Admin\AdminAssignedticketsController;
use App\Http\Controllers\Admin\CommentsController;
use App\Http\Controllers\Admin\AdminprofileController;
use App\Http\Controllers\Admin\AgentCreateController;
use App\Http\Controllers\Admin\ProjectsController;
use App\Http\Controllers\Admin\RoleCreateController;
use App\Http\Controllers\User\Ticket\TicketController;
use App\Http\Controllers\User\Auth\RegisterController;
use App\Http\Controllers\User\Auth\ChangepasswordController;



use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/clear', function() {

   Artisan::call('cache:clear');
   Artisan::call('config:clear');
   Artisan::call('config:cache');
   Artisan::call('view:clear');

   return "Cleared!";

});
//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});
Route::get('/',[HomeController::class,'index'])->name('home');
Route::middleware('guest')->group(function () {
Route::get('/admin-login',[AuthenticatedSessionController::class,'create'])->name('user.login');
});

Route::group(['middleware' =>['admin.auth','auth']], function(){
    Route::group(['prefix'=>'admin'],function(){
        Route::get('/',[AdminDashboardController::class,'dashboard'])->name('admin.dashboard');
        Route::get('/profile',[AdminprofileController::class,'index'])->name('admin.profile.index');
        Route::post('/profile',[AdminprofileController::class,'profilesetup'])->name('admin.profile.profilesetup');
        Route::post('image/remove/{id}',[AdminprofileController::class,'imageremove'])->name('admin.profile.change.password');
        Route::get('profile/edit',[AdminprofileController::class,'profileedit'])->name('admin.profile.edit');
        Route::get('/usersettings',[AdminprofileController::class,'usersetting'])->name('admin.profile.usersetting');
        Route::post('change-password',[AdminprofileController::class,'changePassword'])->name('admin.profile.changePassword');
        Route::get('/categories',[CategoryController::class,'index'])->name('category.index');
        Route::get('category/list/{ticket_id}',[CategoryController::class,'categorylist']);
        Route::post('category/change',[CategoryController::class,'categorychange']);
        Route::get('/categories/status{id}',[CategoryController::class,'status']);
        Route::post('/categories/create',[CategoryController::class,'store']);
        Route::get('createticket',[AdminTicketController::class,'createticket'])->name('admin.ticket.create');
        Route::post('createticket',[AdminTicketController::class,'storeticket'])->name('admin.ticket.sotre');
        Route::get('alltickets',[AdminTicketController::class,'alltickets'])->name('admin.ticket.all');
        Route::get('/ticket-view/{ticket_id}', [AdminTicketController::class,'show'])->name('admin.ticketshow');
        Route::post('priority/change',[AdminTicketController::class,'changepriority']);
        Route::get('assigned/{id}',[AdminAssignedticketsController::class,'show']);
        Route::post('assigned/create',[AdminAssignedticketsController::class,'create']);
        Route::get('assigned/update/{id}',[AdminAssignedticketsController::class,'update']);
        Route::get('ticket/delete/tickets/{id}',[AdminAssignedticketsController::class,'ticketmassdestroy']);
        Route::post('ticket-view/ticketassigneds/{id}',[AdminAssignedticketsController::class,'show']);
        Route::post('note/create',[AdminTicketController::class,'note']);
        Route::delete('ticketnote/delete/{id}',[AdminTicketController::class,'notedestroy']);
        Route::post('ticket/{ticket_id}',[CommentsController::class,'postComment']);
        Route::post('ticket/editcomment/{ticket_id}',[CommentsController::class,'updateedit']);
        Route::post('ticket/reopen/{id}',[CommentsController::class,'reopenticket']);
        Route::get('myticket',[AdminTicketController::class,'mytickets']);
        Route::get('activeticket',[AdminTicketController::class,'activeticket'])->name('admin.activeticket');
        Route::get('closedticket',[AdminTicketController::class,'closedticket'])->name('admin.closedticket');
        Route::get('assignedtickets',[AdminTicketController::class,'assignedtickets'])->name('admin.assignedtickets');
        Route::get('myassignedtickets',[AdminTicketController::class,'myassignedtickets'])->name('admin.myassignedtickets');
        Route::get('onholdtickets',[AdminTicketController::class,'onholdticket'])->name('admin.onholdticket');
        Route::get('ticket/delete/tickets',[AdminTicketController::class,'ticketmassdestroy'])->name('admin.ticket.sremove');
        Route::get('delete-ticket/{id}',[AdminTicketController::class,'destroy'])->name('admin.ticket.destroy');

        Route::get('role',[RoleCreateController::class,'index'])->name('admin.index.role');
        Route::get('role/create',[RoleCreateController::class,'create'])->name('admin.create.role');
        Route::get('role/edit/{id}',[RoleCreateController::class,'edit']);
        Route::post('role/create',[RoleCreateController::class,'store'])->name('admin.create.store');
        Route::post('role/edit/{id}',[RoleCreateController::class,'update'])->name('admin.create.update');


        Route::get('employee',[AgentCreateController::class,'index'])->name('admin.emp.index');
        Route::get('employee/create',[AgentCreateController::class,'create'])->name('admin.emp.create');
        Route::get('userimport',[AgentCreateController::class,'userimportindex'])->name('user.userimport');
        Route::post('userimport',[AgentCreateController::class,'usercsv'])->name('user.usercsv.stoer');
        Route::post('agent',[AgentCreateController::class,'store'])->name('admin.imp.store');
        Route::post('agent/status/{id}',[AgentCreateController::class,'status'])->name('admin.imp.status.update');
        Route::get('agentprofile/{id}',[AgentCreateController::class,'show'])->name('admin.agent.show');

        Route::get('customer',[AdminprofileController::class,'customers'])->name('admin.customer');
        Route::get('/customer/create',[AdminprofileController::class,'customerscreate'])->name('admin.customer.create');
        Route::post('/customer/create',[AdminprofileController::class,'customersstore'])->name('admin.customer.store');
        Route::get('/customer/{id}',[AdminprofileController::class,'customersshow'])->name('admin.customer.show');
        Route::post('/customer/{id}',[AdminprofileController::class,'customersupdate'])->name('admin.customer.stroe');
        Route::get('/customer/delete/{id}',[AdminprofileController::class,'customersdelete'])->name('admin.customer.delete');
        Route::get('/notifications',[ProjectsController::class,'notificationpage'])->name('notificationpage');
        Route::get('/mark-as-read',[AdminDashboardController::class,'markNotification'])->name('admin.markNotification');



    });



});


Route::group(['namespace' => 'App\Http\Controllers\User','prefix'=>'customer'],function(){
    Route::group(['namespace' => 'Auth'], function(){
        Route::get('/login','LoginController@showLoginForm')->middleware('guest:customer')->name('auth.login');
        Route::post('/login','LoginController@login')->middleware('guest:customer')->name('client.do_login');
        Route::post('/ajaxlogin','LoginController@showLoginForm')->middleware('guest:customer')->name('client.do_ajaxlogin');
        Route::post('/c/logout','LoginController@logout')->name('client.logout');
        Route::post('/change-password', 'ChangepasswordController@changepassword')->name('change.password');

        Route::get('/register','RegisterController@showRegistrationForm')->middleware('guest:customer')->name('auth.register');
        Route::post('/register', 'RegisterController@register')->middleware('guest:customer')->name('auth.register.store');
    });

    Route::middleware('auth:customer','customer.auth')->group(function () {
        Route::get('/', 'DashboardController@userTickets')->name('client.dashboard');
        Route::get('/profile','Profile\UserprofileController@profile')->name('client.profile');
        Route::post('/profile','Profile\UserprofileController@profilesetup')->name('client.profilesetup');
        Route::get('/deleteaccount/{id}','Profile\UserprofileController@profiledelete')->name('client.profiledelete');
        Route::delete('/image/remove/{id}', 'Profile\UserprofileController@imageremove');
        Route::post('/custsettings', 'Profile\UserprofileController@custsetting');
        Route::get('/ticket','Ticket\TicketController@create')->name('client.ticket');
        Route::post('/ticket','Ticket\TicketController@store')->name('client.ticketcreate');
        Route::post('/imageupload','Ticket\TicketController@storeMedia')->name('imageupload');
        Route::get('/ticket/view/{ticket_id}','Ticket\TicketController@show')->name('loadmore.load_data');

        Route::post('/ticket/editcomment/{id}','Ticket\CommentsController@updateedit')->name('client.comment.edit');
        Route::get('/activeticket','Ticket\TicketController@activeticket')->name('activeticket');
        Route::get('/closedticket','Ticket\TicketController@closedticket')->name('closedticket');
        Route::get('/onholdticket','Ticket\TicketController@onholdticket')->name('onholdticket');
        Route::post('/closed/{ticket_id}','Ticket\TicketController@close')->name('client.ticketclose');

        Route::post('/ticket/{ticket_id}','Ticket\CommentsController@postComment')->name('client.comment');
        Route::post('/ticket/editcomment/{id}','Ticket\CommentsController@updateedit')->name('client.comment.edit');




        Route::get('/notification', 'DashboardController@notify')->name('client.notification');

                    Route::get('/markAsRead', function(){

                        $notify = Auth::guard('customer')->user();
                        $notify->unreadNotifications->markAsRead();

                    })->name('cust.mark');
    });

});

require __DIR__.'/auth.php';
