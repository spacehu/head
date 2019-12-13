<?php

namespace TigerDAL\cli;

use TigerDAL\BaseDAL;

class MessageQueueDAL {

    /** 获取用户信息列表 */
    public static function getAll() {
        $base = new BaseDAL("cli");
        $sql = "select * from " . $base->table_name("message_queue") . "  "
                . "where`status`=0  "
                . "order by add_time desc "
                . " ;";
        return $base->getFetchAll($sql);
    }

    /** 获取数量 */
    public static function getTotal() {
        $base = new BaseDAL("cli");
        $sql = "select count(1) as total from " . $base->table_name("message_queue") . " where `status`=0  limit 1 ;";
        return $base->getFetchRow($sql)['total'];
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
            $sql = "insert into " . $base->table_name('message_queue') . " values (null," . $set . ");";
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
            $sql = "update " . $base->table_name('message_queue') . " set " . $set . "  where id=" . $id . " ;";
            return $base->query($sql);
        } else {
            return true;
        }
    }
    

}
