<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Admin\Http\Requests\BaseRequest;
use App\Traits\Json;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    use Json;

    protected $service;

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (!isset($this->service)){
            return $this->successJson([], '请先设置Service或者重写方法！');
        }
        return $this->successJson($this->service->lists($request->all()));
    }

    public function detail($id = 0)
    {
        if (!isset($this->service)){
            return $this->successJson([], '请先设置Service或者重写方法！');
        }
        return $this->successJson(empty($id) ? [] : $this->service->detail($id));
    }

    public function createService($request)
    {
        if ($request instanceof FormRequest){
            $request->validated();
        }

        if (!isset($this->service)){
            return $this->successJson([], '请先设置Service或者重写方法！');
        }
        if ($this->service->create($request->all())){
            return $this->successJson([], $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }

    public function updateService($request)
    {
        if ($request instanceof FormRequest){
            $request->validated();
        }

        if (!isset($this->service)){
            return $this->successJson([], '请先设置Service或者重写方法！');
        }
        if ($this->service->update($request->all())){
            return $this->successJson([], $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }

    public function delete(Request $request)
    {
        if (!isset($this->service)){
            return $this->successJson([], '请先设置Service或者重写方法！');
        }
        if ($this->service->delete($request->all())){
            return $this->successJson([], $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }

    public function changeFiledStatus(Request $request)
    {
        if (!isset($this->service)){
            return $this->successJson([], '请先设置Service或者重写方法！');
        }

        if ($this->service->changeFiledStatus($request->all())){
            return $this->successJson([], $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }
}
