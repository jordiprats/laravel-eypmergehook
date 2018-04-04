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
    'username', 'name', 'email', 'password', 'nickname', 'avatar',
    'telegram_notifications', 'telegram_chatid',  'githubrepos_updated_on'
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

  public function repos()
  {
    return $this->hasMany(Repo::class);
  }

  public function grants()
  {
    return $this->belongsToMany(Repo::class)
      ->as('grants')
      ->withPivot('admin', 'push', 'pull')
      ->withTimestamps();
  }

  public function organizations()
  {
    return $this->belongsToMany(Organization::class)->withTimestamps();;
  }

  public function linkedsocialaccounts()
  {
    return $this->hasMany(LinkedSocialAccount::class);
  }

  public function platforms()
  {
    return $this->hasMany(Platform::class);
  }

}
