<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/13
 * Time: 10:23
 */


namespace app\admin\controller;

use app\admin\common\controller\Base;
use think\facade\Request;
class Index extends Base
{
    public function index()
    {
        $this->islogin();
        $this->assign('title','后台首页');
        return $this->redirect('user/userList');
    }


}