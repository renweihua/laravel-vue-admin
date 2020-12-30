<?php

namespace App\Modules\Admin\Http\Controllers\Article;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\Article\ArticleRequest;
use App\Modules\Admin\Services\ArticleCategoryService;

class ArticleCategoryController extends BaseController
{
    public function __construct(ArticleCategoryService $articleCategoryService)
    {
        $this->service = $articleCategoryService;
    }

    public function create(ArticleRequest $request)
    {
        return $this->createService($request);
    }

    public function update(ArticleRequest $request)
    {
        return $this->updateService($request);
    }
}
