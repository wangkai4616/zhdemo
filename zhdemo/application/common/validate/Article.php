<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/7
 * Time: 23:03
 */

namespace app\common\validate;

use think\Validate;
class Article extends Validate
{
    protected $rule = [
        'title|文章标题'=>'require|length:3,40',
        'content|文章内容'=>'require',
        'cate_id|栏目名称'=>'require',
        'user_id|作者'=>'require',
        //image为判断上传是否为图片
        'title_img|标题图片'=>'image'
    ];
}