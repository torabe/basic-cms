<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category as ChildrenCategory;
use App\Models\Category as ParentCategory;
use App\Models\Traits\Enable;
use App\Models\Traits\Sortable;

class Category extends Model
{
    use SoftDeletes;
    use Enable;
    use Sortable;

    /**
     * 初期値
     *
     * @var array
     */
    protected $attributes = [
        'category_type_id' => null,
        'name' => '',
        'slug' => '',
        'parent_id' => null,
        'sort' => null,
        'is_enable' => 0,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_type_id',
        'name',
        'slug',
        'parent_id',
        'sort',
        'is_enable',
    ];

    /**
     * CategoryType のリレーション
     * @return mixed
     */
    public function categoryType()
    {
        return $this->belongsTo(CategoryType::class, 'category_type_id', 'id');
    }

    /**
     * 子カテゴリのリレーション
     *
     * @return void
     */
    public function children()
    {
        return $this->hasMany(ChildrenCategory::class, 'parent_id')->sort();
    }

    /**
     * 親カテゴリのリレーション
     *
     * @return void
     */
    public function parent()
    {
        return $this->belongsTo(ParentCategory::class, 'parent_id', 'id');
    }
}
