<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/13
 * Time: 20:21
 */

namespace app\admin\controller;

use app\admin\common\controller\Base;
use think\facade\Request;
use think\facade\Session;
use app\admin\common\model\User as UserModel;
class User extends Base
{
    public function login()
    {
        $this->assign('title', '后台登录');
        return $this->view->fetch('user/login');
    }

    public function loginCheck()
    {
//        $data = Request::param();
//        halt($data);
        if (Request::post()) {
            $data = Request::param();
            $rule = [
                'email|邮箱' => 'require|email',
                'password|密码' => 'require|length:6,20'
            ];
            $res = $this->validate($data, $rule);
//            halt($res);
            if (true != $res) {
                return ['status' => -1, 'message' => $res];
            } else {
                $map[] = ['email', '=', $data['email']];
                $map[] = ['password', '=', sha1($data['password'])];
                $user = UserModel::where($map)->find();
                if ($user) {
                    Session::set('user_id', $user['id']);
                    Session::set('user_name', $user['name']);
                    Session::set('is_admin', $user['is_admin']);
                    return ['status' => 1, 'message' => '登陆成功'];
                } else {
                    return ['status' => -1, 'message' => '账号或者密码错误请重新登录'];
                }
            }
        } else{
            return ['status' => 0, 'message' => '请求类型错误'];
        }
    }

    public function loginOut()
    {
        Session::clear();
        $this->success('退出成功','user/login');
    }

    public function userList()
    {
        $data['admin_id'] = Session::get('user_id');
        $data['admin_level'] = Session::get('is_admin');
        if($data['admin_level'] == 1)
        {
            $userList = UserModel::select();
        }else{
            $userList = UserModel::where('id',$data['admin_id'])->select();
        }

        $this->view->assign('title','用户列表');
        $this->view->assign('empty','没有任何数据');
        $this->view->assign('userList',$userList);

        return $this->view->fetch('user/userList');
    }

    public function userEdit()
    {
        $userId = Request::param('id');
        $userInfo = UserModel::where('id',$userId)->find();
//        halt($data);
        $this->view->assign('title','用户信息编辑');
        $this->view->assign('userInfo',$userInfo);
        return $this->view->fetch('useredit');
    }

    public function doEdit()
    {
        $data = Request::post();
//        halt($data);
        $id = $data['id'];
        unset($data['id']);
        if(UserModel::where('id',$id)->data($data)->update()){
            return $this->success('更新成功','userList');
        }
    }

    public function doDelete()
    {
        $userId = Request::param('id');
        if(UserModel::where('id',$userId)->delete()){
            return $this->success('删除成功','userList');
        }
        $this->error('删除失败');
    }
}