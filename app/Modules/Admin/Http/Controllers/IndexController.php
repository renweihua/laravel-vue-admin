<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\MonthModel;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function index(Request $request)
    {
        return $this->successJson([]);
    }

    /**
     * 月份表列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMonthList()
    {
        return $this->successJson(MonthModel::getInstance()->getAllMonthes());
    }
}
