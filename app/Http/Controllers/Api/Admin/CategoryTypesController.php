<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryTypes;
use App\Models\CategoryType;
use Illuminate\Http\Request;

class CategoryTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = CategoryType::orderBy('id', 'asc')
            ->get();

        return [
            'items' => $items,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CategoryTypes  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryTypes $request)
    {
        $categoryTypes = new CategoryType();
        $categoryTypes->fill($request->all())->save();

        return $categoryTypes;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $single = CategoryType::find($id);

        return [
            'selects' => [
                [
                    'text' => 'セレクトボックス',
                    'value' => 'select',
                ],
                [
                    'text' => 'チェックボックス',
                    'value' => 'checkbox',
                ], [
                    'text' => 'ラジオボタン',
                    'value' => 'radio',
                ]
            ],
            'single' => $single,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CategoryTypes  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryTypes $request, $id)
    {
        $categoryType = CategoryType::find($id);
        $categoryType->fill($request->all())->save();

        return $categoryType;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CategoryType::destroy($id);

        return $id;
    }
}
