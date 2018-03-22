<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
  // everything is mass assignable
  protected $guarded = [];

  public function repos()
  {
    return $this->hasMany(Repo::class);
  }

  public function users()
  {
      return $this->belongsToMany(User::class)->withTimestamps();;
  }

}
