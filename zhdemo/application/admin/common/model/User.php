<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/13
 * Time: 20:23
 */
namespace app\admin\common\model;

use think\Model;
class User extends Model
{
    protected $pk = 'id';
    protected $table = 'zh_user';

//    protected function getIsAdminAttr($value)
//    {
//        $isAdmin = ['1'=>'超级管理员','0'=>'注册会员'];
//        return $isAdmin[$value];
//    }

}