<?php

namespace App\Modules\Admin\Entities\Article;

use App\Models\Model;

class ArticleCategory extends Model
{
    protected $primaryKey = 'category_id';
    protected $is_delete = 0;

    public function getSelectLists()
    {
        return list_to_tree($this->orderBy('category_sort', 'ASC')->get()->toArray(), $this->getKeyName());
    }
}
