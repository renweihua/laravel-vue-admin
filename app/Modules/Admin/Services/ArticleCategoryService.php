<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\Article\ArticleCategory;

class ArticleCategoryService extends BaseService
{
    public function __construct(ArticleCategory $article)
    {
        $this->model = $article;
    }

    public function lists(array $params): array
    {
        $lists = $this->model->orderBy('category_sort', 'ASC')->get();

        return list_to_tree($lists->toArray(), 'category_id');
    }

    public function getSelectLists($request)
    {
        return $this->model->getSelectLists();
    }
}
