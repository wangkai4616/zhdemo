<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/16
 * Time: 10:52
 */

namespace app\admin\controller;

use app\common\controller\Base;
use app\admin\common\model\Article as ArtModel;
use think\facade\Request;
use think\facade\Session;
use app\admin\common\model\Cate;
class Article extends Base
{
    public function index()
    {
        $this->islogin();

        $this->redirect('artList');
    }
    public function artList()
    {
        $id = Session::get('user_id');
        $isAdmin = Session::get('is_admin');
        if($isAdmin==1){
            $artList = ArtModel::paginate(5);
        }else{
            $artList = ArtModel::where('id',$id)->paginate(5);
        }
        $this->view->assign('title','文章管理');
        $this->view->assign('empty','没有发布文章');
        $this->view->assign('artList',$artList);

        return $this->view->fetch('artList');
    }

    public function artEdit()
    {
        $artId = Request::param('id');
//        halt($artId);
        $cateList = Cate::all();
        $artData = ArtModel::where('id',$artId)->find();
//        halt($artData);
        $this->view->assign('artList',$cateList);
        $this->view->assign('title','文章编辑');
        $this->view->assign('artDa',$artData);
        return $this->view->fetch('artedit');
    }

    public function doEdit()
    {
        $data = Request::param();
        $file = Request::file('title_img');
        //文件验证成功后进行move,$info为已经移动到服务器的文件名，移动默认目录为public
        $info = $file->validate([
            'size'=>1000000,
            'ext'=>'jpeg,jpg,png,gif',
        ])->move('uploads');
        //上传成功，变脸保存为服务器文件名
        if($info){
            //将服务器文件名赋值给$data数组
            $data['title_img'] = $info->getSaveName();
        }else{
            //getError为上传错误信息
            $this->error($file->getError());
        }
        //数据写入数据库
        if(ArtModel::update($data)){
            $this->success('文章更新成功','artList');
        }else{
            $this->error('文章更新失败');
        }
    }

    public function doDelete()
    {
        $id = Request::param('id');

        if(ArtModel::where('id',$id)->delete()){
            $this->success('删除成功','artList');
        }else{
            $this->error('删除失败');
        }
    }
}