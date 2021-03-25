<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Enable;

class CategoryType extends Model
{
    use SoftDeletes;
    use Enable;

    /**
     * 初期値
     *
     * @var array
     */
    protected $attributes = [
        'name' => '',
        'slug' => '',
        'select' => 'checkbox',
        'is_multiple' => 0,
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
        'select',
        'is_multiple',
        'is_enable',
    ];

    /**
     * Category のリレーション
     *
     * @return void
     */
    public function categories()
    {
        return $this->hasMany(Category::class, 'category_type_id')->enable();
    }
}
