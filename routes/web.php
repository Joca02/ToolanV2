<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('login');
});

Auth::routes();
Route::middleware('guest')->group(function () {
   });

Route::middleware('auth')->group(function () {
    Route::get('/user/home', [App\Http\Controllers\UserController::class, 'index'])->name('home');
    Route::get('/logout',[LoginController::class,'logout'])->name('logout');
    Route::get('/admin/posts',[PostController::class,'getPostsFromAllUsers'])->name('allPosts');
    Route::get('/user/posts',[PostController::class,'getPostsFromFollowedUsers'])->name('followedPosts');
    Route::get("/profile/{userId}",[PostController::class,'getPostsFromUser'])->name('profilePosts');
});

Route::get('/verify/{token}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::get('/check-input-field', [App\Http\Controllers\Auth\RegisterController::class, 'checkInputField'])->name('checkInputField');
Route::post('/register',[App\Http\Controllers\Auth\RegisterController::class,'create'])->name('register');



