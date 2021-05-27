<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Handlers\DatabaseHandler;
use App\Modules\Admin\Services\DatabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * @param  \App\Handlers\DatabaseHandler  $databaseHandler
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function backupsTables(Request $request, DatabaseHandler $databaseHandler)
    {
        set_time_limit(0);//防止超时
        $tables_list = $request->tables_list;
        if (empty($tables)) {
            $tables_list = array_column(DB::select('SHOW TABLE STATUS'), 'Name');
        }
        sort($tables_list);
        foreach ($tables_list as $key => $table){
            if (preg_match("/^\d{4}[\_]([0-9][0-9])?$/", substr($table, -7))){
                unset($tables_list[$key]);
            }
        }
        if ($databaseHandler->dataTableBak($tables_list, $result)){
            return $this->successJson($result, $databaseHandler->getError());
        }else{
            return $this->errorJson($databaseHandler->getError());
        }
    }
}
