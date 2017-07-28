<?php

namespace App\http\Model;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';
    protected $primaryKey = 'conf_id';
    public $timestamps = false;
    protected $guarded=[];//排除不能填充的字段($fillable是要填充的字段)create()方法的一个参数
}
