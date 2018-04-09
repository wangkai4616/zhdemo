<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/13
 * Time: 10:13
 */
namespace app\admin\common\controller;
use think\Controller;
use think\facade\Session;
class Base extends Controller
{
    protected function initialize()
    {

    }

    protected function islogin()
    {
        if(!Session::has('user_id')){
            $this->error('请先登录','admin/user/login');
        }
    }

}