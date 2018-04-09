<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15
 * Time: 11:14
 */

namespace app\admin\common\model;

use think\Model;
class Cate extends Model
{
    //默认主键
    protected $pk='id';
    //设置数据表
    protected $table='zh_article_category';
    //开启自动时间戳autoWriteTimestamp
    protected $autoWriteTimestamp = true;
    //开启时间戳自带创建时间和更新时间
    //为创建时间，更新时间绑定字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    //设置时间格式
    protected $dateFormat = 'Y年m月d日';
    //获取器(get字段名Attr),数据从数据库读出后对数据进行处理

    //开启自动写入
    protected $auto = [];
    //新增时写入
    protected $insert = ['create_time','status'=>1];
   //更新时写入
    protected $update = ['update_time'];
}