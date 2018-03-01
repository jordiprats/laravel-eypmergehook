<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repo extends Model
{
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function organization()
  {
    return $this->belongsTo(User::class);
  }
}
