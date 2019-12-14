<?php

namespace TigerDAL\Cms;

use TigerDAL\BaseDAL;

class SiteInfomationDAL {

    /** 获取用户信息列表 */
    public static function getAll($currentPage, $pagesize, $keywords, $enterprise_id = '') {
        $base = new BaseDAL();
        $limit_start = ($currentPage - 1) * $pagesize;
        $limit_end = $pagesize;
        $where = "";
        if (!empty($keywords)) {
            $where .= " and (name like '%" . $keywords . "%' ) ";
        }
        if ($enterprise_id !== '') {
            $where .= " and enterprise_id=" . $enterprise_id . " ";
        }
        $sql = "select * "
                . " from " . $base->table_name("site_infomation") . " "
                . " where `status`<2 " . $where . " "
                . " order by `status` asc,add_time desc limit " . $limit_start . "," . $limit_end . " ;";
        //echo $sql;
        return $base->getFetchAll($sql);
    }

    /** 获取数量 */
    public static function getTotal($keywords, $enterprise_id = '') {
        $base = new BaseDAL();
        $where = "";
        if (!empty($keywords)) {
            $where .= " and (name like '%" . $keywords . "%' ) ";
        }
        if ($enterprise_id !== '') {
            $where .= " and enterprise_id=" . $enterprise_id . " ";
        }
        $sql = "select count(1) as total "
                .  " from " . $base->table_name("site_infomation") . " "
                . " where status<2 " . $where . " ;";
        return $base->getFetchRow($sql)['total'];
    }

    /** 获取用户信息 */
    public static function getOne($id) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("site_infomation") . " where id=" . $id . "  limit 1 ;";
        return $base->getFetchRow($sql);
    }

    /** 获取用户信息 */
    public static function getByUserIdOne($id) {
        $base = new BaseDAL();
        $sql = "select * from " . $base->table_name("site_infomation") . " where user_id=" . $id . "  limit 1 ;";
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
            $sql = "insert into " . $base->table_name('site_infomation') . " values (null," . $set . ");";
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
            $sql = "update " . $base->table_name('site_infomation') . " set " . $set . "  where id=" . $id . " ;";
            return $base->query($sql);
        } else {
            return true;
        }
    }
    
    /** 删除用户企业关系信息 */
    public static function delete($id) {
        $base = new BaseDAL();
        $sql = "update " . $base->table_name('site_infomation') . " set `status`=2  where id=" . $id . " ;";
        return $base->query($sql);
    }

    
    public static function saveSiteInfomationStatus($ids, $data) {
        if (empty($data)) {
            return true;
        }
        $base = new BaseDAL();
        // 删除
        $sql = "update " . $base->table_name('site_infomation') . " set `status`=".$data['status']." where `id` in (" . $ids . ")  ;";
        //echo $sql;die;
        return $base->query($sql);
    }

}
