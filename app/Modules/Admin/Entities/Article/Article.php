<?php

namespace App\Modules\Admin\Entities\Article;

use App\Models\Model;

class Article extends Model
{
    protected $primaryKey = 'article_id';
    protected $is_delete = 0;
}
