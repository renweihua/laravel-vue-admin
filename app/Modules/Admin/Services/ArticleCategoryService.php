<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\Article\ArticleCategory;

class ArticleCategoryService extends BaseService
{
    public function __construct(ArticleCategory $article)
    {
        $this->model = $article;
    }

    public function lists(array $params) : array
    {
        return $this->model->getSelectLists();
    }

    public function getSelectLists($request)
    {
        return $this->model->getSelectLists();
    }
}
