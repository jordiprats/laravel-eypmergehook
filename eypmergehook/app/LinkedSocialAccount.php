<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkedSocialAccount extends Model
{
  // $table->string('provider')->nullable();
  // $table->string('token')->nullable();
  // $table->string('refresh_token')->nullable();
  // $table->string('expires_in')->nullable();
  // $table->string('token_secret')->nullable();
  protected $fillable = [ 'user_id', 'provider', 'token', 'refresh_token',
                          'expires_in', 'token_secret' ];

  public function user()
  {
    return $this->belongsTo('App\User');
  }
}
