<?php

namespace App\Http\Controllers;

use App\Events\MessageSentEvent;
use App\Events\UserIsTypingEvent;
use App\Events\UserStoppedTypingEvent;
use App\Http\Requests\MessageStoreRequest;
use App\Http\Resources\MessageResource;
use App\Models\Chatroom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function store(MessageStoreRequest $request, Chatroom $chatroom): JsonResponse
    {
        $data = $request->validated();

        if($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('chatroom_images', $imageName);

            $storageUrl = Storage::url('chatroom_images/' . $imageName);

            $message = $chatroom->messages()
                ->create([
                'content' => $data['content'],
                'image_url' => $storageUrl,
                'user_id' => auth()->user()->id
            ]);

            broadcast( new MessageSentEvent(
                new MessageResource($message->load('user')))
            )->toOthers();

            return response()->json([
                'message' => new MessageResource($message->load('user'))
            ]);
        }

        $message = $chatroom->messages()->create([
            'user_id' => $request->user()->id,
            'content' => $data['content']
        ]);

        broadcast(new MessageSentEvent(
            new MessageResource($message->load('user')))
        )->toOthers();

        return response()->json([
            'message' => new MessageResource($message->load('user'))
        ], 200);
    }

    public function userIsTyping(Request $request, Chatroom $chatroom): void
    {
        broadcast(
            new UserIsTypingEvent($request->user()->name, $chatroom->id)
        )->toOthers();
    }

    public function userStoppedTyping(Request $request, Chatroom $chatroom): void
    {
        broadcast(
            new UserStoppedTypingEvent($request->user()->name, $chatroom->id)
        )->toOthers();
    }
}
