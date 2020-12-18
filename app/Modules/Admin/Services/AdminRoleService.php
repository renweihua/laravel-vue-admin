<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\Rabc\AdminRole;

class AdminRoleService extends BaseService
{
    public function __construct(AdminRole $adminRole)
    {
        $this->model = $adminRole;
    }

    public function getSelectLists($request)
    {
        return $this->model->where(function($query) use ($request){
            $search = $request->input('search', '');
            // 可以按照名称进行搜索
            if (!empty($search)){
                $query->where('role_name', 'LIKE', '%' . trim($search) . '%');
            }
        })->orderBy($this->model->getKeyName(), 'ASC')->limit(100)->get();
    }
}
