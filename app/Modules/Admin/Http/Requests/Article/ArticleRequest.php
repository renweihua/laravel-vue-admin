<?php

namespace App\Modules\Admin\Http\Requests\Article;

use App\Modules\Admin\Entities\Article\Article;
use App\Modules\Admin\Http\Requests\BaseRequest;

class ArticleRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $instance = Article::getInstance();
        $primarykey = $instance->getKeyName();
        $validate_id = ',' . request()->input($primarykey, 0) . ',' .  $primarykey;

        return [
            'article_title' => [
                'required',
                'max:256',
                'unique:' . $instance->getTable() . ',article_title' . $validate_id
            ],
            'article_cover' => [
                'required',
            ]
        ];
    }

    public function messages()
    {
        return [
            'article_title.required' => '请输入标题！',
            'article_cover.required'   => '请上传封面！',
        ];
    }
}
