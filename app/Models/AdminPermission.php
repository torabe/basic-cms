<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminPermission extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id',
        'post_type_id',
        'permission'
    ];

    /**
     * Admin のリレーション
     *
     * @return void
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * PostType のリレーション
     *
     * @return void
     */
    public function postType()
    {
        return $this->belongsTo(PostType::class, 'post_type_id')->enable();
    }
}
