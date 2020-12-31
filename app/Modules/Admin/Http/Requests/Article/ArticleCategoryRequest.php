<?php

namespace App\Modules\Admin\Http\Requests\Article;

use App\Modules\Admin\Entities\Article\ArticleCategory;
use App\Modules\Admin\Http\Requests\BaseRequest;

class ArticleCategoryRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $instance = ArticleCategory::getInstance();
        $primarykey = $instance->getKeyName();
        $validate_id = ',' . request()->input($primarykey, 0) . ',' .  $primarykey;

        return [
            'category_name' => [
                'required',
                'max:256',
                'unique:' . $instance->getTable() . ',category_name' . $validate_id
            ],
            'category_sort' => [
                'required',
            ]
        ];
    }

    public function messages()
    {
        return [
            'category_name.required' => '请输入分类名称！',
            'category_name.max'      => '分类名称字数不可超过 256！',
            'category_name.unique'   => '分类名称已存在，请更换！',
        ];
    }
}
