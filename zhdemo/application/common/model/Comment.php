<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/19
 * Time: 21:21
 */

namespace app\common\model;

use think\Model;
class Comment extends Model
{
    //设置默认主键
    protected $pk = 'id';
    //设置关联数据表
    protected $table = 'zh_user_comments';
    //开启自动时间戳
    protected $autoWriteTimestamp = true;
    //设置新增时间
    protected $createTime = 'create_time';
    //设置更新时间
    protected $updateTime = 'update_time';
    //设置时间格式
    protected $dateFormat = 'Y年m月d日 H:i';
    //开启自动写入
    protected $auto = [];
    //新增时写入
    protected $insert = ['create_time','status'=>1];
    //更新时写入
    protected $update = ['update_time'];

}