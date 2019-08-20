<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['user_id','user_to_id','messages'];

    protected $casts = [
        'messages' => 'array',
    ];

//    public function messages()
//    {
//        return $this->hasMany(Message::class);
//    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
