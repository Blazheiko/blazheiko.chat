<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferVideoChat extends Model
{
    protected $fillable = [
        'offer', 'answer','conversation_id'
    ];
    protected $casts = [
        'offer' => 'array',
        'answer' => 'array',
    ];
    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }
}
