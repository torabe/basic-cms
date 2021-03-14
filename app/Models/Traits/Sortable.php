<?php

namespace App\Models\Traits;

trait Sortable
{
  public function scopeSort($query)
  {
    return $query->orderBy('sort', 'asc')->orderBy('id', 'desc');
  }
}
