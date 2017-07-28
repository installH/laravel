<?php

namespace App\Http\Controllers\Home;

use App\Http\Model\Article;
use App\http\Model\Navs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class CommonController extends Controller
{
    public function __construct()
    {
        //点击排行的_5篇文章
        $hot5 = Article::orderBy('art_view','desc')->take(5)->get();
        //8条_最新文章
        $new = Article::orderBy('art_time','desc')->take(8)->get();
        $navs = Navs::orderBy('nav_order','asc')->get();
        View::share('navs',$navs);//共享导航栏_到所有页面
        View::share('hot5',$hot5);//共享右侧栏_最新文章_到所有页面
        View::share('new',$new);//共享变量右侧栏_点击排行_到所有页面
    }
}
