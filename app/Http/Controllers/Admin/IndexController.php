<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Model\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller {

    public function index(){
//        dd($_SERVER);
        return view("admin.index");
    }

    public function info()
    {
        return view('admin.info');
    }

    //更改超级管理员密码
    public function pass()
    {
        if ($input = Input::all()){
            //前端确认密码框 name=password_confirmation
            $rules = [
                'password'=>'required|between:6,20|confirmed',
            ];
            $message = [
                'password.required'=>'新密码必填!',
                'password.between'=>'新密码必须6-20位!',
                'password.confirmed'=>'新密码和确认面不一致!',
            ];

            $validator = Validator::make($input,$rules,$message);

            //Validator判断方法
            if ($validator->passes()){
                $user = User::first();
                $_password = Crypt::decrypt($user->user_pass);
                if($input['password_o']==$_password){
                    //原密码正确
                    $user->user_pass = Crypt::encrypt($input['password']);
                    $user->update();
                    return back()->with('errors' ,'密码修改成功!');
                }else{
                    //原密码错误
                    return back()->with('errors' ,'原密码错误');
                }
            }else{
//                dd($validator->errors()->all());
                return back()->withErrors($validator);
            }
        }else{
            return view('admin.pass');
        }
    }






//========================以下是测试数据=====================================
    public function login(){
        session(['admin'=>2]);
        echo session('admin');
        echo "namespace登陆成功!";
        //连接数据库
//        $pdo = DB::connection()->getPdo();
//        dd($pdo);

        //pdo数据查询
//        $users = DB::table('user')->where('id','>',1)->get();
//        dd($users);

        //Model取数数据
//        $user = User::where('id',1)->get();
//        dump($user);

        //定义protected 的 $primaryKey
//        $user = User::find(1);
//        dd($user);

//        $user = User::find(1);
//        $user->user_name = 'xxxx';
//        $user->update();
//        return view('welcome');
    }
}