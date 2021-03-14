<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use App\Models\Traits\Enable;
use App\Models\Traits\Sortable;

/**
 * 管理ユーザー class
 */
class Admin extends Authenticatable
{
    use Notifiable;
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
        'login_id' => '',
        'role_id' => 0,
        'email' => '',
        'password' => '',
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
        'login_id',
        'role_id',
        'email',
        'password',
        'is_enable',
        'sort'
    ];

    /**
     * 配列に含めない属性
     *
     * @var array
     */
    protected $hidden = ['password', 'created_at', 'updated_at'];


    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * AdminRole のリレーション
     *
     * @return void
     */
    public function role()
    {
        return $this->belongsTo(AdminRole::class, 'role_id');
    }

    /**
     * AdminPermission のリレーション
     *
     * @return void
     */
    public function permissions()
    {
        return $this->hasMany(AdminPermission::class);
    }
}
