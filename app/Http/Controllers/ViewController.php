<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function index(){
        $data = [
            'name' => 'CJH',
            'age' => 18
        ];
        $title = "xxx";
        $cjh = null;
        $str = "<script>document.write('laravel');</script>";
        return view('my_laravel',compact('data','title','str'));
    }

    public function showConfig(){
        echo config('database.connections.mysql.prefix');
    }
}
