<?php

namespace App\Modules\Admin\Entities\Article;

use App\Models\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    protected $primaryKey = 'article_id';
    protected $is_delete = 0;

    public function getArticleCoverAttribute($key)
    {
        if (empty($key)) return $key;
        return Storage::url($key);
    }

    public function setArticleCoverAttribute($key)
    {
        if (!empty($key)) {
            $this->attributes['article_cover'] = str_replace(Storage::url('/'), '', $key);
        }
    }

    public function category()
    {
        return $this->hasOne(ArticleCategory::class, 'category_id', 'category_id');
    }
}
