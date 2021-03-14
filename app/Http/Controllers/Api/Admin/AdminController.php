<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Http\Requests\Admins;

class AdminController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Admin::with([
            'role',
            'permissions' => function ($query) {
                $query->has('postType');
            },
        ])->get();

        return [
            'items' => $items,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admins  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Admins $request)
    {
        $admin = new Admin();

        $admin->fill($request->all())->save();

        $admin->permissions()->createMany($request->permissions);

        return $admin->fresh([
            'role',
            'permissions' => function ($query) {
                $query->has('postType');
            },
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $single = Admin::with([
            'role',
            'permissions' => function ($query) {
                $query->has('postType');
            },
        ])->find($id);

        return ['single' => $single,];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PostType  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Admins $request, $id)
    {
        $admin = Admin::find($id);
        $admin->fill($request->all())->save();

        foreach ($request->permissions as $permission) {
            $admin->permissions()->updateOrCreate(
                ['id' => $permission['id'] ?? null],
                $permission
            );
        }

        return $admin->fresh([
            'role',
            'permissions' => function ($query) {
                $query->has('postType');
            },
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Admin::destroy($id);

        return $id;
    }
}
