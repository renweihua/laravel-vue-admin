<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\Article\Article;

class ArticleService extends BaseService
{
    public function __construct(Article $article)
    {
        $this->model = $article;
    }
}
