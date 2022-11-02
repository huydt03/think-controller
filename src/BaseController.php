<?php
declare (strict_types = 1);

namespace Huydt\ThinkController;

use think\Request;
use think\exception\ValidateException;
use Auth;

class BaseController
{

    protected $middleware = [Auth::class];

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->model::select();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = $request-> post($this-> model::$fillable);

        try{   
            validate($this-> model::$rules)->check($data);
        }catch (ValidateException $e) {
            return json([
                'status'    => 0,
                'data'      => $e->getError()
            ]);
        }

        $model = $this-> model::create($data);
            
        return json([
            'status'    => 1,
            'data'      => $model
        ]);

    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $data = $this->model::find($id);
        return view('read', ['model'=>$data]);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $data = $this->model::find($id);
        return view('create', ['model'=>$data]);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $model = $this-> model::find($id);

        if(!$model)
            return json([
                'status'    => 0,
                'data'      => 'User not found!'
            ]);

        $data = $request-> post($this-> model::$fillable);

        // return json_encode($model-> rules());

         try{   
            validate($model-> rules())->check($data);
            try{
                $model-> save($data);
            }catch(\Exception $e){
                return json([
                    'status'    => 0,
                    'data'      => $data
                ]);
            }
        }catch (ValidateException $e) {
            return json([
                'status'    => 0,
                'data'      => $e->getError()
            ]);
        }

        return $model;

    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {

        $model = $this-> model::find($id);

        if(!$model)
            return json([
                'status'    => 0,
                'data'      => 'User not found!'
            ]);

        $model-> delete();

        return json([
            'status'    => 1,
            'data'      => $model
        ]);
    }
}
