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

  public function grants()
  {
    return $this->belongsToMany(User::class)
      ->as('grants')
      ->withPivot('admin', 'push', 'pull')
      ->withTimestamps();
  }

  public function admin_users()
  {
    return $this->belongsToMany(User::class)
      ->as('grants')
      ->wherePivot('admin', true);
  }

  public function push_users()
  {
    return $this->belongsToMany(User::class)
      ->as('grants')
      ->wherePivot('push', true);
  }

  public function pull_users()
  {
    return $this->belongsToMany(User::class)
      ->as('grants')
      ->wherePivot('pull', true);
  }
}
