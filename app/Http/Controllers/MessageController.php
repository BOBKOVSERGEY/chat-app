<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageStoreRequest;
use App\Models\Chatroom;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(MessageStoreRequest $request, Chatroom $chatroom)
    {

    }
}
