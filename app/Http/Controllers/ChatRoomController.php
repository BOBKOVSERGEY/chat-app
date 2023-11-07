<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUsersToChatRoomRequest;
use App\Http\Requests\ChatRoomStoreRequest;
use App\Http\Requests\ChatRoomUpdateRequest;
use App\Http\Resources\ChatRoomResource;
use App\Models\ChatRoom;
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
            'chatRooms' => ChatRoomResource::collection($chatRooms)
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
            'chatroom' => $chatroom
        ], 201);

    }

    public function addUsersToChatRoom(
        AddUsersToChatRoomRequest $request,
        ChatRoom                  $chatRoom
    ): JsonResponse
    {
        $data = $request->validated();

        $chatRoom->users()->syncWithoutDetaching($data['user_ids']);

        return response()->json([
            'chatroom' => $chatRoom->load('users')
        ], 201);
    }

    public function delete(ChatRoom $chatRoom): JsonResponse
    {
        $chatRoom->delete();

        return response()->json([
            'message' => 'Chatroom deleted successfully'
        ]);
    }

    public function show(Request $request, ChatRoom $chatRoom): JsonResponse
    {
        $chatRoom = $chatRoom->load(['messages', 'users']);

        return response()->json([
            'chatroom' => new ChatRoomResource($chatRoom)
        ]);
    }

    public function update(ChatRoomUpdateRequest $request, ChatRoom $chatRoom)
    {
        $data = $request->validated();
        $data['user_ids'][] = auth()->user()->id;

        $chatRoom->users()->sync($data['user_ids']);

        $chatRoom->update([
            'name' => $data['room_name']
        ]);

        $chatRoom = $chatRoom->load(['users']);

        return response()->json([
            'chatroom' => new ChatRoomResource($chatRoom)
        ]);
    }
}
