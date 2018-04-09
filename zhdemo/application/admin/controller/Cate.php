<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15
 * Time: 12:58
 */

namespace app\admin\controller;

use app\admin\common\controller\Base;
use think\facade\Request;
use think\facade\Session;
use app\admin\common\model\Cate as CateModel;
class Cate extends Base
{
    public function index()
    {
        $this->islogin();

        return $this->redirect('cateList');
    }

    public function cateList()
    {
        $cateList = CateModel::all();

        $this->view->assign('title','分类管理');
        $this->view->assign('empty','<span style="color: red">没有分类</span>');
        $this->view->assign('cateList',$cateList);

        return $this->view->fetch('cateList');
    }

    public function cateEdit()
    {
        $id = Request::param('id');
        $cateDa = CateModel::where('id',$id)->find();
//        halt($cateDa);

        $this->view->assign('title','分类编辑');
        $this->view->assign('cateDa',$cateDa);
        return $this->view->fetch('cateedit');
    }

    public function doEdit()
    {
        $data = Request::post();
//        halt($data);
        $id = $data['id'];
        unset($data['id']);
        if(CateModel::where('id',$id)->data($data)->update()){
            return $this->success('更新成功','cateList');
        }else{
            $this->error('更新失败');
        }
    }

    public function cateAdd()
    {
        return $this->view->fetch('cateadd',['title'=>'添加分类']);
    }

    public function doAdd()
    {
        $data = Request::param();
//        halt($data);
        if(CateModel::create($data)){
            $this->success('添加成功','cateList');
        }else{
            $this->error('添加失败');
        }
    }

    public function doDelete()
    {
        $id = Request::param('id');
//        halt($id);
        if(CateModel::where('id',$id)->delete()){
            $this->success('删除成功','cateList');
        }else{
            $this->error('删除失败');
        }
    }
}