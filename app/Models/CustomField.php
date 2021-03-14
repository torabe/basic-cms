<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\StorageHelper;
use App\Models\Traits\Sortable;

class CustomField extends Model
{
    use Sortable;

    /**
     * 初期値
     *
     * @var array
     */
    protected $attributes = [
        'post_id' => 0,
        'meta_id' => 0,
        'value' => null,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'meta_id',
        'value',
    ];

    public function meta()
    {
        return $this->belongsTo(CustomFieldMeta::class, 'meta_id');
    }

    /**
     * バリデータのアクセサ
     *
     * @param [type] $value
     * @return void
     */
    public function getValueAttribute(?string $value)
    {
        if (empty($value)) {
            return $value;
        }
        $value = unserialize($value);
        return $this->meta->type === 'image' || $this->meta->type === 'file' ? StorageHelper::getSymbolicLink($value) : $value;
    }

    /**
     * バリデータのミューテタ
     *
     * @param [type] $value
     * @return void
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = $this->meta->type === 'image' || $this->meta->type === 'file' ? serialize(StorageHelper::getStoragePath($value)) : serialize($value);
    }
}
