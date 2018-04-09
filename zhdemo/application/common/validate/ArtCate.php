<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/7
 * Time: 23:03
 */

namespace app\common\validate;

use think\Validate;
class ArtCate extends Validate
{
    protected $rule = [
        'name|文章标题'=>'require|length:5,20|chsAlpha',
    ];
}