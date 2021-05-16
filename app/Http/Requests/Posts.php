<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\AlphaDash;
use App\Models\PostType;
use App\Models\CustomFieldMeta;

class Posts extends FormRequest
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
        $uniqueSlug = Rule::unique('posts');
        if ($this->filled('id')) {
            $uniqueSlug->ignore($this->id)->whereNull('deleted_at');
        }

        $postType = PostType::find($this->post_type_id);
        $categoryRules = [];
        foreach ($postType->categoryTypes as $categoryType) {
            $categoryRules['categories.' . $categoryType['slug']] = ['array'];
        }

        $customFieldMetas = CustomFieldMeta::where('post_type_id', $this->post_type_id)->sort()->get();
        $customeFieldRules = [];
        foreach ($customFieldMetas as $index => $meta) {
            $customeFieldRules['custom_fields.' . $index . '.meta_id'] = ['required', 'numeric'];
            $validation = array_merge($meta['validate'], ['nullable']);
            switch ($meta->type) {
                case 'text':
                case 'textarea':
                    $validation = array_merge($validation, ['string']);
                    break;
                case 'date':
                    $validation = array_merge($validation, ['date']);
                    break;
                case 'datetime':
                    $validation = array_merge($validation, ['date']);
                    break;
                case 'file':
                    $validation = $this->hasFile('custom_fields.' . $index . '.value') ? array_merge($validation, ['file']) : array_merge($validation, ['string']);
                    break;
                case 'image':
                    $validation = $this->hasFile('custom_fields.' . $index . '.value') ? array_merge($validation, ['image']) : array_merge($validation, ['string']);
                    break;
                case 'link':
                    $customeFieldRules['custom_fields.' . $index . '.value'] = ['array'];
                    $customeFieldRules['custom_fields.' . $index . '.value.url'] = array_merge($validation, ['url']);
                    $customeFieldRules['custom_fields.' . $index . '.value.text'] = ['nullable', 'string'];
                    continue 2;
                    break;
            }
            $customeFieldRules['custom_fields.' . $index . '.value'] = $validation;
        }

        return array_merge([
            'post_type_id' => ['required', 'numeric'],
            'publish_at' => ['required', 'date'],
            'unpublish_at' => ['nullable', 'date'],
            'slug' => ['required', new AlphaDash, $uniqueSlug, 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
            'admin_id' => ['required', 'numeric'],
            'sort' => ['numeric', 'nullable'],
            'is_enable' => ['boolean'],
            'categories' => ['array'],
        ], $categoryRules, $customeFieldRules);
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     *
     * @return array
     */
    public function attributes()
    {
        $postType = PostType::find($this->post_type_id);
        $categoryAttributes = [];
        foreach ($postType->categoryTypes as $categoryType) {
            $categoryAttributes['categories.' . $categoryType['slug']] = $categoryType['name'];
        }

        $customFieldMetas = CustomFieldMeta::where('post_type_id', $this->post_type_id)->sort()->get();
        $customeFieldAttributes = [];
        foreach ($customFieldMetas as $index => $meta) {
            if ($meta->type === 'link') {
                $customeFieldAttributes['custom_fields.' . $index . '.value.url'] = $meta['name'];
                continue;
            }
            $customeFieldAttributes['custom_fields.' . $index . '.value'] = $meta['name'];
        }

        return array_merge([
            'publish_at' => '公開日時',
            'unpublish_at' => '公開終了日時',
            'slug' => 'スラッグ',
            'title' => 'タイトル',
            'description' => '概要',
            'content' => '本文',
        ], $categoryAttributes, $customeFieldAttributes);
    }

    public function messages()
    {
        return [
            'custom_fields.*.value.after' => ':attributeは現在時以降の日付にしてください。',
            'custom_fields.*.value.before' => ':attributeは現在時以前の日付にしてください。',
        ];
    }
}
