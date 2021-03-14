<?php

namespace App\Models\Traits;

trait Enable
{
  public function scopeEnable($query)
  {
    return $query->where('is_enable', 1);
  }

  public function scopeDisable($query)
  {
    return $query->where('is_enable', 0);
  }
}
