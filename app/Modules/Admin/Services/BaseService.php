<?php

namespace App\Modules\Admin\Services;

use App\Models\MonthModel;
use App\Services\Service;

class BaseService extends Service
{
    protected $model;

    public function lists(array $params): array
    {
        // 如果是按月分表的模型，设置按月份查询的月份表
        if ($this->model instanceof MonthModel){
            $this->model = $this->model->setMonthTable(request()->input('search_month', ''));
        }
        $lists = $this->model->orderBy($this->model->getKeyName(), 'DESC')->paginate($this->getLimit($params['limit'] ?? 10));

        return [
            'current_page' => $lists->currentPage(),
            'per_page' => $lists->perPage(),
            'count_page' => $lists->lastPage(),
            'total' => $lists->total(),
            'data' => $lists->items(),
        ];
    }

    public function detail($id)
    {
        return $this->model->detail($id);
    }

    public function create(array $params)
    {
        return $this->model->create($this->model->setFilterFields($params));
    }

    public function update(array $params)
    {
        $primaryKey = $this->model->getKeyName();
        return $this->model->where($primaryKey, $params[$primaryKey])->update($this->model->setFilterFields($params));
    }

    public function delete(array $params)
    {
        $primaryKey = $this->model->getKeyName();
        if ( empty($params[$primaryKey]) && empty($params['is_batch'])) {
            $this->setError('请指定删除标识！');
            return false;
        }
        // 是否为批量删除
        if (isset($params['is_batch']) && $params['is_batch'] == 1){
            $ids = $params['ids'] ?? $params[$primaryKey];
        }else{
            $ids = [$params[$primaryKey]];
        }
        /**
         * 如果是月份表进行删除的话，那么需要指定表名
         */
        if ($this->model instanceof MonthModel && isset($params['month'])){
            $this->model = $this->model->setMonthTable($params['month']);
        }
        if ($this->model->getIsDelete() == 0){
            return $this->model->where($primaryKey, 'IN', $ids)->update([$this->model->getDeleteField() => 1]);
        }else{
            return $this->model->where($primaryKey, 'IN', $ids)->delete();
        }
    }

    public function changeFiledStatus(array $params)
    {
        $primaryKey = $this->model->getKeyName();
        if (empty($params[$primaryKey])) {
            $this->setError('请指定删除标识！');
            return false;
        }
        if ( $this->model->where([$primaryKey => $params[$primaryKey]])->update([$params['change_field'] => $params['change_value']]) ) {
            $this->setError('设置成功！');
            return true;
        } else {
            $this->setError('设置失败！');
            return false;
        }
    }
}
