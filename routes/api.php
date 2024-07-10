<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\FacilitiesController;
use App\Http\Controllers\FurnitureController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SendMailContoller;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::put('/room/{id}', [RoomController::class, 'updateExpire'])->name('room.update.expire');




Route::post('/project/store',[ProjectController::class,'store'])->name('project.store');
// Route::delete('/project/destroy/{id}/{user_id}',[ProjectController::class,'destroy'])->name('project.destroy');
Route::post('/project/predelete/{id}/{user_id}',[ProjectController::class,'predelete'])->name('project.predelete');
Route::post('/project/store/plan',[ProjectController::class,'storeplan'])->name('project.store.plan');
Route::post('/project/store/floor',[ProjectController::class,'storefloor'])->name('project.store.floor');
Route::post('/project/plan/destroy',[ProjectController::class,'destroyplan'])->name('project.destroy.plan');
Route::post('/project/floor/destroy',[ProjectController::class,'destroyfloor'])->name('project.destroy.floor');
Route::post('/project/store/fileprice',[ProjectController::class,'storeFilePrice'])->name('project.store.fileprice');
Route::get('/project/fileprice/edit/{id}',[ProjectController::class,'editFilePrice'])->name('project.edit.fileprice');
Route::post('/project/fileprice/update/{id}',[ProjectController::class,'updateFilePrice'])->name('project.update.fileprice');
Route::delete('/project/fileprice/destroy/{id}/{user_id}',[ProjectController::class,'destroyFilePrice'])->name('project.destroy.fileprice');


Route::post('/contract/project/store',[ContractController::class,'store'])->name('contract.store');
Route::delete('/contract/destroy/{id}/{user_id}',[ContractController::class,'destroy'])->name('contract.destroy');
Route::get('/contract/edit/{id}',[ContractController::class,'edit'])->name('contract.edit');
Route::get('/contract/detail/{id}',[ContractController::class,'detail'])->name('contract.detail');
Route::post('/contract/update/{id}',[ContractController::class,'update'])->name('contract.update');
Route::post('/contract/returndeposit',[ContractController::class,'returndeposit'])->name('contract.returndeposit');

Route::post('/furniture/store',[FurnitureController::class,'store'])->name('furniture.store');
Route::delete('/furniture/destroy/{id}/{user_id}',[FurnitureController::class,'destroy'])->name('furniture.destroy');
Route::get('/furniture/edit/{id}',[FurnitureController::class,'edit'])->name('furniture.edit');
Route::post('/furniture/update/{id}',[FurnitureController::class,'update'])->name('furniture.update');

Route::post('/facilities/store',[FacilitiesController::class,'store'])->name('facilities.store');
Route::delete('/facilities/destroy/{id}/{user_id}',[FacilitiesController::class,'destroy'])->name('facilities.destroy');
Route::get('/facilities/edit/{id}',[FacilitiesController::class,'edit'])->name('facilities.edit');
Route::post('/facilities/update/{id}',[FacilitiesController::class,'update'])->name('facilities.update');


Route::post('/team/store',[TeamController::class,'store'])->name('team.store');
Route::delete('/team/destroy/{id}/{user_id}',[TeamController::class,'destroy'])->name('team.destroy');
Route::get('/team/edit/{id}',[TeamController::class,'edit'])->name('team.edit');
Route::post('/team/update/{id}',[TeamController::class,'update'])->name('team.update');

Route::post('/promotion/store',[PromotionController::class,'store'])->name('promotion.store');
Route::get('/promotion/edit/{id}',[PromotionController::class,'edit'])->name('promotion.edit');
Route::post('/promotion/update/{id}',[PromotionController::class,'update'])->name('promotion.update');
Route::delete('/promotion/destroy/{id}/{user_id}',[PromotionController::class,'destroy'])->name('promotion.destroy');



Route::post('/user/store',[UserController::class,'store'])->name('user.store');
Route::get('/user/edit/{id}',[UserController::class,'edit'])->name('user.edit');
Route::post('/user/update/{id}',[UserController::class,'update'])->name('user.update');
Route::delete('/user/destroy/{id}/{user_id}',[UserController::class,'destroy'])->name('user.destroy');

Route::post('/user/partner',[UserController::class,'createPartner'])->name('partner.store');

Route::post('/room/store',[RoomController::class,'store'])->name('room.store');
Route::post('/room/cancel/{id}/{user_id}',[RoomController::class,'cancelBooking'])->name('room.cancelBooking');
Route::post('/room/prepayment',[RoomController::class,'prepayment'])->name('room.prepayment');

//ShowCustomerbooking
Route::get('/rooms/booking/customer/{id}',[BookingController::class,'getBookingCustomer'])->name('rooms.booking.customer');
Route::get('/rooms/booking/edit/{id}',[BookingController::class,'editBooking']);
Route::post('/rooms/booking/update/{id}',[BookingController::class,'updateBookingRoom']);
Route::post('/rooms/booking/cancel/{id}/{user_id}/{roomId}',[BookingController::class,'cancelBooking']);
Route::delete('/rooms/booking/destroy/{id}/{user_id}',[BookingController::class,'destroy'])->name('booking.destroy');
Route::delete('/rooms/delete/{id}/{user_id}', [RoomController::class,'deleteRoom'])->name('room.delete');
//SLA
Route::get('/booking/sla/customer',[BookingController::class,'createSLA']);

//sendMail expFile
Route::get('/sendmail/expfile',[SendMailContoller::class,'fileExp']);

// api createRoleBy VBNext
Route::post('/create-role/{user_id}',[UserController::class,'createUserRoleByAPI'])->middleware(['checkTokenApi']);
// Route::get('/users-list/{user_id}',[UserController::class,'userListAPI'])->middleware(['checkTokenApi']);
Route::get('/users-list/{user_id}',[UserController::class,'userListAPI']);
