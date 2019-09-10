<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['message','photo_url','is_photo','conversation_id','is_video','sdp','ice'];

    /**
     * A message belong to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    protected $casts = [
        'sdp' => 'object',
        'ice' => 'object',
    ];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }

}
