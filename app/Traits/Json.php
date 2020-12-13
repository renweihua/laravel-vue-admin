<?php

declare(strict_types = 1);

namespace App\Traits;

trait Json
{
    public function successJson($data = [], $msg = 'success', $other = [])
    {
        return $this->myAjaxReturn(array_merge(['data' => $data, 'msg' => $msg, 'status' => 1], $other));
    }

    public function errorJson($msg = 'error', $status = 0, $data = [], $other = [])
    {
        return $this->myAjaxReturn(array_merge(['msg' => $msg, 'status' => $status, 'data' => $data], $other));
    }

    /**
     * [myAjaxReturn]
     * @author:cnpscy <[2278757482@qq.com]>
     * @chineseAnnotation:API接口返回格式统一
     * @englishAnnotation:
     * @version:1.0
     * @param              [type] $data [description]
     */
    public function myAjaxReturn($data)
    {
        $data['data'] = $data['data'] ?? [];
        $data['status'] = intval($data['status'] ?? (empty($data['data']) ? 0 : 1));
        $data['msg'] = $data['msg'] ?? (empty($data['status']) ? '数据不存在！' : 'success');

        return response()->json($data);
    }

    /**
     * [checkAjaxReturn]
     * @author:cnpscy <[2278757482@qq.com]>
     * @chineseAnnotation:检测返回的数组，参数是否匹配，不匹配主动生成空
     * @englishAnnotation:
     * @version:1.0
     * @param              [type] $data [description]
     */
    public static function checkAjaxReturn(array $data = [])
    {
        $data['data'] = $data['data'] ?? [];
        $data['status'] = intval($data['status'] ?? (empty($data['data']) ? 0 : 1));
        $data['msg'] = $data['msg'] ?? (empty($data['status']) ? '数据不存在！' : 'success');

        if ($data['msg'] == '请先登录') $data['status'] = lang('_UN_LOGIN_STAUT_');
        $data['config'] = config('cnpscy');
        return $data;
    }

    public static function commonReturn(array $return = [])
    {
        $data = self::checkAjaxReturn($return);

        return self::myAjaxReturn($data);
    }
}
