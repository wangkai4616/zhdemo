<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/28
 * Time: 19:43
 *
 * 1.一个数据表对应一个控制器
 * 2.一个控制器对应view下所有模板
 */

namespace app\index\controller;

use app\common\controller\Base;
use think\facade\Request;
use app\common\model\User as UserModel;
use think\facade\Session;

class User extends Base
{
    //注册渲染
    public function register()
    {
        //变量赋值
        $this->isReg();
        $this->assign('title', '用户注册');
        return $this->fetch();
    }

    //注册逻辑
    public function insert()
    {
        //判断是否为Ajax请求
        if (Request::isAjax()) {
//           $data = Request::except('password_confirm','post');
            //判断请求类型是否为post
            $data = Request::post();
            //验证器路径
            $rule = 'app\common\validate\User';
            //调用controller公共控制器方法validate进行验证(数据，验证器)
            $res = $this->validate($data,$rule);
            //返回真为true,错误为错误信息
            if (true !== $res) {
                return ['status' => -1, 'message' => $res];
            } else {
                //Model模型静态方法create将数据写入数据库
                if ($use = UserModel::create($data)) {
                    //注册后自动登录，通过返回的$use模型对象，包含在数据库中的数据信息
//                    $res = UserModel::get($use->id);
                    Session::set('user_id',$use->id);
                    Session::set('user_name',$use->name);
                    return ['status' => 1, 'message' => '恭喜，注册成功'];
                }else{
                    return ['status' => 0, 'message' => '注册失败，请检查'];
                }
            }
        } else {
            $this->error('请求类型错误', 'register');
        }
    }

    //用户登录
    public function login()
    {
        //防止重复登录，检测session
        $this->Logined();
        //模板渲染与赋值fetch（默认为当前控制器下模板名，赋值数组[]）
        return $this->view->fetch('login',['title'=>'用户登录']);
    }
    //登录验证
    public function loginCheck()
    {
        if(Request::isAjax()){
            $data = Request::post();
            $rule = [
                'email|邮箱'=>'require|email',
                'password|密码'=>'require|length:6,20'
            ];
            $res = $this->validate($data,$rule);
            if (true !== $res) {
                return ['status' => -1, 'message' => $res];
            } else {
                //get方法查询单条数据，回调、链式操作、use()引入外部变量
                //where(字段名，请求变量)
                //return status|null
                $result =UserModel::get(function ($query) use($data){
                    $query->where('email',$data['email'])
                        ->where('password',sha1($data['password']));
                });
                if(null == $result){
                    return ['status' => 0, 'message' => '登录失败，请检查'];
                }else{
                    //将数据写入Session('自定义变量名','查询结果')
                    Session::set('user_id',$result->id);
                    Session::set('user_name',$result->name);
                    Session::set('is_admin',$result->is_admin);
                    return ['status' => 1, 'message' => '恭喜，登录成功'];
                }
            }

        }
    }
    //退出登录
    public function logonOut()
    {
        if(Session::has('user_id')){
            //删除指定Session，clear/delete
            Session::delete('user_id');
            Session::delete('user_name');
            //success成功(数据，url跳转)
            $this->success('退出登录成功','index/index');
        }
    }

}