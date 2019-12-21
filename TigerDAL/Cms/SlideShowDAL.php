<?php

namespace TigerDAL\Cms;

use TigerDAL\BaseDAL;

class SlideShowDAL {

    /** 获取用户信息列表 */
    public static function getAll($currentPage, $pagesize,$enterprise_id) {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $where = "";
        if (!empty($enterprise_id)) {
            $where .= " and ss.enterprise_id= '".$enterprise_id."' ";
        }
        $sql = "select ss.*,e.name from " . $base->table_name("slide_show") . " as ss "
                ." left join " . $base->table_name("enterprise") . " as e on e.id=ss.enterprise_id "
                ." where ss.`delete`=0 " . $where . " "
                ." order by ss.edit_time desc "
                ." limit " . $limit_start . "," . $limit_end . " ;";
        return $base->getFetchAll($sql);
    }

    /** 获取数量 */
    public static function getTotal($enterprise_id) {
        $base = new BaseDAL();
        $where = "";
        if (!empty($enterprise_id)) {
            $where .= " and enterprise_id= '".$enterprise_id."' ";
        }
        $sql = "select count(1) as total from " . $base->table_name("slide_show") . " where `delete`=0 " . $where . " limit 1 ;";
        return $base->getFetchRow($sql)['total'];
    }

    /** 获取用户信息 */
    public static function getOne($id) {
        $base = new BaseDAL();
        $sql = "select ss.*,i.original_src from " . $base->table_name("slide_show") . " as ss "
                ." left join " . $base->table_name("image") . " as i on i.id=ss.image_id "
                ." where ss.`delete`=0 and ss.id=" . $id . "  limit 1 ;";
        return $base->getFetchRow($sql);
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
            $sql = "insert into " . $base->table_name('slide_show') . " values (null," . $set . ");";
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
            $sql = "update " . $base->table_name('slide_show') . " set " . $set . "  where id=" . $id . " ;";
            return $base->query($sql);
        } else {
            return true;
        }
    }

    /** 删除用户信息 */
    public static function delete($id) {
        $base = new BaseDAL();
        $sql = "update " . $base->table_name('slide_show') . " set `delete`=1  where id=" . $id . " ;";
        return $base->query($sql);
    }

}
