<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserFriendController;

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

Route::get('/', function () {
    return redirect('/home');;
});

Auth::routes(['verify' => true]);

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/users', [HomeController::class, 'getUsers'])->name('users.getusers');

Route::get('/friends', [UserFriendController::class, 'index'])->name('user.friends');
Route::get('/friendList', [UserFriendController::class, 'getFriendList'])->name('user.friendList');
Route::post('/invite/{id}', [UserFriendController::class, 'inviteFriend'])->name('user.invite');
Route::get('/confirm/{id}', [UserFriendController::class, 'confirm'])->name('invitation.confirm');
Route::delete('/remove/{id}',[UserFriendController::class, 'delete'])->name('user.remove');