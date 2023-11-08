<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chatroom extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class
        );
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->with('user');
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latest();
    }
}
