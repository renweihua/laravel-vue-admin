<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\Article\Article;
use App\Modules\Admin\Entities\Rabc\Admin;
use App\Modules\Admin\Entities\System\Banner;
use App\Modules\Admin\Entities\System\Friendlink;

class IndexService extends BaseService
{
    public function index()
    {
        return [
            // 管理员人数
            'admins_count' => Admin::count(),
            // 文章总数
            'articles_count' => Article::count(),
            // Banner数量
            'banners_count' => Banner::count(),
            // 友情链接数量
            'friendlinks_count' => Friendlink::count(),
            // 技能
            'skill' => $this->skill()
        ];
    }

    private function skill()
    {
        $arr = [
            'cnpscy' => 100,
            'PHP' => rand(70, 99),
            'Mysql' => rand(70, 99),
            'Redis' => rand(70, 99),
            'Thinkphp' => rand(70, 99),
            'Laravel' => rand(50, 99),
            'Hypref' => rand(50, 99),
            'Vue' => rand(10, 99),
            'JavaScript' => rand(20, 99),
        ];
        $list = [];
        foreach ($arr as $key => $value){
            $list[] = [
                'name' => $key,
                'value' => $value,
            ];
        }
        return $list;
    }
}
