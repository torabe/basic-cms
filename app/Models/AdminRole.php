<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Enable;
use App\Models\Traits\Sortable;

class AdminRole extends Model
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
        'identifier' => '',
        'is_enable' => 0,
        'sort' => null,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'identifier',
        'is_enable',
        'sort'
    ];
}
