<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRoomUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'room_name' => [
                'required',
                'max:255',
                'unique:chatrooms,name,'.$this->chatRoom->id,
            ],
            'user_ids' => [
                'required',
                'array',
                'exists:users,id',
            ]
        ];
    }
}
