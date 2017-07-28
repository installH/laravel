<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;

require_once 'resources/org/code/Code.class.php';

class LoginController extends CommonController
{
    //后台登陆
    public function login()
    {
        //nput::all()如果有数据就不为空==>相当于IS_POST()
        if ($input = Input::all()){
            $code = new \Code();
            if (strtoupper($input['code'])!=$code->get()){
                return back()->with('msg','验证码错误');//返回前一个页面
            }else{
                //验证码通过走
                $user = User::first();
                if ($user->user_name!=$input['user_name'] || Crypt::decrypt($user->user_pass)!=$input['user_pwd']){
                    return back()->with('msg','用户名或者密码错误!');//返回前一个页面
                }
                //登陆成功走
                session(['user'=>$user]);
//                dd(session('user'));
                return redirect('admin');
            }
        }else{
//            session(['user'=>null]);
            return view('admin.login');
        }
    }
    //验证码生成
    public function code()
    {
        $code = new \Code();
        $code->make();
    }
    //退出登陆
    public function quit()
    {
        session(['user'=>null]);
        return redirect('admin/login');
    }

    //这个是密码测试==可以删除
    public function crypt()
    {
        $str = 'admin';
        //加密_解密  字符250个
        echo Crypt::encrypt($str)."<br>";
        echo decrypt(Crypt::encrypt($str));
    }
}
