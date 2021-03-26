<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostTypes;
use App\Models\PostType;
use App\Models\CustomFieldMeta;
use App\Models\CategoryType;
use App\Services\PostTypesService;
use Illuminate\Http\Request;

class PostTypesController extends Controller
{
    /**
     * relations
     */
    const SUPPORTED_RELATIONS = [
        'children',
        'categoryTypes',
        'customFieldMetas',
        'customFieldMetas.children',
    ];

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
        $items = PostType::sort()->get();

        return [
            'items' => $items,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostType  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostTypes $request)
    {
        $postType = new PostType();
        $postType->fill($request->all())->save();

        $postType->categoryTypes()->sync($request->input('category_type_ids', []));
        $postType->customFieldMetas()->createMany($request->input('custom_field_metas', []));

        return $postType->fresh(self::SUPPORTED_RELATIONS);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $single = PostType::with(self::SUPPORTED_RELATIONS)->find($id);

        return [
            'post_types' => $this->postTypesService->get(true),
            'category_types' => CategoryType::orderBy('id', 'asc')->get(),
            'field_types' => \Config::get('posttype.field_types'),
            'field_validates' => \Config::get('posttype.field_validates'),
            'single' => $single,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PostType  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostTypes $request, $id)
    {
        $postType = PostType::find($id);
        $postType->fill($request->all())->save();

        $postType->categoryTypes()->sync($request->input('category_type_ids', []));
        foreach ($request->input('custom_field_metas', []) as $index => $meta) {
            if ($request->has('custom_field_metas.' . $index . '.is_delete')) {
                CustomFieldMeta::find($meta['id'])->delete();
                continue;
            }

            $postType->customFieldMetas()->updateOrCreate(
                [
                    'id' => $meta['id'] ?? null,
                ],
                [
                    'id' => $meta['id'],
                    'name' => $meta['name'],
                    'type' => $meta['type'],
                    'key' => $meta['key'],
                    'validate' => $meta['validate'],
                    'options' => $meta['options'],
                    'parent_id' => $meta['parent_id'],
                    'sort' => $meta['sort'],
                ]
            );
        }

        return $postType->fresh(self::SUPPORTED_RELATIONS);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function sort(Request $request)
    {
        $ids = [];

        foreach ($request->input() as $value) {
            $postType = PostType::find($value['id']);
            $postType->sort = $value['sort'];
            $postType->timestamps = false;
            $postType->save();

            $ids[] = $postType->id;
        }

        return PostType::with(self::SUPPORTED_RELATIONS)
            ->whereIn('id', $ids)
            ->sort()
            ->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PostType::destroy($id);

        return $id;
    }
}
