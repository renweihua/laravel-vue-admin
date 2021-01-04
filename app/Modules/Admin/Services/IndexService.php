<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\Article\Article;
use App\Modules\Admin\Entities\Log\AdminLog;
use App\Modules\Admin\Entities\Rabc\Admin;
use App\Modules\Admin\Entities\System\Banner;
use App\Modules\Admin\Entities\System\Friendlink;
use Monolog\Handler\IFTTTHandler;

class IndexService extends BaseService
{
    public function index()
    {
        return [
            // 管理员人数
            'admins_count'      => Admin::count(),
            // 文章总数
            'articles_count'    => Article::count(),
            // Banner数量
            'banners_count'     => Banner::count(),
            // 友情链接数量
            'friendlinks_count' => Friendlink::count(),
            // 技能
            'skill'             => $this->skill(),
            // 统计图：使用请求日志来做数据
            'statistics'        => $this->logsStatistics(),
        ];
    }

    private function skill()
    {
        $arr = [
            'cnpscy'     => 100,
            'PHP'        => rand(70, 99),
            'Mysql'      => rand(70, 99),
            'Redis'      => rand(70, 99),
            'Thinkphp'   => rand(70, 99),
            'Laravel'    => rand(50, 99),
            'Hypref'     => rand(50, 99),
            'Vue'        => rand(10, 99),
            'JavaScript' => rand(20, 99),
        ];
        $list = [];
        foreach ($arr as $key => $value) {
            $list[] = [
                'name'  => $key,
                'value' => $value,
            ];
        }
        return $list;
    }

    /**
     * 按照日志的请求类型来获取对应的统计图数据
     *
     * @return array
     */
    public function logsStatistics()
    {
        $default_data = [
            'xAxis'      => [
                'data' => [

                ],
            ],
            'list_name'  => [
                // 'GET',
                'POST',
                'PUT',
                'DELETE',
            ],
            'data_lists' => [
                // 'GET'    => [],
                'POST'   => [

                ],
                'PUT'    => [

                ],
                'DELETE' => [

                ],
            ],
        ];
        $adminLogInstance = AdminLog::getInstance();
        $interval_nums = 10; // 时段次数：10个时间段，自己调整
        $time_interval = 600; // 时段间隔：10分钟，自己调整
        $hours = $time_interval / 600;
        $time = strtotime(date('Y-m-d H', strtotime('+' . $hours . ' hour')) . ':00:00');

        // 数据查询
        $list = $adminLogInstance->whereBetWeen('created_time', [
            $time - $interval_nums * $time_interval,
            $time,
        ])->get();
        for ($i = 0; $i < $interval_nums; $i++) {
            $end_time = $time - $time_interval;
            // 默认X轴的时段
            $default_data['xAxis']['data'][$i] = date('Y-m-d H:i', $end_time);

            // $default_data['data_lists']['GET'][$i] =
            $default_data['data_lists']['POST'][$i]
                = $default_data['data_lists']['PUT'][$i]
                = $default_data['data_lists']['DELETE'][$i]
                = 0;

            if ( $list ) {
                foreach ($list as $v) {
                    if ($v->created_time >= $end_time && $v->created_time <= $time){
                        if ( $v->log_method == 'GET' ) {
                            // ++$default_data['data_lists']['GET'][$i];
                        } elseif ( $v->log_method == 'POST' ) {
                            ++$default_data['data_lists']['POST'][$i];
                        } elseif ( $v->log_method == 'PUT' ) {
                            ++$default_data['data_lists']['PUT'][$i];
                        } elseif ( $v->log_method == 'DELETE' ) {
                            ++$default_data['data_lists']['DELETE'][$i];
                        }
                    }
                }
            }

            // 把当前的结束时间设置为下一次的开始时间
            $time = $end_time;
        }
        return (array)$default_data;
    }
}
