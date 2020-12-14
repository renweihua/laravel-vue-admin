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
        return $this->successJson($this->service->lists($request->all()));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return $this->successJson();
    }

    public function createService($request)
    {
        if ($request instanceof FormRequest){
            $request->validated();
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

        if ($this->service->update($request->all())){
            return $this->successJson([], $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(BaseRequest $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
