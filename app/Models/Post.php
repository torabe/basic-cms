<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Enable;

class Post extends Model
{
    use SoftDeletes;
    use Enable;

    /**
     * 初期値
     *
     * @var array
     */
    protected $attributes = [
        'post_type_id' => null,
        'publish_at' => null,
        'unpublish_at' => null,
        'slug' => '',
        'title' => '',
        'description' => '',
        'admin_id' => null,
        'sort' => null,
        'is_enable' => 0,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_type_id',
        'publish_at',
        'unpublish_at',
        'slug',
        'title',
        'description',
        'admin_id',
        'sort',
        'is_enable',
    ];

    /**
     *
     * @var array
     */
    protected $dates  = [
        'publish_at',
        'unpublish_at'
    ];

    /**
     * PostType のリレーション
     * @return mixed
     */
    public function postType()
    {
        return $this->belongsTo(PostType::class, 'post_type_id', 'id');
    }

    /**
     * Admin のリレーション
     * @return mixed
     */
    public function author()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    /**
     * Cateogries のリレーション
     *
     * @return void
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * CustomField のリレーション
     *
     * @return void
     */
    public function customFields()
    {
        return $this->hasMany(CustomField::class);
    }

    public function scopePublish($query)
    {
        return $query->where(function ($q) {
            $q->where('publish_at', '<', now())
                ->where(function ($q) {
                    $q->where('unpublish_at', '>=', now())
                        ->orWhereNull('unpublish_at');
                });
        });
    }

    public function scopeSort($query, $is_sortable)
    {
        if ($is_sortable) {
            return $query
                ->orderBy('sort', 'asc')
                ->orderBy('id', 'desc');
        }

        return $query
            ->orderBy('publish_at', 'desc')
            ->orderBy('unpublish_at', 'desc')
            ->orderBy('id', 'desc');
    }
}
