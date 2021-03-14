<?php

namespace App\Http\Controllers\Api\Admin\Store;

use App\Http\Controllers\Controller;
use App\Services\UsersService;
use App\Services\UserRolesService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $usersService;
    private $userRolesService;

    public function __construct(UsersService $usersService, UserRolesService $userRolesService)
    {
        $this->usersService = $usersService;
        $this->userRolesService = $userRolesService;
    }

    public function index()
    {
        return [
            'user' => $this->usersService->getCurrentUser(),
            'roles' => $this->userRolesService->get(),
        ];
    }
}
