<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\FacilitiesController;
use App\Http\Controllers\FurnitureController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RentralController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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



Route::get('rWGWxTKnAPQfShWUuxBuhPdE0a6kUe6eh5wEytp6td3LVLGqwRGFDSYBjpnmCe724CS6Dd33zZTt7WdKD55qVkWaYZ/{id}&{token}',[CustomAuthController::class,'AllowLoginConnect']);

//login by agent system
Route::get('/agent/{id}/{role_id}',[UserController::class,'createUserByAgentSystem']);

Route::middleware(['isAuth'])->group(function () {
    Route::get('/change-password',[CustomAuthController::class,'changePassword'])->name('change.password');
    Route::post('/change-password',[CustomAuthController::class,'updatePassword'])->name('update.password');
});

Route::middleware(['alreadyLogin'])->group(function () {

    Route::get('/login',[CustomAuthController::class,'login']);
    Route::post('/login/auth',[CustomAuthController::class,'loginUser'])->name('loginUser');

});


Route::middleware(['isLogin'])->group(function () {


    Route::get('/', [MainController::class, 'index'])->name('main');

    Route::get('/projects', [ProjectController::class, 'index'])->name('project');
    Route::get('/projects/detail/{id}',[ProjectController::class,'detail'])->name('project.detail');
    Route::get('/projects/edit/{id}',[ProjectController::class,'edit'])->name('project.edit');
    Route::post('/projects/update-info',[ProjectController::class,'updateinfo'])->name('project.update.info');
    Route::post('/projects/update-plan',[ProjectController::class,'updateplan'])->name('project.update.plan');
    Route::post('/projects/update-floor',[ProjectController::class,'updatefloor'])->name('project.update.floor');


    Route::get('/rooms', [RoomController::class, 'index'])->name('room');
    Route::get('/rooms/edit/{id}', [RoomController::class, 'edit'])->name('room.edit');
    Route::post('/rooms/update', [RoomController::class, 'update'])->name('room.update');
    Route::post('/rooms/search', [RoomController::class, 'search'])->name('room.search');
    Route::get('/rooms/detail/{id}', [RoomController::class, 'detail'])->name('room.detail');

    //จอง
    Route::get('/rooms/booking/{id}', [BookingController::class,'getRoom'])->name('room.booking');
    Route::post('/rooms/booking', [BookingController::class,'bookingRoom'])->name('room.booking.insert');

    Route::get('/rooms/partner', [RoomController::class, 'index'])->name('room.partner');
    Route::post('/rooms/partner/search', [RoomController::class, 'searchPartner'])->name('room.search.partner');
    Route::get('/rooms/partner/edit/{id}', [RoomController::class, 'editPartner'])->name('room.edit.partner');
    Route::post('/rooms/partner/update', [RoomController::class, 'updatePartner'])->name('room.update.partner');

    Route::post('/rooms/importexcel', [RoomController::class, 'importexcel'])->name('room.importexcel');
    Route::post('/rooms/updateexcel', [RoomController::class, 'updateexcel'])->name('room.updateexcel');
    Route::post('/rooms/deleteSelected', [RoomController::class,'deleteSelected'])->name('room.delete.selected');
    Route::post('/rooms/cancelSelected', [RoomController::class,'cancelSelected'])->name('room.cancel.selected');


    Route::get('/rooms/approve', [RoomController::class,'approve'])->name('room.approve');
    Route::post('/rooms/approve/search', [RoomController::class,'searchApprove'])->name('room.approve.search');
    Route::post('/rooms/approve/update', [RoomController::class,'update_approve'])->name('room.approve.update');
    /////


    Route::get('/getplans/{id}',[RoomController::class,'getPlan'])->name('getplans');
    Route::get('/getcustomers',[RoomController::class,'getCustomers'])->name('getcustomer');

    Route::get('/reports', [ReportController::class, 'index'])->name('report');
    Route::post('/reports/search', [ReportController::class, 'search'])->name('report.search');
    Route::get('/reports/search/in', [ReportController::class, 'searchIn'])->name('report.search.in');
    Route::get('/reports/search/out', [ReportController::class, 'searchOut'])->name('report.search.out');
    Route::get('/promotions', [PromotionController::class, 'index'])->name('promotion');
    Route::get('/furnitures', [FurnitureController::class, 'index'])->name('furniture');
    Route::get('/facilities', [FacilitiesController::class, 'index'])->name('facilities');
    Route::get('/team', [TeamController::class, 'index'])->name('team');
    Route::get('/users',[UserController::class,'index'])->name('user');


    Route::get('/rental',[RentralController::class,'index'])->name('rentral');
    Route::post('/rental/search',[RentralController::class,'search'])->name('rentral.search');

    Route::get('/logout/auth',[CustomAuthController::class,'logoutUser'])->name('logoutUser');




});

