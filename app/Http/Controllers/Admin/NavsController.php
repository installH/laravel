<?php

namespace App\Http\Controllers\Admin;

use App\http\Model\Navs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class NavsController extends Controller
{
    //GET|HEAD.admin/navs  全部自定义导航
    public function index()
    {
        $data=Navs::orderBy('nav_order','asc')->get();
//        dd($data);
        return view('admin.navs.index',compact('data'));
    }
    //自定义导航_修改排序
    public function changeOrder()  //自定义导航排序
    {
        $input = Input::all();
        $navs = navs::find($input['nav_id']);
        $navs->nav_order = $input['nav_order'];
        $res = $navs->update();
        if($res){
            $data = [
                'status'=>1,
                'msg'=>'自定义导航排序更新成功!'
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'自定义导航排序更新失败!'
            ];
        }
        return $data;
    }

    //GET|HEAD.admin/navs/create  添加自定义导航
    public function create()
    {
        return view('admin.navs.add');
    }
    //POST.admin/navs  自定义导航提交
    public function store()
    {
        if ($input = Input::except('_token')){
            $rules = [
                'nav_name'=>'required',
                'nav_url'=>'required',
                'nav_order'=>'digits_between:0,1000'
            ];
            $message = [
                'nav_name.required'=>'名称不能为空!',
                'nav_url.required'=>'URL不能为空!',
                'nav_order.digits_between'=>'排序必须是0-1000的数字!'
            ];

            $validator = Validator::make($input,$rules,$message);

            //Validator判断方法
            if ($validator->passes()){
                $res = Navs::create($input);
//                dd($res);
                if($res){
                    return redirect('admin/navs');
                }else{
                    return back()->with('errors','自定义导航添加失败,稍后重试');
                }
            }else{
//                dd($validator->errors()->all());
                return back()->withErrors($validator);
            }
        }else{
            return view('admin.pass');
        }
    }
    //GET|HEAD.admin/navs/{navs}/edit  编辑自定义导航
    public function edit($nav_id)
    {
        $field = Navs::find($nav_id);
        return view('admin.navs.edit',compact('field'));
    }
    //PUT|PATCH.admin/navs/{navs}  更新分类
    public function update($nav_id)
    {
        $input = Input::except('_token','_method');
        $res = Navs::where('nav_id',$nav_id)->update($input);
        if($res){
            return redirect('admin/navs');
        }else{
            return back()->with('errors','自定义导航更新失败,稍后重试');
        }
    }
    //DELETE.admin/navs/{navs}  删除自定义导航
    public function destroy($nav_id)
    {
        $res = Navs::where('nav_id',$nav_id)->delete($nav_id);
        if($res){
            $data = [
                'status'=>1,
                'msg'=>'链接删除成功!'
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'链接删除失败!'
            ];
        }
        return $data;
    }
    //GET|HEAD.admin/navs/{navs}  显示单个分类信息
    public function show()
    {

    }
}
