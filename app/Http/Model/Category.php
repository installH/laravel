<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'cate_id';
    public $timestamps = false;
    protected $guarded=[];//排除不能填充的字段($fillable是要填充的字段)create()方法的一个参数

    public function tree()
    {
        $categorys = $this->orderBy('cate_order','asc')->get();
        return $data = $this->getTree($categorys,'cate_name','cate_id','cate_pid',0);
    }

    // 另外一种方法
//    public static function tree()
//    {
//        $categorys = Category::all();
//        return $data = (new Category)->getTree($categorys,'cate_name','cate_id','cate_pid',0);
//    }

    public function getTree($data,$field_name,$field_id='id',$field_pid='pid',$pid=0)
    {
        $arr = [];
        foreach ($data as $k=>$v){
            if ($v->$field_pid==$pid){
                $data[$k]['_'.$field_name] = $data[$k][$field_name];
                $arr[] = $data[$k];
                foreach ($data as $m=>$n){
                    if($n->$field_pid==$v->$field_id){
                        $data[$m]['_'.$field_name] = '┣━ '.$data[$m][$field_name];
                        $arr[] = $data[$m];
                    }
                }
            }
        }
        return $arr;
    }
}
