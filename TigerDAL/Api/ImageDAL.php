<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;

class ImageDAL {

    /** 获取用户信息列表 */
    public static function getImages($_ids) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("image") . " where `delete`=0 and id in (" . $_ids . ")  ;";
        $_res = $base->getFetchAll($sql);
        //\mod\common::pr($sql);
        if (empty($_res)) {
            return 'emptydata';
        }
        foreach ($_res as $k => $v) {
            $res[$v['id']] = $v;
        }
        return $res;
    }

    /** 获取用户信息列表 */
    public static function getAll($currentPage, $pagesize,$enterprise_id,$st="") {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $where = "";
        if (!empty($enterprise_id)) {
            $where .= " and enterprise_id= '".$enterprise_id."' ";
        }else{
            $where .= " and enterprise_id is null ";
        }
        if (!empty($st)) {
            $where .= " and st= '".$st."' ";
        }
        $sql = "select * from " . $base->table_name("image") . " where `delete`=0 " . $where . " order by edit_time desc limit " . $limit_start . "," . $limit_end . " ;";
        //echo $sql;die;
        return $base->getFetchAll($sql);
    }

    /** 获取数量 */
    public static function getTotal($enterprise_id,$st="") {
        $base = new BaseDAL();
        $where = "";
        if (!empty($enterprise_id)) {
            $where .= " and enterprise_id= '".$enterprise_id."' ";
        }else{
            $where .= " and enterprise_id is null ";
        }
        if (!empty($st)) {
            $where .= " and st= '".$st."' ";
        }
        $sql = "select count(1) as total from " . $base->table_name("image") . " where `delete`=0 " . $where . " limit 1 ;";
        return $base->getFetchRow($sql)['total'];
    }
}
