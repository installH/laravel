<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Category;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CategoryController extends CommonController
{
    //GET|HEAD.admin/category  全部分类列表
    public function index()
    {
        $data = $categorys = (new Category)->tree();
        return view('admin.category.index')->with('data',$data);
    }

    public function changeOrder()
    {
        $input = Input::all();
        $cate = Category::find($input['cate_id']);
        $cate->cate_order = $input['cate_order'];
        $res = $cate->update();
        if($res){
            $data = [
                'status'=>1,
                'msg'=>'分类排序更新成功!'
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'分类排序更新失败!'
            ];
        }
        return $data;
    }

    //GET|HEAD.admin/category/create  添加分类
    public function create()
    {
        $data = Category::where('cate_pid',0)->get();
        return view('admin/category/add',compact('data'));
    }
    //POST.admin/category  添加分类提交
    public function store()
    {
        if ($input = Input::except('_token')){
//            dd($input);
            //前端确认密码框 name=password_confirmation
            $rules = [
                'cate_name'=>'required',
            ];
            $message = [
                'cate_name.required'=>'分类名称不能为空!'
            ];

            $validator = Validator::make($input,$rules,$message);

            //Validator判断方法
            if ($validator->passes()){
                $res = Category::create($input);
//                dd($res);
                if($res){
                    return redirect('admin/category');
                }else{

                }
            }else{
//                dd($validator->errors()->all());
                return back()->with('errors','数据填充失败,稍后重试');
            }
        }else{
            return view('admin.pass');
        }
    }
    //GET|HEAD.admin/category/{category}/edit  编辑分类
    public function edit($cate_id)
    {
        $field = Category::find($cate_id);
        $data = Category::where('cate_pid',0)->get();
        return view('admin.category.edit',compact('data','field'));
    }
    //PUT|PATCH.admin/category/{category}  更新分类
    public function update($cate_id)
    {
        $input = Input::except('_token','_method');
        $res = Category::where('cate_id',$cate_id)->update($input);
        if($res){
            return redirect('admin/category');
        }else{
            return back()->with('errors','分类信息更新失败,稍后重试');
        }
    }
    //DELETE.admin/category/{category}  删除单个分类
    public function destroy($cate_id)
    {
        $res = Category::where('cate_id',$cate_id)->delete($cate_id);
        Category::where('cate_pid',$cate_id)->update(['cate_pid'=>0]);
        if($res){
            $data = [
                'status'=>1,
                'msg'=>'分类删除成功!'
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'分类删除失败!'
            ];
        }
        return $data;

    }
    //GET|HEAD.admin/category/{category}  显示单个分类信息
    public function show()
    {

    }
}
