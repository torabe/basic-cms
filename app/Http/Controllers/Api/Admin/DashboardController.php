<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PostTypesService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * @var PostTypesService
     */
    private $postTypesService;

    public function __construct(PostTypesService $postTypesService)
    {
        $this->postTypesService = $postTypesService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postTypes = $this->postTypesService->get(true);
        foreach ($postTypes as $postType) {
            $postType->posts = $postType->posts()->orderBy('updated_at', 'desc')->take(5)->get();
        }

        return [
            'postTypes' => $postTypes,
        ];
    }
}
