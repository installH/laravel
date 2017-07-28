<?php

namespace App\Http\Controllers\Home;


use App\Http\Model\Article;
use App\Http\Model\Category;
use App\http\Model\Links;

class IndexController extends CommonController
{
    public function cjh()
    {
        //网站维护显示页面
        return view('welcome');
    }

    //前台首页
    public function index()
    {
        //点击量最高的6篇文章(图片推荐区域)
        $hot = Article::orderBy('art_view','desc')->take(6)->get();
        //文章推荐_图文列表带_分页效果_5条
        $data = Article::orderBy('art_time','desc')->take(5)->paginate(5);
        //友情链接
        $links = Links::orderBy('link_order','asc')->get();

        return view('home.index',compact('hot','new','data','links','hot5'));
    }
    //文章列表页面
    public function cate($cate_id)
    {
        //板块查看次数自增
        Category::where('cate_id',$cate_id)->increment('cate_view',1);

        //图文列表4篇(带分页)
        $data = Article::where('cate_id',$cate_id)->orderBy('art_time','desc')->paginate(4);
        //当前分类的子分类
        $submenu = Category::where('cate_pid',$cate_id)->get();
        //显示当前分类的文章信息
        $field = Category::find($cate_id);
        return view('home.list',compact('field','data','submenu'));
    }
    //文章详情页
    public function article($art_id)
    {
        //文章点击次数自增
        Article::where('art_id',$art_id)->increment('art_view',1);

        //联合查询_文章表_分类表
        $field = Article::Join('category','article.cate_id','=','category.cate_id')->where('article.art_id',$art_id)->first();

        //上一篇_下一篇
        $article['pre'] = Article::where('art_id','<',$art_id)->orderBy('art_id','desc')->first();
        $article['next'] = Article::where('art_id','>',$art_id)->orderBy('art_id','asc')->first();

        //相关文章
        $data = Article::where('cate_id',$field->cate_id)->orderBy('art_time','desc')->take(6)->get();

        return view('home.new',compact('field','article','data'));
    }
}
