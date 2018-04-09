<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15
 * Time: 11:14
 */

namespace app\admin\common\model;

use think\Model;
class Article extends Model
{
    protected $pk = 'id';
    protected $table = 'zh_article';
}