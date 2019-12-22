<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;

/*
 * 用来返回生成首页需要的数据
 * 类
 * 访问数据库用
 * 继承数据库包
 */

class SlideShowDAL {

    function __construct() {
        
    }

    /** 获取轮播图片 */
    public function GetSlideShow($currentPage, $pagesize,$enterprise_id) {

        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;

        $where="";
        if(!empty($enterprise_id)){
            $where .= " and s.enterprise_id= ".$enterprise_id." ";
        }else{
            $where .= " and s.enterprise_id is null ";
        }
        $base = new BaseDAL();
        $sql = "select s.*,i.original_src from " . $base->table_name("slide_show") . " as s , " . $base->table_name("image") . " as i where s.`delete`=0 and i.`delete`=0 and s.image_id=i.id ".$where." order by s.order_by asc, s.add_time desc limit " . $limit_start . "," . $limit_end . "";
        $data = $base->getFetchAll($sql);

        $sql = "select count(1) as num from " . $base->table_name("slide_show") . " as s , " . $base->table_name("image") . " as i where s.`delete`=0 and i.`delete`=0 and s.image_id=i.id ".$where."   ";
        $total = $base->getFetchRow($sql);

        return ['data' => $data, 'total' => $total['num']];
    }

}
