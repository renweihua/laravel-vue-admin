<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\System\Config;

class ConfigService extends BaseService
{
    public function __construct(Config $config)
    {
        $this->model = $config;
    }

    public function lists(array $params): array
    {
        $params['where_callback'] = function($query) use ($params){
            $request = request();
            // 按照名称进行搜索
            if (!empty($search = $request->input('search', ''))){
                $query->where('config_title', 'LIKE', '%' . trim($search) . '%')
                    ->whereOr('config_name', 'LIKE', '%' . trim($search) . '%');
            }
            // 状态
            $is_check = $request->input('is_check', -1);
            if ($is_check > -1){
                $query->where('is_check', '=', $is_check);
            }
        };
        $params['order'] = 'config_sort';
        $params['order_sort'] = 'ASC';
        return parent::lists($params); // TODO: Change the autogenerated stub
    }

    /**
     * 同步配置
     *
     * @return mixed
     */
    public function pushRefreshConfig()
    {
        return $this->model->pushRefreshConfig();
    }
}
