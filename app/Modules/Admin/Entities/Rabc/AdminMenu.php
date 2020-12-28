<?php

namespace App\Modules\Admin\Entities\Rabc;

use App\Models\Model;

class AdminMenu extends Model
{
    protected $primaryKey = 'menu_id';
    protected $is_delete = 0;

    public static function getAllMenus()
    {
        return self::orderBy('menu_sort', 'ASC')->get();
    }

    public function getSelectLists()
    {
        return list_to_tree($this->orderBy('menu_sort', 'ASC')->get()->toArray());
    }
}