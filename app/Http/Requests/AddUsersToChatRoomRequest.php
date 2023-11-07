<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddUsersToChatRoomRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'user_ids' => [
                'required',
                'array',
            ],
            'user_ids.*' => [
                'exists:users,id',
            ],
        ];
    }
}
