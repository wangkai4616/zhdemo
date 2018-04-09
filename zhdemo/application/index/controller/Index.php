<?php
namespace app\index\controller;
//use think\facade\Config;
use app\common\controller\Base;
use app\common\model\ArtCate;
use app\common\model\Article;
use think\facade\Request;
use think\Db;
use app\common\model\Comment;
use think\Session;

class Index extends Base
{
    //主页
    public function index()
    {
//        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) 2018新年快乐</h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
//          $res = Config::get();
//          dump($res);
//          return;
        //搜索与代码整合
        //创建数组查询
        $map = [];
        //条件一
        $map[] = ['status','=',1];
        //获取get提交的查询内容
        $keywords = Request::param('keywords');
        //判断是否有值
        if(!empty($keywords)){
            //条件二：like为模糊查询, 格式为 ‘%’.变量.‘%’
            $map[] = ['title','like','%'.$keywords.'%'];
        }


        //从URL参数获取当前 cate_id
        $cateId = Request::param('cate_id');
        //判断用户是否点击产生 cate_id，如果有通过模型查询获得当前栏目名称并显示
        if(isset($cateId)){
            //条件三：
            $map[] = ['cate_id','=',$cateId];
            $res = ArtCate::get($cateId);
            $artList = Db::table('zh_article')
                ->where($map)
                ->order('create_time', 'desc')
                ->paginate(3);
            $this->assign('cateName',$res->name);
        }else{
            $this->assign('cateName','大千世界，无奇不有');
            $artList = Db::table('zh_article')
                ->where($map)
                ->order('create_time', 'desc')
                ->paginate(3);
        }
        $this->view->assign('artList',$artList);
        //渲染
        return $this->view->fetch('index',['title'=>'简客，不一样的生活']);

//        $artList = Article::All(function ($query) use ($cateId){
//            if(isset($cateId)){
//                $query->where('status',1)
//                    ->where('cate_id',$cateId)
//                    ->order('create_time','desc');
//            }
//                $query->where('status',1)
//                    ->order('create_time','desc');
//
//        });
        //通过Db类查询获取文章信息
    }
    //栏目操作
    public function insert()
    {
        //防止未登陆
        $this->islogin();
        //模板赋值
        $this->assign('title','文章发布');
        //获取该模型绑定数据表所有数据
        $cateList = ArtCate::all();
        //是否获取到数据
        if(count($cateList)>0){
            $this->assign('cateList',$cateList);
        }else{
            $this->error('请先添加栏目','index/index');
        }
        //模板渲染
        return $this->fetch('insert');
    }
    //文章发布逻辑
    public function save()
    {
        if(Request::isPost()){
            $data = Request::post();
            //数据验证，正确返回true,错误返回错误信息
            $res = $this->validate($data,'app\common\validate\Article');
            if(true !== $res){
                echo '<script>alert("'.$res.'");location.back();</script>';
            }else{
                //文件上传，$file为文件源名
                $file = Request::file('title_img');
                //文件验证成功后进行move,$info为已经移动到服务器的文件名，移动默认目录为public
                $info = $file->validate([
                    'size'=>100000,
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
                if(Article::create($data)){
                    $this->success('文章发布成功','index/index');
                }else{
                    $this->error('文章发布失败');
                }
            }
        }else{
            $this->error('请求类型错误');
        }
    }
    //详情页
    public function detail()
    {
        //根据upassinfo方法传递的参数
        $artId = Request::param('id');
        //查询获取单条数据 setInc 查询一次 自增一次 pv
        $art = Article::get(function ($query) use ($artId){
            $query->where('id',$artId)->setInc('pv');
        });
        if(!is_null($art)){
            $this->view->assign('art',$art);
        }
        $this->view->assign('commentList',Comment::all(function($query)use($artId) {
            $query->where('status',1)
                ->where('art_id',$artId)
                ->order('create_time','desc');
        }));
        $this->view->assign('title','详情页');
        $this->view->assign('empty','还没有评论快来抢沙发吧');
        return $this->view->fetch('detail');
    }
    //收藏
    public function fav()
    {
//        return ['status'=>1,'message'=>'成功'];
        if(Request::isAjax()){
            $data = Request::get();
//            halt($data);
            if(empty($data['session_id'])){
                return ['status'=>-2,'message'=>'请登录后再收藏'];
            }
            $map = [];
            $map[] = ['user_id','=',$data['user_id']];
            $map[] = ['art_id','=',$data['article_id']];

            $fav = Db::table('zh_user_fav')->where($map)->find();
            if(is_null($fav)){
                Db::table('zh_user_fav')->data([
                    'user_id'=>$data['user_id'],
                    'art_id'=>$data['article_id']
                ])->insert();
                return ['status'=>1,'message'=>'已收藏'];
            }else{
                Db::table('zh_user_fav')->where($map)->delete();
                return ['status'=>0,'message'=>'已取消'];
            }
        }else{
            return ['status'=>-1,'message'=>'请求类型错误'];
        }
    }

    public function comment()
    {
//        $this->islogin();
        if(Request::isAjax()){
            $data = Request::param();
            if(Comment::create($data,true)){
                return ['status'=>1,'message'=>'评论成功'];
            }else{
                return ['status'=>0,'message'=>'评论失败'];
            }
        }
    }

//    public function hello($name = 'ThinkPHP5')
//    {
//        return 'hello,' . $name;
//    }
}
