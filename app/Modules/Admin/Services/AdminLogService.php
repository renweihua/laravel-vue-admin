<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\Log\AdminLog;

class AdminLogService extends BaseService
{
    public function __construct(AdminLog $adminLog)
    {
        $this->model = $adminLog;
    }

    public function lists(array $params) : array
    {
        $lists = $this->model
            ->setMonthTable($this->getSearchMonth())
            ->with(['admin' => function($query){
                $query->select('admin_id', 'admin_name');
            }])
            ->whereHas('admin', function($query) use($params){
                if (isset($params['search']) && !empty($params['search'])){
                    $query->where('admin_name', 'LIKE', '%' . trim($params['search']) . '%')
                        ->orWhere('admin_id', '=', intval($params['search']));
                }
            })
            ->orderBy($this->model->getKeyName(), 'DESC')
            ->paginate($this->getLimit($params['limit'] ?? 10));

        return [
            'current_page' => $lists->currentPage(),
            'per_page' => $lists->perPage(),
            'count_page' => $lists->lastPage(),
            'total' => $lists->total(),
            'data' => $lists->items(),
        ];
    }
}
