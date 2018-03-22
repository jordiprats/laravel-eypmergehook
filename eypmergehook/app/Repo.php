<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repo extends Model
{
  // everything is mass assignable
  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function organization()
  {
    return $this->belongsTo(User::class);
  }

  public function reporeleases()
  {
    return $this->hasMany(RepoRelease::class);
  }
}
