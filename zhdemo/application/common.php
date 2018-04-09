<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Db;

// 应用公共文件
//对主页面文章内容进行过滤
function getArtContent($content)
{
    //strip（除去）_tags（标签）（）剥离HTML标签，mb_substr（）中文截取（首位，长度）
    return mb_substr(strip_tags($content),0,20).'...';
}

//function_exists判断当前函数是否存在，如果存在执行，不存在不执行
if(!function_exists('getUserName')){
    function getUserName($id)
    {
        return Db::table('zh_user')
            ->where('id',$id)
            ->value('name');
    }
}

if(!function_exists('getCateName')){
    function getCateName($cate_id)
    {
        return Db::table('zh_article_category')
            ->where('id',$cate_id)
            ->value('name');
    }
}




