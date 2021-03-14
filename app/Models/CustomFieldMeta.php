<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CustomFieldMeta as Children;
use App\Models\Traits\Enable;
use App\Models\Traits\Sortable;

class CustomFieldMeta extends Model
{
    use Enable;
    use Sortable;

    /**
     * 初期値
     *
     * @var array
     */
    protected $attributes = [
        'post_type_id' => 0,
        'name' => '',
        'type' => '',
        'key' => '',
        'validate' => '',
        'options' => -1,
        'parent_id' => null,
        'sort' => null,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_type_id',
        'name',
        'type',
        'key',
        'validate',
        'options',
        'parent_id',
        'sort',
    ];

    /**
     * 子投稿タイプのリレーション
     *
     * @return void
     */
    public function children()
    {
        return $this->hasMany(Children::class, 'parent_id');
    }

    /**
     * バリデータのアクセサ
     *
     * @param [type] $value
     * @return void
     */
    public function getValidateAttribute(?string $value)
    {
        return unserialize($value);
    }

    /**
     * バリデータのミューテタ
     *
     * @param [type] $value
     * @return void
     */
    public function setValidateAttribute(?array $value)
    {
        $this->attributes['validate'] = serialize($value);
    }

    /**
     * オプションのアクセサ
     *
     * @param [type] $value
     * @return void
     */
    public function getOptionsAttribute(?string $value)
    {
        return unserialize($value);
    }

    /**
     * オプションのミューテタ
     *
     * @param [type] $value
     * @return void
     */
    public function setOptionsAttribute(?array $value)
    {
        $this->attributes['options'] = serialize($value);
    }
}
