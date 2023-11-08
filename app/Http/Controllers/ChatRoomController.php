<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUsersToChatRoomRequest;
use App\Http\Requests\ChatRoomStoreRequest;
use App\Http\Requests\ChatRoomUpdateRequest;
use App\Http\Resources\ChatRoomResource;
use App\Models\Chatroom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatRoomController extends Controller
{
    public function index(): JsonResponse
    {
        $chatRooms = auth()->user()
            ->chatRooms()
            ->with(['lastMessage', 'users'])
            ->get();

        return response()->json([
            'chatrooms' => ChatRoomResource::collection($chatRooms)
        ], 201);
    }

    public function store(ChatRoomStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $chatroom = auth()->user()
            ->chatRooms()
            ->create([
                'name' => $data['room_name']
            ]);

        return response()->json([
            'chatroom' => new ChatRoomResource($chatroom->load(['users']))
        ], 201);

    }

    public function addUsersToChatRoom(
        AddUsersToChatRoomRequest $request,
        Chatroom $chatroom
    ): JsonResponse
    {
        $data = $request->validated();

        $chatroom->users()->syncWithoutDetaching($data['user_ids']);

        return response()->json([
            'chatroom' => $chatroom->load('users')
        ], 201);
    }

    public function delete(Chatroom $chatroom): JsonResponse
    {
        $chatroom->delete();

        return response()->json([
            'message' => 'Chatroom deleted successfully'
        ]);
    }

    public function show(Request $request, Chatroom $chatroom): JsonResponse
    {
        $chatRoom = $chatroom->load(['messages', 'users']);

        return response()->json([
            'chatroom' => new ChatRoomResource($chatRoom)
        ]);
    }

    public function update(ChatRoomUpdateRequest $request, Chatroom $chatroom): JsonResponse
    {
        $data = $request->validated();
        $data['user_ids'][] = auth()->user()->id;

        $chatroom->users()->sync($data['user_ids']);

        $chatroom->update([
            'name' => $data['room_name']
        ]);

        $chatRoom = $chatroom->load(['users']);

        return response()->json([
            'chatroom' => new ChatRoomResource($chatRoom)
        ]);
    }
}
