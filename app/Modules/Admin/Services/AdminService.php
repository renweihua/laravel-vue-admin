<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\Rabc\Admin;

class AdminService extends BaseService
{
    public function __construct(Admin $admin)
    {
        $this->model = $admin;
        $this->with = ['adminInfo'];
    }

    public function getSelectLists($request)
    {
        return $this->model->where(function($query) use ($request){
            $search = $request->input('search', '');
            // 可以按照名称进行搜索
            if (!empty($search)){
                $query->where('admin_id', '=', intval($search))
                    ->whereOr('admin_id|admin_name', 'LIKE', '%' . trim($search) . '%');
            }
        })->orderBy($this->model->getKeyName(), 'ASC')->limit(100)->get();
    }
}
