<?php

namespace App\Modules\Admin\Services;

use App\Exceptions\Exception;
use App\Modules\Admin\Entities\Rabc\Admin;
use App\Modules\Admin\Entities\Rabc\AdminRole;
use Illuminate\Support\Facades\DB;

class AdminService extends BaseService
{
    public function __construct(Admin $admin)
    {
        $this->model = $admin;
        $this->with = ['adminInfo', 'roles'];
    }

    public function lists(array $params) : array
    {
        $params['where_callback'] = function($query) use ($params){
            $request = request();
            // 按照名称进行搜索
            if (!empty($search = $request->input('search', ''))){
                $query->where('admin_name', 'LIKE', '%' . trim($search) . '%');
            }
            // 状态
            $is_check = $request->input('is_check', -1);
            if ($is_check > -1){
                $query->where('is_check', '=', $is_check);
            }
        };
        return parent::lists($params); // TODO: Change the autogenerated stub
    }

    public function getSelectLists($request)
    {
        return $this->model->where(function($query) use ($request){
            $search = $request->input('search', '');
            // 可以按照名称进行搜索
            if (!empty($search)){
                $query->where('admin_name', 'LIKE', '%' . trim($search) . '%');
            }
        })->orderBy($this->model->getKeyName(), 'ASC')->limit(100)->get();
    }

    public function create(array $params)
    {
        DB::beginTransaction();
        try{
            $detail = parent::create($params); // TODO: Change the autogenerated stub

            $primary_key = $this->model->getKeyName();

            // 管理员详情
            $ip_agent = get_client_info();
            $detail->adminInfo()->create([
                $primary_key => $detail->{$primary_key},
                'created_ip' => $ip_agent['ip'] ?? get_ip(),
                'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
            ]);

            // 管理员关联角色设置
            $this->setRoleForAdmin($params);

            DB::commit();
            return true;
        }catch (Exception $e){
            DB::rollBack();
            $this->setError($e->getMessage());
            return false;
        }
    }

    public function update(array $params)
    {
        DB::beginTransaction();
        try{
            parent::update($params); // TODO: Change the autogenerated stub

            // 管理员关联角色设置
            $this->setRoleForAdmin($params);

            DB::commit();
            return true;
        }catch (Exception $e){
            DB::rollBack();
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * 管理员关联角色设置
     *
     * @param  array  $request_data
     */
    protected function setRoleForAdmin(array $request_data)
    {
        if (!isset($request_data['role_ids'])) return;
        /**
         * 角色操作
         */
        $request_roles = $request_data['role_ids'] ?? [];
        $all_roles     = AdminRole::findMany($request_roles);//当前选中的角色
        $has_roles     = $this->detail->roles;//当前已有的角色

        /**
         * 添加的角色
         */
        $add_roles = $all_roles->diff($has_roles);
        if (!empty($add_roles)) {
            foreach ($add_roles as $roles) $this->detail->assignRole($roles);
        }

        /**
         * 要删除的角色
         */
        $delete_roles = $has_roles->diff($all_roles);
        if (!empty($delete_roles)) {
            foreach ($delete_roles as $roles) $this->detail->deleteRole($roles);
        }
    }

    public function delete(array $params)
    {
        $primaryKey = $this->model->getKeyName();
        if (isset($params[$primaryKey]) && $params[$primaryKey] == 1){
            $this->setError('超管禁止删除！');
            return false;
        }
        // 如果是批量删除，那么移除Id
        if(isset($params['is_batch']) && $params['is_batch'] == 1){
            $ids_ary = explode(',', $params[$primaryKey]);
            if (in_array(1, $ids_ary)){
                $key = array_search(1, $ids_ary);
                unset($ids_ary[$key]);
                $params[$primaryKey] = implode(',', $ids_ary);
            }
        }
        return parent::delete($params); // TODO: Change the autogenerated stub
    }
}
