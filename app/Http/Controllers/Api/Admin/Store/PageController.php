<?php

namespace App\Http\Controllers\Api\Admin\Store;

use App\Http\Controllers\Controller;
use App\Services\PostTypesService;

class PageController extends Controller
{
    private $postTypesService;

    public function __construct(PostTypesService $postTypesService)
    {
        $this->postTypesService = $postTypesService;
    }

    public function index()
    {
        return [
            'postTypes' => $this->postTypesService->get(true),
        ];
    }
}
