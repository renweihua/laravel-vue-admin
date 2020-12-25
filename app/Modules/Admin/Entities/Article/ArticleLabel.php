<?php

namespace App\Modules\Admin\Entities\Article;

use App\Models\Model;

class ArticleLabel extends Model
{
    protected $primaryKey = 'label_id';
    protected $is_delete = 0;
}
