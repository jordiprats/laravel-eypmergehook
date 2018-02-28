<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
  public function repos()
  {
    return $this->hasMany(Repo::class);
  }
}
