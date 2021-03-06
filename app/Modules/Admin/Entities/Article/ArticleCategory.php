<?php

namespace App\Modules\Admin\Entities\Article;

use App\Models\Model;
use Illuminate\Support\Facades\Cache;

class ArticleCategory extends Model
{
    protected $primaryKey = 'category_id';
    protected $is_delete  = 0;
    protected $cache_key  = 'article_categories';

    public function getSelectLists()
    {
        return $this->getCacheTree();
    }

    /**
     * 删除缓存
     *
     * @return bool
     */
    public function delCache()
    {
        Cache::forget($this->cache_key);
        return true;
    }

    /**
     * 获取文章分类的数据缓存
     *
     * @param  string  $get_type
     *
     * @return mixed
     */
    private function getCache(string $get_type = '')
    {
        return Cache::remember($this->cache_key, 3600, function() use ($get_type) {
            $all = $this->orderBy('category_sort', 'ASC')->get()->toArray();
            $tree = list_to_tree($all, $this->getKeyName());
            return compact('all', 'tree');
        });
    }

    /**
     * 获取Tree格式化数据
     *
     * @return array|mixed
     */
    public function getCacheTree()
    {
        return $this->getCache()['tree'] ?? [];
    }

    /**
     * 获取所有文章数据
     *
     * @return array|mixed
     */
    public function getCacheAll()
    {
        return $this->getCache()['all'] ?? [];
    }

    /**
     * 获取所有子集的Id集合（包含自己）
     *
     * @param  int    $parent_id
     * @param  array  $all
     *
     * @return array|int[]
     */
    public function getChildIds(int $parent_id, array $all = [])
    {
        $ids = [$parent_id];
        empty($all) && $all = $this->getCacheAll();
        foreach ($all as $key => $value) {
            if ( $value['parent_id'] == $parent_id ) {
                unset($all[$key]);
                $child_ids = $this->getChildIds((int)$value['category_id'], $all);
                !empty($child_ids) && $ids = array_merge($ids, $child_ids);
            }
        }
        return $ids;
    }
}
