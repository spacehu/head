<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;


/**
 * Class LinYuDAL
 *
 * Created on 17/10/17.
 * 短信服务API产品的DEMO程序,工程中包含了一个SmsDemo类，直接通过
 * 执行此文件即可体验语音服务产品API功能(只需要将AK替换成开通了云通信-短信服务产品功能的AK即可)
 * 备注:Demo工程编码采用UTF-8
 */
class LinYuSmsDAL {

    private static $userid; // 1400开头
    private static $account;
    private static $password;  // NOTE: 这里的模板ID`7839`只是一个示例，真实的模板ID需要在短信控制台中申请
    private static $url;
    private static $auth;
    
    function __construct(){
        self::$userid = \mod\init::$config['env']['linyu']['userid'];
        self::$account = \mod\init::$config['env']['linyu']['account'];
        self::$password = \mod\init::$config['env']['linyu']['password'];
        self::$url = \mod\init::$config['env']['linyu']['url'];
        self::$auth="&userid=".self::$userid."&account=".self::$account."&password=".self::$password;
    }
    /**
     * 发送短信
     * @return stdClass
     */

    public static function sendSms($phone, $content, $sendTime="",$extno="") {
        

        // 指定模板ID单发短信
        try {
            $url=self::$usl."?action=send".self::$auth."&mobile=".$phone."&content=".$content."&sendTime=".$sendTime."&extno=".$extno."";
            $xmls=file_get_contents($url);
            $xml =simplexml_load_string($xmls);
            $xmljson= json_encode($xml);
            $result=json_decode($xmljson,true);
            //var_dump($result);

        } catch (\Exception $e) {
            echo var_dump($e);
        }
        return $result;
    }
    /** 插入发送记录 */
    public static function insert($data) {
        $base = new BaseDAL();
        if (is_array($data)) {
            $base->insert($data,'sms');
            return $base->last_insert_id();
        } else {
            return true;
        }
    }

    /** 更新用户信息 */
    public static function update($id, $data) {
        $base = new BaseDAL();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if ($k == 'success') {
                    $_data[] = " `" . $k . "`=" . $v . " ";
                } else {
                    $_data[] = " `" . $k . "`='" . $v . "' ";
                }
            }
            $set = implode(',', $_data);
            $sql = "update " . $base->table_name('sms') . " set " . $set . "  where id=" . $id . " ;";
            //echo $sql;
            return $base->query($sql);
        } else {
            return true;
        }
    }

}
