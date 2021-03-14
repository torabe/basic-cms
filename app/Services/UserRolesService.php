<?php

namespace App\Services;

use App\Models\AdminRole;

class UserRolesService
{
  public function get()
  {
    $adminRole = new AdminRole;
    return $adminRole->enable()->sort()->get();
  }
}
