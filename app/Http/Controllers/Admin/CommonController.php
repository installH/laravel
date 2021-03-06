<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CommonController extends Controller
{
    //图片上传
    public function upload()
    {
        /*=======================uploadify 使用教程
        //检验一下上传文件是否有效
        if($file = isValid()){}
        $clientName = $file -> getClientOriginalName();//获取文件名称
        $tmpName = $file -> getFileName();//缓存在tmp文件夹中的文件名 例如 php9372.tmp 这种类型的.
        $realPath = $file -> getRealPath();//这个表示的是缓存在tmp文件夹下的文件的绝对路径,例如我的是:"J:\wamp64\tmp\phpC992.tmp"
        $entension = $file -> getClientOriginalExtension();//上传文件后缀
        $mimeTye = $file -> getMimeType();//大家对mimeType应该不陌生了.我得到的结果是image/jpeg.
        $path = $file -> move('storage/uploads');
        //如果你这样写的话,默认是会放置在 我们 public/storage/uploads/phpC992.tmp
        //貌似不是我们希望的,如果我们希望将其放置在app的storage目录下的upload目录中,并且需要改名的话..
        $path = $file -> move(app_path().'/storage/uploads'.$newName);
        //这里app_path()就是app文件夹所在的路径.$newName可以是你通过某种算法活的的文件的名称.主要不能重复产生冲突即可,比如$newName = md5(date('ymshis).$clientName).'.'.$extension;
        //利用日期和客户端文件名结合 使用md5算法加密得到的结果.不要忘记后面加上文件原始扩展名
        */


        $file = Input::file('Filedata');//file(获取文件信息
//        dd($file);
        if($file->isValid()){
            $realPath = $file -> getRealPath();//临时文件的绝对路径
            $entension = $file -> getClientOriginalExtension(); //上传文件的后缀.
            $newName = date('YmdHis').mt_rand(100,999).'.'.$entension;//随机名称
            $path = $file -> move(base_path().'/uploads',$newName);//移动文件_并从命名(base_path()就是blog文件夹)
            $filepath = 'uploads/'.$newName;
            return $filepath;
        }
    }
}
