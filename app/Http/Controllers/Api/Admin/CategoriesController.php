<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('categorytype');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = Category::with(['children'])
            ->where('category_type_id', $request->category_type->id)
            ->whereNull('parent_id')
            ->sort()
            ->get();

        return [
            'category_type' => $request->category_type,
            'items' => $items,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Categories  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Categories $request)
    {
        $category = new Category();
        $category->fill($request->all())->save();

        return $category;
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug, $id)
    {
        $single = Category::with(['CategoryType'])->find($id);
        $categories = Category::where('category_type_id', $request->category_type->id)
            ->where('id', '!=', $id)
            ->whereNull('parent_id')
            ->sort()
            ->get();

        return [
            'category_type' => $request->category_type,
            'categories' => $categories,
            'single' => $single,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Categories  $request
     * @param  string  $slug
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Categories $request, $slug, $id)
    {
        $category = Category::find($id);
        $category->fill($request->all())->save();

        return $category->fresh(['children']);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function sort(Request $request)
    {
        foreach ($request->input() as $value) {
            $category = Category::find($value['id']);
            $category->sort = $value['sort'];
            $category->timestamps = false;
            $category->save();
        }

        return Category::with(['children'])
            ->where('category_type_id', $request->category_type->id)
            ->whereNull('parent_id')
            ->sort()
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
        Category::destroy($id);

        return $id;
    }
}
