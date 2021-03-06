<?php

namespace TigerDAL\Cms;

use TigerDAL\BaseDAL;

class ImageDAL {

    /** 获取用户信息列表 */
    public static function getAll($currentPage, $pagesize,$enterprise_id, $keywords,$st="") {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $where = "";
        if (!empty($enterprise_id)) {
            $where .= " and enterprise_id= '".$enterprise_id."' ";
        }
        if (!empty($keywords)) {
            $where .= " and name like '%" . $keywords . "%' ";
        }
        if (!empty($st)) {
            $where .= " and st= '".$st."' ";
        }
        $sql = "select * from " . $base->table_name("image") . " where `delete`=0 " . $where . " order by edit_time desc limit " . $limit_start . "," . $limit_end . " ;";
        return $base->getFetchAll($sql);
    }

    /** 获取数量 */
    public static function getTotal($enterprise_id,$keywords,$st="") {
        $base = new BaseDAL();
        $where = "";
        if (!empty($enterprise_id)) {
            $where .= " and enterprise_id= '".$enterprise_id."' ";
        }
        if (!empty($keywords)) {
            $where .= " and name like '%" . $keywords . "%' ";
        }
        if (!empty($st)) {
            $where .= " and st= '".$st."' ";
        }
        $sql = "select count(1) as total from " . $base->table_name("image") . " where `delete`=0 " . $where . " limit 1 ;";
        return $base->getFetchRow($sql)['total'];
    }

    /** 获取用户信息 */
    public static function getOne($id) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("image") . " where `delete`=0 and id=" . $id . "  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 获取用户信息 */
    public static function getByName($name) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("image") . " where `delete`=0 and name='" . $name . "'  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 新增返回id */
    public static function insert_return_id($data) {
        self::insert($data);
        $base = new BaseDAL();
        return $base->last_insert_id();
    }

    /** 新增用户信息 */
    public static function insert($data) {
        $base = new BaseDAL();
        if (is_array($data)) {
            foreach ($data as $v) {
                if (is_numeric($v)) {
                    $_data[] = " " . $v . " ";
                } else if (empty($v)) {
                    $_data[] = " null ";
                } else {
                    $_data[] = " '" . $v . "' ";
                }
            }
            $set = implode(',', $_data);
            $sql = "insert into " . $base->table_name('image') . " values (null," . $set . ");";
            //echo $sql;die;
            return $base->query($sql);
        } else {
            return true;
        }
    }

    /** 更新用户信息 */
    public static function update($id, $data) {
        $base = new BaseDAL();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if (is_numeric($v)) {
                    $_data[] = " `" . $k . "`=" . $v . " ";
                } else if (empty($v)) {
                    $_data[] = " `" . $k . "`= null ";
                } else {
                    $_data[] = " `" . $k . "`='" . $v . "' ";
                }
            }
            $set = implode(',', $_data);
            $sql = "update " . $base->table_name('image') . " set " . $set . "  where id=" . $id . " ;";
            return $base->query($sql);
        } else {
            return true;
        }
    }

    /** 删除用户信息 */
    public static function delete($id) {
        $base = new BaseDAL();
        $sql = "update " . $base->table_name('image') . " set `delete`=1  where id=" . $id . " ;";
        return $base->query($sql);
    }

}
