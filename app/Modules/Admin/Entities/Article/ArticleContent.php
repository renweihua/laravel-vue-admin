<?php

namespace App\Modules\Admin\Entities\Article;

use App\Models\Model;

class ArticleContent extends Model
{
    protected $primaryKey = 'article_id';
    public $timestamps = false;
}
