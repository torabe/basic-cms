<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * 投稿タイプ
     *
     * @var array
     */
    protected $postType;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('posttype');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->postType = $request->post_type;

        $posts = Post::where('post_type_id', $this->postType->id);
        if (Auth::guard('admin')->user() && $request->has('preview')) {
            $posts->enable()
                ->publish();
        }
        $posts->sort($this->postType->is_sortable);
        $posts = $request->post_type->per_page > 0 ? $posts->paginate($request->post_type->per_page) : $posts->get();

        return $posts;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $slug, string $postSlug)
    {
        //
    }
}
