<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class UsersService
{
  public function getCurrentUser()
  {
    Auth::guard('admin');
    if (Auth::guard('admin')->user()) {
      Auth::guard('admin')->user()->role;
      Auth::guard('admin')->user()->permissions;
      foreach (Auth::guard('admin')->user()->permissions as $permission) {
        $permission->postType;
      }
    }
    return Auth::guard('admin')->user();
  }
}
