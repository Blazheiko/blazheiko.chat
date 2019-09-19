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
    protected $fillable = ['message','photo_url','is_photo','conversation_id','is_video','video_descr','user_to'];

    /**
     * A message belong to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    protected $casts = [
        'video_descr' => 'object',
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
