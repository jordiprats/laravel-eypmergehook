<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
  // everything is mass assignable
  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
