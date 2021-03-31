<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts;
use App\Models\Post;
use App\Models\CustomField;
use App\Traits\Uploadable;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PostController extends Controller
{
    use Uploadable;

    /**
     * 画像アップロード先
     */
    const UPLOAD_PATH = '/public/';

    /**
     * relations
     */
    const SUPPORTED_RELATIONS = [
        'postType',
        'author',
        'categories',
        'categories.categoryType',
        'customFields',
        'customFields.meta',
    ];

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
        $date = Carbon::now();
        $this->setUploadPath(Self::UPLOAD_PATH . $date->format('Y/m'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->postType = $request->post_type;

        $items = Post::with(self::SUPPORTED_RELATIONS)
            ->where('post_type_id', $this->postType->id)
            ->sort($this->postType->is_sortable)
            ->get();

        return [
            'post_type' => $this->postType,
            'items' => $items,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Posts  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Posts $request)
    {
        try {
            $post = new Post();
            $post->fill($request->all())->save();

            $categories = [];
            foreach ($request->input('categories', []) as $cates) {
                $categories = array_merge($categories, $cates);
            }
            $post->categories()->sync($categories);

            foreach ($request->input('custom_fields', []) as $index => $customField) {
                if ($request->hasFile('custom_fields.' . $index . '.value')) {
                    // $this->setUploadPath(Self::UPLOAD_PATH . $post->postType->slug);
                    $value = $this->fileUpload($request->file('custom_fields.' . $index . '.value'));
                    $post->customFields()->create(array_merge($customField, ['value' => $value]));
                    continue;
                }
                $post->customFields()->create($customField);
            }

            return $post->fresh(self::SUPPORTED_RELATIONS);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug, $id)
    {
        $this->postType = $request->post_type;

        $single = Post::with(self::SUPPORTED_RELATIONS)
            ->where('post_type_id', $this->postType->id)
            ->find($id);

        return [
            'post_type' => $this->postType,
            'single' => $single,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Posts  $request
     * @param  string  $slug
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Posts $request, $slug, $id)
    {
        try {
            $post = Post::find($id);
            $post->fill($request->all())->save();

            $categories = [];
            foreach ($request->input('categories', []) as $cates) {
                $categories = array_merge($categories, $cates);
            }
            $post->categories()->sync($categories);

            foreach ($request->input('custom_fields', []) as $index => $customField) {
                if ($request->hasFile('custom_fields.' . $index . '.value')) {
                    if ($request->has('custom_fields.' . $index . '.id')) {
                        $CustomField = CustomField::find($request->input('custom_fields.' . $index . '.id'));
                        $this->fileDelete($CustomField->value);
                    }

                    // $this->setUploadPath(Self::UPLOAD_PATH . $post->postType->slug);
                    $value = $this->fileUpload($request->file('custom_fields.' . $index . '.value'));
                    $post->customFields()->updateOrCreate(
                        ['id' => $customField['id'] ?? null],
                        array_merge($customField, ['value' => $value])
                    );
                    continue;
                }
                $post->customFields()->updateOrCreate(['id' => $customField['id'] ?? null], $customField);
            }

            return $post->fresh(self::SUPPORTED_RELATIONS);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function sort(Request $request)
    {
        $this->postType = $request->post_type;
        $ids = [];

        foreach ($request->input() as $value) {
            $post = Post::find($value['id']);
            $post->sort = $value['sort'];
            $post->timestamps = false;
            $post->save();

            $ids[] = $post->id;
        }

        return Post::with(self::SUPPORTED_RELATIONS)
            ->whereIn('id', $ids)
            ->sort($this->postType->is_sortable)
            ->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $slug
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug, $id)
    {
        Post::destroy($id);

        return $id;
    }
}
