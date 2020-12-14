<?php

namespace App\Modules\Admin\Services;

use App\Services\Service;

class BaseService extends Service
{
    protected $model;

    public function lists(array $params)
    {
        $lists = $this->model->orderBy($this->model->getKeyName(), 'DESC')->paginate($this->getLimit($params['limit'] ?? 10));

        return [
            'current_page' => $lists->currentPage(),
            'per_page' => $lists->perPage(),
            'count_page' => $lists->lastPage(),
            'total' => $lists->total(),
            'data' => $lists->items(),
        ];
    }

    public function create(array $params)
    {
        return $this->model->create($params);
    }

    public function update(array $params)
    {
        $primaryKey = $this->model->getKeyName();
        return $this->model->where($primaryKey, $params[$primaryKey])->update($params);
    }
}
