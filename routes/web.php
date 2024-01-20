<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// all listing
Route::get('/', [ListingController::class,'index']);

//create listing page
Route::get('/createPage',[ListingController::class,'create'])->middleware('auth');

// store listing new datas
Route::post('/create/data',[ListingController::class,'newDataStore'])->name('create#data');

// single listing
Route::get('/listing/{listing}',[ListingController::class,'show'])->name('show#listing');

// single listing edit
Route::get('/listing/{listing}/edit',[ListingController::class,'edit'])->middleware('auth');

// single listing update
Route::put('/listing/{listing}',[ListingController::class,'update']);

// single listing delete
Route::delete('/listing/{listing}',[ListingController::class,'delete'])->middleware('auth');

// manage listing
Route::get('/manage/listing',[ListingController::class,'manage'])->middleware('auth');

// show register create form
Route::get('/register',[UserController::class,'create'])->name('register#create')->middleware('guest');

// register data store
Route::post('/users',[UserController::class,'userData'])->name('user#data');

//user logout
Route::post('/logout',[UserController::class,'logout'])->name('user#logout')->middleware('auth');

//user login page
Route::get('/login',[UserController::class,'login'])->name('login')->middleware('guest');

// login user store
Route::post('/users/authenticate',[UserController::class,'userAuth'])->name('user#auth');