<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//===========================正式开始blog系统===========================
//未登陆路由

//网站维护显示页面
Route::any('cjh', 'Home\IndexController@cjh');

//首页显示路由_中间件网站状态判断
Route::group(['middleware' => ['web','web.status']], function () {
    //首页
    Route::get('/', 'Home\IndexController@index');
    //分类页面
    Route::get('/cate/{cate_id}', 'Home\IndexController@cate');
    //文章详情页
    Route::get('/a/{art_id}', 'Home\IndexController@article');

    //登陆路由
    Route::any('admin/login', 'Admin\LoginController@login');
    //验证码路由
    Route::get('admin/code', 'Admin\LoginController@code');
    //生成密码方法==可以删除
    Route::get('admin/crypt', 'Admin\LoginController@crypt');
});

//已经登陆路由==增加了中间件判断
Route::group(['middleware' => ['web','admin.login'],'prefix'=>'admin','namespace'=>'Admin'], function () {
    //登陆成功显示首页
    Route::get('/', 'IndexController@index');
    //info页面
    Route::get('info', 'IndexController@info');
    //退出登陆
    Route::get('quit', 'LoginController@quit');
    //修改密码
    Route::any('pass', 'IndexController@pass');
    //图片上传
    Route::any('upload', 'CommonController@upload');
    //资源路由==分类管理等等
    Route::resource('category', 'CategoryController');
    Route::post('cate/changeorder', 'CategoryController@changeOrder');//分类_修改排序
    //资源路由==文章管理等等
    Route::resource('article', 'ArticleController');
    //资源路由==友情链接
    Route::resource('links', 'LinksController');
    Route::post('links/changeorder', 'LinksController@changeOrder');//友情链接_修改排序
    //资源路由==首页菜单栏
    Route::resource('navs', 'NavsController');
    Route::post('navs/changeorder', 'NavsController@changeOrder');//自定义导航_修改排序
    //资源路由==配置项
    Route::get('config/putfile', 'ConfigController@putFile');
    Route::resource('config', 'ConfigController');
    Route::post('config/changeorder', 'ConfigController@changeOrder');//配置项_修改排序
    Route::post('config/changecontent', 'ConfigController@changeContent');//配置项_修改值内容
});




//===========================以下是例子===========================
////分组
//Route::group(['prefix' => 'admin','namespace'=>'Admin','middleware'=>['web','admin.login']], function () {
////    Route::get('login', 'IndexController@login');
//    Route::get('index', 'IndexController@index');
//    Route::resource('article', 'ArticleController');
//
//
//});
////中间件
//Route::group(['middleware' => ['web']], function () {
//    Route::get('admin/login', 'Admin\IndexController@login');
//
//    Route::get('/', function () {
//        session(["key"=>456]);
//        return view('welcome');
//    });
//
//    Route::get('/test', function () {
//        echo session("key");
//        return "test";
//    });
//});
//
//
//Route::get('user', 'Admin\IndexController@index')->name('profile');
//
////Route::get('/view', function () {
////    return view('my_laravel');
////});
//
//Route::get('view', 'ViewController@index');
//
//Route::get('showConfig', 'ViewController@showConfig');
//Route::get('/', 'IndexController@index');
