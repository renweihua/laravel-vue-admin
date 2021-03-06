<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\UploadFile;
use Illuminate\Http\Request;

class UploadController extends BaseController
{
    /**
     * 文件上传
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $file
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function file(Request $request, $file = 'file')
    {
        if (empty($request->file($file))){
            return $this->errorJson('请上传文件！');
        }

        // var_dump($request->file($file)->getClientOriginalExtension());
        // var_dump($request->file($file)->getMimeType());
        // var_dump($request->file($file)->getType());
        // var_dump($request->file($file)->getClientMimeType());
        // exit;

        $path = $request->file($file)->storePublicly(
            date('Ym'),
            config('filesystems')
        );

        // 添加文件库记录
        $uploadFile = $this->addUploadFile($path, $request->file($file));

        return $this->successJson($path, '上传成功', ['file_url' => $uploadFile->file_url]);
    }

    /**
     * 添加文件库上传记录
     * @param $fileName
     * @param $fileInfo
     * @param $fileType
     * @return UploadFile
     */
    private function addUploadFile($file_name, $file)
    {
        // 存储引擎
        $storage = 'local';
        // 存储域名
        $host_url = '';
        // 添加文件库记录
        return UploadFile::create([
            'storage' => $storage,
            'host_url' => $host_url,
            'file_name' => $file_name,
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
        ]);
    }
}
