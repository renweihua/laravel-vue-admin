<?php

namespace App\Modules\Admin\Entities\Article;

use App\Models\Model;

class ArticleCategory extends Model
{
    protected $primaryKey = 'category_id';
    protected $is_delete = 0;
}
