<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['count_read','count_read_to','counter','user_to_id','user_id','last_message_date'];

    public function messages()
    {
        return $this->hasMany('App\Message');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function offerVideoChat()
    {
        return $this->hasMany('App\OfferVideoChat');
    }


}
