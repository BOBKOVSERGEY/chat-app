<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatRoomController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    // chatrooms
    Route::get('/chatrooms', [ChatRoomController::class, 'index']);
    Route::get('/chatrooms/{chatroom}', [ChatRoomController::class, 'show']);
    Route::post('/chatrooms', [ChatRoomController::class, 'store']);
    Route::post('/chatrooms/{chatroom}/users', [ChatRoomController::class, 'addUsersToChatroom']);
    Route::put('/chatrooms/{chatroom}', [ChatRoomController::class, 'update']);
    Route::delete('/chatrooms/{chatroom}', [ChatRoomController::class, 'delete']);

    // search users
    Route::get('search/users', [UserController::class, 'searchUsers']);
});


