<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Admin\Services\DatabaseService;
use Illuminate\Http\Request;

class DatabaseController extends BaseController
{
    public function __construct(DatabaseService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->successJson($this->service->lists($request->all()));
    }

    /**
     * 批量备份指定的数据表
     *
     * @param  \Illuminate\Http\Request       $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function backupsTables(Request $request)
    {
        set_time_limit(0);//防止超时
        if ($result = $this->service->backupsTables($request->tables_list)){
            return $this->successJson($result, $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }
}
