<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/1
 * Time: 16:58
 */
namespace app\common\model;

use think\Model;
class User extends Model
{
    //默认主键
    protected $pk='id';
    //设置数据表
    protected $table='zh_user';
    //开启自动时间戳autoWriteTimestamp
    protected $autoWriteTimestamp = true;
    //开启时间戳自带创建时间和更新时间
    //为创建时间，更新时间绑定字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    //设置时间格式
    protected $dateFormat = 'Y年m月d日';
    //获取器(get字段名Attr),数据从数据库读出后对数据进行处理
    protected function getStatusAttr($value){
        $status = ['1'=>'启用','0'=>'禁用'];
        return $status[$value];
    }
//    protected function getIsAdminAttr($value){
//        $isAdmin = ['1'=>'管理员','0'=>'普通会员'];
//        return $isAdmin[$value];
//    }
    //修改器(set字段名Attr),数据写入数据库前对数据进行处理
    protected function setPasswordAttr($value){
        return sha1($value);
    }
}