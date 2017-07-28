<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Article;
use App\Http\Model\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ArticleController extends CommonController
{
    //GET|HEAD  admin/article  全部文章列表(分页)
    public function index()
    {
        $data = Article::orderBy('art_id','disc')->paginate(10);//一页显示五条数据
        //dd($data->links());//查看共有几页数组(打印出来就是<ul>标签)
        //dd($data);
        return view('admin.article.index',compact('data'));
    }
    //POST  admin/article  提交添加文章
    public function store()
    {
        $input = Input::except('_token');
        $input['art_time'] = time();

        $rules = [
            'art_title'=>'required',
            'art_content'=>'required'
        ];
        $message = [
            'art_title.required'=>'文章标题不能为空!',
            'art_content.required'=>'文章内容不能为空!'
        ];

        $validator = Validator::make($input,$rules,$message);

        //Validator判断方法
        if ($validator->passes()){
            $res = Article::create($input);
            if($res){
                return redirect('admin/article');
            }else{
                return back()->with('errors','数据填充失败');
            }
        }else{
//                dd($validator->errors()->all());
            return back()->withErrors($validator);
        }
    }
    //GET|HEAD  admin/article/create  显示添加文章页面
    public function create()
    {
        $data = (new Category())->tree();
        return view('admin.article.add',compact('data'));
    }
    //GET|HEAD  admin/article/{article}
    public function show()
    {

    }
    //PUT|PATCH  admin/article/{article}
    public function update($art_id)
    {
        $input = Input::except('_token','_method');
//        dd($input);
        $res = Article::where('art_id',$art_id)->update($input);
        if($res){
            return redirect('admin/article');
        }else{
            return back()->with('errors','文章更新失败,稍后重试');
        }
    }
    //DELETE  admin/article/{article}  删除单个文章
    public function destroy($art_id)
    {
        $res = Article::where('art_id',$art_id)->delete($art_id);
        if($res){
            $data = [
                'status'=>1,
                'msg'=>'文章删除成功!'
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'文章删除失败!'
            ];
        }
        return $data;
    }
    //GET|HEAD  admin/article/{article}/edit  编辑文章
    public function edit($art_id)
    {
        $data = (new Category())->tree();
        $field = Article::find($art_id);
        return view('admin.article.edit',compact('data','field'));
    }
}
