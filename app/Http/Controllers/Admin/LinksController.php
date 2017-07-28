<?php

namespace App\Http\Controllers\Admin;

use App\http\Model\Links;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class LinksController extends Controller
{
    //GET|HEAD.admin/links  全部友情链接
    public function index()
    {
        $data=Links::orderBy('link_order','asc')->get();
//        dd($data);
        return view('admin.links.index',compact('data'));
    }
    //友情链接_修改排序
    public function changeOrder()  //友情链接排序
    {
        $input = Input::all();
        $links = Links::find($input['link_id']);
        $links->link_order = $input['link_order'];
        $res = $links->update();
        if($res){
            $data = [
                'status'=>1,
                'msg'=>'友情链接排序更新成功!'
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'友情链接排序更新失败!'
            ];
        }
        return $data;
    }

    //GET|HEAD.admin/links/create  添加友情链接
    public function create()
    {
        return view('admin.links.add');
    }
    //POST.admin/links  友情链接提交
    public function store()
    {
        if ($input = Input::except('_token')){
            $rules = [
                'link_name'=>'required',
                'link_title'=>'required',
                'link_url'=>'required',
                'link_order'=>'digits_between:0,1000'
            ];
            $message = [
                'link_name.required'=>'名称不能为空!',
                'link_title.required'=>'标题不能为空!',
                'link_url.required'=>'地址不能为空!',
                'link_order.digits_between'=>'排序必须是0-1000的数字!'
            ];

            $validator = Validator::make($input,$rules,$message);

            //Validator判断方法
            if ($validator->passes()){
                $res = Links::create($input);
//                dd($res);
                if($res){
                    return redirect('admin/links');
                }else{
                    return back()->with('errors','友情链接添加失败,稍后重试');
                }
            }else{
//                dd($validator->errors()->all());
                return back()->withErrors($validator);
            }
        }else{
            return view('admin.pass');
        }
    }
    //GET|HEAD.admin/links/{links}/edit  编辑友情链接
    public function edit($link_id)
    {
        $field = Links::find($link_id);
        return view('admin.links.edit',compact('field'));
    }
    //PUT|PATCH.admin/links/{links}  更新分类
    public function update($link_id)
    {
        $input = Input::except('_token','_method');
        $res = Links::where('link_id',$link_id)->update($input);
        if($res){
            return redirect('admin/links');
        }else{
            return back()->with('errors','友情链接更新失败,稍后重试');
        }
    }
    //DELETE.admin/links/{links}  删除友情链接
    public function destroy($link_id)
    {
        $res = Links::where('link_id',$link_id)->delete($link_id);
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
    //GET|HEAD.admin/links/{links}  显示单个分类信息
    public function show()
    {

    }
}
