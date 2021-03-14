<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PostType as Children;
use App\Models\Traits\Enable;
use App\Models\Traits\Sortable;

class PostType extends Model
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
        'name' => '',
        'slug' => '',
        'description' => '',
        'parent_id' => null,
        'admin_icon' => '',
        'per_page' => -1,
        'is_sortable' => 0,
        'is_customize' => 0,
        'sort' => null,
        'is_enable' => 0,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'admin_icon',
        'per_page',
        'is_sortable',
        'is_customize',
        'sort',
        'is_enable',
    ];

    /**
     * 子投稿タイプのリレーション
     *
     * @return void
     */
    public function children()
    {
        return $this->hasMany(Children::class, 'parent_id')->orderBy('sort', 'asc');
    }

    /**
     * カテゴリタイプのリレーション
     *
     * @return void
     */
    public function categoryTypes()
    {
        return $this->belongsToMany(CategoryType::class);
    }

    /**
     * カスタムフィールドメタ情報のリレーション
     *
     * @return void
     */
    public function customFieldMetas()
    {
        return $this->hasMany(CustomFieldMeta::class, 'post_type_id')->orderBy('sort', 'asc');
    }

    /**
     * AdminPermission のリレーション
     *
     * @return void
     */
    public function permissions()
    {
        return $this->hasMany(AdminPermission::class, 'post_type_id');
    }

    /**
     * Post のリレーション
     *
     * @return void
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'post_type_id');
    }
}
