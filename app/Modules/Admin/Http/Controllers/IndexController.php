<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\MonthModel;
use App\Modules\Admin\Services\IndexService;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function __construct(IndexService $indexService)
    {
        $this->service = $indexService;
    }

    public function index(Request $request)
    {
        return $this->successJson($this->service->index());
    }

    /**
     * 按照日志类型的统计图数据
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logsStatistics(Request $request)
    {
        return $this->successJson($this->service->logsStatistics());
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
