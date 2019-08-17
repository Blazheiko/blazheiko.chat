<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Message;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'gender','avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function portfolio()
    {
        return $this->hasOne('App\Portfolio');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function likes()
    {
        return $this->hasMany('App\Like');
    }
    public function posts()
    {
        return $this->hasMany('App\Post');
    }
    public function isAdmin()
    {
        return $this->isAdmin; // поле is_admin в таблице users
    }
    public function messages()
    {
        return $this->hasMany('App\Message');
    }
    public function conversations()
    {
        return $this->hasMany(Message::class);
    }

}
