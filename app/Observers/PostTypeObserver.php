<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\PostType;

class PostTypeObserver
{
    /**
     * Handle the post type "created" event.
     *
     * @param  \App\Models\PostType  $postType
     * @return void
     */
    public function created(PostType $postType)
    {
        $admins = Admin::get();
        foreach ($admins as $admin) {
            $admin->permissions()->updateOrCreate(
                [
                    'admin_id' => $admin->id,
                    'post_type_id' => $postType->id,
                ],
                [
                    'admin_id' => $admin->id,
                    'post_type_id' => $postType->id,
                    'permission' => 1,
                ]
            );
        }
    }

    /**
     * Handle the post type "updated" event.
     *
     * @param  \App\Models\PostType  $postType
     * @return void
     */
    public function updated(PostType $postType)
    {
        $admins = Admin::get();
        foreach ($admins as $admin) {
            $admin->permissions()->updateOrCreate(
                [
                    'admin_id' => $admin->id,
                    'post_type_id' => $postType->id,
                ],
                [
                    'admin_id' => $admin->id,
                    'post_type_id' => $postType->id,
                    'permission' => 1,
                ]
            );
        }
    }

    /**
     * Handle the post type "deleted" event.
     *
     * @param  \App\Models\PostType  $postType
     * @return void
     */
    public function deleted(PostType $postType)
    {
        // $postType->categoryTypes->detach();
        // foreach ($postType->customFieldMetas as $meta) {
        //     $meta->delete();
        // }
        // foreach ($postType->permissions as $permission) {
        //     $permission->delete();
        // }
    }

    /**
     * Handle the post type "restored" event.
     *
     * @param  \App\Models\PostType  $postType
     * @return void
     */
    public function restored(PostType $postType)
    {
        //
    }

    /**
     * Handle the post type "force deleted" event.
     *
     * @param  \App\Models\PostType  $postType
     * @return void
     */
    public function forceDeleted(PostType $postType)
    {
        //
    }
}
