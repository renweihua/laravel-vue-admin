<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\Rabc\AdminMenu;

class AdminMenuService extends BaseService
{
    public function __construct(AdminMenu $adminMenu)
    {
        $this->model = $adminMenu;
    }

    /**
     * ่ๅๅ่กจ
     *
     * @param array $params
     * @return array
     */
    public function lists(array $params) : array
    {
        $lists = $this->model->with($this->with)->orderBy('menu_sort', 'ASC')->get();

        return list_to_tree($lists->toArray());
    }

    public function getSelectLists($request)
    {
        return $this->model->getSelectLists();
    }
}
