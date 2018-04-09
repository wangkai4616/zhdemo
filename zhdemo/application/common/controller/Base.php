<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/28
 * Time: 12:39
 */
namespace app\common\controller;

use think\Controller;
use think\facade\Session;
use app\common\model\ArtCate;
use app\admin\common\model\Site;
use think\facade\Request;
use app\common\model\Article;
class Base extends Controller
{
    //初始化方法，最先被执行的方法
    protected function initialize()
    {
        //实例化栏目信息
        $this->showNav();
        //检测是否关闭
        $this->isOpen();

        $this->getHotList();
    }
    //防止重复登录
    protected function Logined()
    {
        if(Session::has('user_id')){
            $this->error('您已登录','index/index');
        }
    }
    //判断当前是否登录
    protected function islogin()
    {
        if(!Session::has('user_id')){
            $this->error('请先登录','user/login');
        }
    }
    //通过模型获取所有栏目信息，并赋值，$query为查询对象
    protected function showNav()
    {
        $cateList = ArtCate::all(function ($query){
            $query->where('status',1)
                ->order('sort','asc');
        });
        $this->assign('cateList',$cateList);
    }

    protected function isOpen()
    {
        $isOpen = Site::where('status',1)->value('is_open');

        if($isOpen ==0 && Request::module() == 'index'){
            $info = <<<  'INFO'
<body style="background-color: #333">
<h1 style="color: #eee;text-align: center;">站点维护中...</h1>
</body>
INFO;

            exit($info);
        }
    }
    protected function isReg()
    {
        $isReg = Site::where('status',1)->value('is_reg');

        if($isReg ==0 && Request::module() == 'index') {
            $info = <<<  'INFO'
<body style="background-color: #333">
<h1 style="color: #eee;text-align: center;">站点维护中...</h1>
</body>
INFO;

            exit($info);
        }
    }

    protected function getHotList()
    {
        $hotArtList = Article::where('status',1)->order('pv','desc')->limit(12)->select();
        $this->view->assign('hotArtList',$hotArtList);
    }

}


