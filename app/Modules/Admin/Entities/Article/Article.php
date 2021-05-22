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

    public function getArticleImagesAttribute($key)
    {
        if (empty($key)) return [];
        $article_images = [];
        foreach (explode(',', $key) as $value){
            $article_images[] = Storage::url($value);
        }
        return $article_images;
    }

    public function setArticleImagesAttribute($key)
    {
        if (!empty($key)) {
            $article_images = [];
            foreach ($key as $value){
                $article_images[] = str_replace(Storage::url('/'), '', $value);
            }
            $this->attributes['article_images'] = implode(',', $article_images);
        }
    }

    public function category()
    {
        return $this->hasOne(ArticleCategory::class, 'category_id', 'category_id');
    }

    public function content()
    {
        $primaryKey = $this->getKeyName();
        return $this->hasOne(ArticleContent::class, $primaryKey, $primaryKey);
    }

    public function labels()
    {
        return $this->belongsToMany(ArticleLabel::class, ArticleWithLabel::class, 'article_id' , 'label_id' , 'article_id', 'label_id')->withPivot(['article_id', 'label_id']);
    }
}
