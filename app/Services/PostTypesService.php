<?php

namespace App\Services;

use App\Models\PostType;

class PostTypesService
{
    public function get($withRelations = false)
    {
        return PostType::with($withRelations ? [
            'children' => function ($query) {
                $query->enable();
            },
            'categoryTypes' => function ($query) {
                $query->enable();
            },
            'categoryTypes.categories' => function ($query) {
                $query->enable()->whereNull('parent_id');
            },
            'categoryTypes.categories.children' => function ($query) {
                $query->enable();
            },
            'customFieldMetas',
            'customFieldMetas.children',
        ] : [])->enable()->sort()->get();
    }
}
