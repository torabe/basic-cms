<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\AlphaDash;

class PostTypes extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $uniqueSlug = Rule::unique('post_types');
        if ($this->filled('id')) {
            $uniqueSlug->ignore($this->id)->whereNull('deleted_at');
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', new AlphaDash, $uniqueSlug, 'max:255'],
            'description' => ['string', 'nullable'],
            'parent_id' => ['numeric', 'nullable'],
            'admin_icon' => ['string', 'nullable'],
            'per_page' => ['required', 'numeric'],
            'is_sortable' => ['required', 'boolean'],
            'is_customize' => ['required', 'boolean'],
            'sort' => ['numeric', 'nullable'],
            'is_enable' => ['boolean'],
            'category_type_ids' => ['array', 'nullable'],
            'custom_field_metas' => ['array', 'nullable'],
            'custom_field_metas.*.name' => ['required', 'string'],
            'custom_field_metas.*.type' => ['required', 'string', new AlphaDash,],
            'custom_field_metas.*.key' => [
                'required',
                'string', new AlphaDash,
                Rule::notIn([
                    'post_type_id',
                    'publish_at',
                    'unpublish_at',
                    'slug',
                    'title',
                    'description',
                    'admin_id',
                    'sort',
                    'is_enable',
                ]),
            ],
            'custom_field_metas.*.validate' => ['array', 'nullable'],
            'custom_field_metas.*.options' => ['array', 'nullable'],
        ];
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => '名称',
            'slug' => 'スラッグ',
            'description' => '概要',
            'parent_id' => '親投稿タイプ',
            'admin_icon' => 'アイコン',
            'per_page' => '表示件数',
            'is_sortable' => '並び替えの許可',
            'is_customize' => '管理画面カスタマイズ',
            'category_type_ids' => 'カテゴリタイプ',
            'custom_field_metas' => 'カスタムフィールド情報',
            'custom_field_metas.*.name' => 'フィールド名',
            'custom_field_metas.*.type' => 'フィールドタイプ',
            'custom_field_metas.*.key' => 'フィールドキー',
            'custom_field_metas.*.validate' => 'フィールドバリデーション',
            'custom_field_metas.*.options' => 'フィールドオプション',
        ];
    }
}
