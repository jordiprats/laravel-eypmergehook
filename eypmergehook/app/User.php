<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'username', 'name', 'email', 'password', 'nickname', 'avatar'
    #'url', 'company', 'location'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token'
  ];

  public function accounts(){
      return $this->hasMany('App\LinkedSocialAccount');
  }

}
