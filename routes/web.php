<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{LogbookController,AdminController};
Route::get('/',[LogbookController::class,'index'])->name('home');
Route::get('/station/{credential}',[LogbookController::class,'enroll'])->where('credential','[A-Za-z0-9]+')->name('station.enroll');
Route::post('/check-in',[LogbookController::class,'checkin'])->middleware('throttle:10,1')->name('checkin');
Route::post('/check-out',[LogbookController::class,'checkout'])->middleware('throttle:10,1')->name('checkout');
Route::get('/admin/login',[AdminController::class,'login'])->name('admin.login');
Route::get('/login',[AdminController::class,'login'])->name('login');
Route::post('/admin/login',[AdminController::class,'authenticate'])->middleware('throttle:5,1')->name('admin.authenticate');
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function(){
 Route::post('/logout',[AdminController::class,'logout'])->name('logout');
 Route::get('/',[AdminController::class,'dashboard'])->name('dashboard');
 Route::get('/sessions',[AdminController::class,'sessions'])->name('sessions');
 Route::post('/sessions/{session}/resolve',[AdminController::class,'resolve'])->name('resolve');
 Route::get('/computers',[AdminController::class,'computers'])->name('computers');
 Route::post('/computers',[AdminController::class,'addComputer'])->name('computers.add');
 Route::post('/computers/{computer}/rotate',[AdminController::class,'rotateComputer'])->name('computers.rotate');
 Route::post('/computers/{computer}/toggle',[AdminController::class,'toggleComputer'])->name('computers.toggle');
 Route::delete('/computers/{computer}',[AdminController::class,'deleteComputer'])->name('computers.delete');
 Route::get('/settings',[AdminController::class,'settings'])->name('settings');
 Route::post('/settings/{type}',[AdminController::class,'storeOption'])->name('settings.add');
 Route::post('/settings/{type}/{id}/toggle',[AdminController::class,'toggleOption'])->name('settings.toggle');
 Route::delete('/settings/{type}/{id}',[AdminController::class,'deleteOption'])->name('settings.delete');
});
