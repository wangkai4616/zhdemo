<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/19
 * Time: 14:52
 */

namespace app\admin\controller;

use app\common\controller\Base;
use app\admin\common\model\Site as SiteModel;
use think\facade\Request;
class Site extends Base
{
    public function index()
    {
        $siteInfo = SiteModel::where('status','1')->find();

        $this->view->assign('title','网站管理');
        $this->view->assign('siteInfo',$siteInfo);
        return $this->view->fetch('site');
    }

    public function save()
    {
        $data = Request::param();
//        halt($data);
        if(SiteModel::update($data)){
            $this->success('保存成功','index');
        }else{
            $this->error('保存失败请检查','index');
        }
    }
}