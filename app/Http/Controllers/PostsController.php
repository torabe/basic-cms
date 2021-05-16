<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostsController extends Controller
{
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
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $slug)
    {
        $this->postType = $request->post_type;

        $posts = Post::where('post_type_id', $this->postType->id);
        if (!Auth::guard('admin')->user() || !$request->has('preview')) {
            $posts->enable();
        }
        $posts->sort($this->postType->is_sortable);

        $posts = $this->postType->per_page >= 0 ? $posts->paginate($this->postType->per_page) : $posts->get();

        return view('posts.index', [
            'postType' => $this->postType,
            'posts' => $posts,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  string  $postSlug
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug, $postSlug)
    {
        $this->postType = $request->post_type;

        $post = Post::where('post_type_id', $this->postType->id)->where('slug', $postSlug);
        if (!Auth::guard('admin')->user() || !$request->has('preview')) {
            $post->enable()->publish();
        }
        $post = $post->firstOrFail();
        $post->fields = $post->customFields->pluck('value', 'meta.key');

        // foreach ($post->customFields as $field) {
        //     $post->{$field->meta->key} = $field->value;
        // }

        return view('posts.show', [
            'postType' => $this->postType,
            'post' => $post,
        ]);
    }
}
