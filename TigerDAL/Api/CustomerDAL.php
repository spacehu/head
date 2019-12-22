<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;


/*
 * 用来返回生成首页需要的数据
 * 类
 * 访问数据库用
 * 继承数据库包
 */

class CustomerDAL {

    public static function saveImage($path,$enterprise_id,$open_id){
        $base=new BaseDAL();
        $_data=[
            'openid'=>$open_id,
            'original_src'=>$path,
            'add_time'=>date("Y-m-d H:i:s"),
            'enterprise_id'=>$enterprise_id,
            'starus'=>0,
        ];
        return $base->insert($_data,'user_info_head');
    }
}
