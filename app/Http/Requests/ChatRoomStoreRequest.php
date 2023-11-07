<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRoomStoreRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'room_name' => [
                'required',
                'unique:chatrooms,name',
                'max:255',
            ]
        ];
    }
}
