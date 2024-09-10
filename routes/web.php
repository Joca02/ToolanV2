<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
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

Auth::routes();



Route::middleware('user')->group(function () {
    Route::get('/user/home', [App\Http\Controllers\UserController::class, 'index'])->name('home');
    Route::get('/user/profile', [App\Http\Controllers\UserController::class, 'showProfile'])->name('profile');
    Route::get('/user/posts',[PostController::class,'getPostsFromFollowedUsers'])->name('followedPosts');
    Route::post('/user/follow',[UserController::class,'followAction'])->name('followAction');
    Route::get('/user/follow',[UserController::class,'checkFollowingStatus'])->name('checkFollowingStatus');
    Route::post('/user/like',[UserController::class,'likeAction'])->name('likeAction');
    Route::post('/user/comment',[UserController::class,'postComment'])->name('postComment');
    Route::post('/user/post',[PostController::class,'addPost'])->name('addPost');
    Route::get('/user/edit_profile',[UserController::class,'editProfile'])->name('editProfile');
    Route::post('/user/edit_profile',[UserController::class,'confirmEdit'])->name('confirmEdit');
    Route::delete('/user/post',[PostController::class,'deletePost'])->name('deletePost');
    Route::post('/user/deactivate-account',[UserController::class,'deactivateAccount'])->name('deactivateAccount');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile/stats',[UserController::class,'getStats'])->name('getStats');
    Route::get('/logout',[LoginController::class,'logout'])->name('logout');
    Route::get('/filter-users',[UserController::class,'filterUsers'])->name('filterUsers');
    Route::get("/profile/posts",[PostController::class,'getPostsFromUser'])->name('profilePosts');
    Route::get('/user/like',[UserController::class,'getLikes'])->name('getLikes');
    Route::get('/user/comment',[UserController::class,'getComments'])->name('getComments');
    Route::get('/followers-info',[UserController::class,'getFollowersInfo'])->name('getFollowersInfo');
    Route::get('/following-info',[UserController::class,'getFollowingInfo'])->name('getFollowingInfo');
});

Route::middleware('admin')->group(function () {
    Route::get('/admin/posts',[PostController::class,'getPostsFromAllUsers'])->name('allPosts');
    Route::get('/admin/home',[AdminController::class,'showHomePage'])->name('adminHome');
    Route::get('/admin/profile',[AdminController::class,'showProfile'])->name('adminProfile');
    Route::get('/admin/user-ban-status',[AdminController::class,'getUserBanStatus'])->name('userBanStatus');
    Route::post('/admin/ban-user',[AdminController::class,'banUser'])->name('banUser');
    Route::post('/admin/unban-user',[AdminController::class,'unBanUser'])->name('unBanUser');
    Route::get('/admin/banned-users',[AdminController::class,'getBannedUsers'])->name('bannedUsers');
    Route::get('/admin/statistics-page',[AdminController::class,'getStatisticsPage'])->name('statisticsPage');
    Route::get('/admin/statistics',[AdminController::class,'getStatistics'])->name('getStatistics');
});

//dont need authentication
Route::get('/verify/{token}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::get('/check-input-field', [App\Http\Controllers\Auth\RegisterController::class, 'checkInputField'])->name('checkInputField');
Route::post('/register',[App\Http\Controllers\Auth\RegisterController::class,'create'])->name('register');

Route::post('/password-reset', [ResetPasswordController::class, 'requestPasswordReset'])->name('password.reset.link');

Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');

Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');

Route::get('/reactivate-account',[UserController::class,'reactivateAccount'])->name('reactivateAccount');

Route::get('/login',[LoginController::class,'showLoginForm'])->name('login');
