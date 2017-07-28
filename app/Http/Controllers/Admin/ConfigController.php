<?php

namespace App\Http\Controllers\Admin;

use App\http\Model\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller
{
    //GET|HEAD.admin/config  全部配置项
    public function index()
    {
        $data=config::orderBy('conf_order','asc')->get();
        foreach ($data as $k=>$v){

            //?????????????????????
//            $data[$k]->_html='XXXXXXXXXXXXXXXXXXXXXX';
//            dd($data);
            //???????????????????????

            switch ($v->field_type){
                case 'input':
                    $data[$k]->_html = '<input type="text" class="lg" name="conf_content[]" value="'.$v->conf_content.'">';
                    break;
                case 'textarea':
                    $data[$k]->_html = '<textarea type="text" name="conf_content[]" >'.$v->conf_content.'</textarea>';
                    break;
                case 'radio':
                    //1|开启,0|关闭
                    $arr = explode(',',$v->field_value);
                    $str = '';
                    foreach ($arr as $m=>$n){
                        //1|开启
                        $r = explode('|',$n);
                        $c = $v->conf_content == $r[0]?' checked ':'';
                        $str .= '<input type="radio" name="conf_content[]"'.$c.'value="'.$r[0].'">'.$r[1].'　';
                    }
                    $data[$k]->_html = $str;
                    break;
            }
        }
        return view('admin.config.index',compact('data'));
    }
    //配置项_修改排序
    public function changeOrder()  //配置项排序
    {
        $input = Input::all();
        $config = config::find($input['conf_id']);
        $config->conf_order = $input['conf_order'];
        $res = $config->update();
        if($res){
            $data = [
                'status'=>1,
                'msg'=>'配置项排序更新成功!'
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'配置项排序更新失败!'
            ];
        }
        return $data;
    }

    //GET|HEAD.admin/config/create  添加配置项
    public function create()
    {
        return view('admin.config.add');
    }
    //POST.admin/config  配置项提交
    public function store()
    {
        if ($input = Input::except('_token')){
            $rules = [
                'conf_name'=>'required',
                'conf_title'=>'required',
                'conf_order'=>'digits_between:0,1000'
            ];
            $message = [
                'conf_name.required'=>'名称不能为空!',
                'conf_title.required'=>'标题不能为空!',
                'conf_order.digits_between'=>'排序必须是0-1000的数字!'
            ];

            $validator = Validator::make($input,$rules,$message);

            //Validator判断方法
            if ($validator->passes()){
                $res = Config::create($input);
//                dd($res);
                if($res){
                    return redirect('admin/config');
                }else{
                    return back()->with('errors','配置项添加失败,稍后重试');
                }
            }else{
//                dd($validator->errors()->all());
                return back()->withErrors($validator);
            }
        }else{
            return view('admin.pass');
        }
    }
    //GET|HEAD.admin/config/{config}/edit  编辑配置项
    public function edit($conf_id)
    {
        $field = Config::find($conf_id);
        return view('admin.config.edit',compact('field'));
    }
    //修改配置项值
    public function changeContent()
    {
        $input = Input::except('_token');
        foreach ($input['conf_id'] as $k=>$v){
            Config::where('conf_id',$v)->update(['conf_content'=>$input['conf_content'][$k]]);
        }
        $this->putFile();//更新本地配置文件
        return back()->with('errors','配置项更新成功');
    }
    //PUT|PATCH.admin/config/{config}  更新分类
    public function update($conf_id)
    {
        $input = Input::except('_token','_method');
        $res = Config::where('conf_id',$conf_id)->update($input);
        if($res){
            $this->putFile();//更新本地配置文件
            return redirect('admin/config');
        }else{
            return back()->with('errors','配置项更新失败,稍后重试');
        }
    }
    //配置项写到配置文件里
    public function putFile()
    {
        $config = Config::pluck('conf_content','conf_name')->all();//加上all才是纯净的数组
        //echo var_export($config,true);//直接输出==array ( 'web_title' => '没有标题123', 'web_count' => '淘宝123', 'web_status' => '1', )
        $path = base_path().'\config\web.php';
        $str = "<?php return ".var_export($config,true).';';
        file_put_contents($path,$str);//本地写入配置文件
    }
    //DELETE.admin/config/{config}  删除配置项
    public function destroy($conf_id)
    {
        $res = Config::where('conf_id',$conf_id)->delete($conf_id);
        if($res){
            $this->putFile();//更新本地配置文件
            $data = [
                'status'=>1,
                'msg'=>'链接删除成功!'
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'链接删除失败!'
            ];
        }
        return $data;
    }
    //GET|HEAD.admin/config/{config}  显示单个分类信息
    public function show()
    {

    }
}
