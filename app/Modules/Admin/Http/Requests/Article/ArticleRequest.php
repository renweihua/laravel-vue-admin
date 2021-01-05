<?php

namespace App\Modules\Admin\Http\Requests\Article;

use App\Modules\Admin\Entities\Article\Article;
use App\Modules\Admin\Http\Requests\BaseRequest;

class ArticleRequest extends BaseRequest
{
    public function setInstance()
    {
        $this->instance = Article::getInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'article_title' => [
                'required',
                'max:256',
                'unique:' . $this->instance->getTable() . ',article_title' . $this->validate_id,
            ],
            'category_id'   => [
                'required',
            ],
            'article_cover' => [
                'required',
            ],
        ];
    }

    public function messages()
    {
        return [
            'article_title.required' => '请输入文章标题！',
            'article_title.max'      => '标题字数不可超过 256！',
            'article_title.unique'   => '文章标题已存在，请更换！',
            'category_id.required'   => '请选择文章分类！',
            'article_cover.required' => '请上传封面！',
        ];
    }
}
