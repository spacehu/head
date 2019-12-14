<?php

namespace action\v6;

use mod\common as Common;
use TigerDAL;
use TigerDAL\Api\LinYuSmsDAL;
use TigerDAL\Api\AuthDAL;
use TigerDAL\AccessDAL;
use config\code;
use TigerDAL\Web\StatisticsDAL;
use TigerDAL\Api\LogDAL;

class ApiSms extends \action\RestfulApi {

    /**
     * 主方法引入父类的基类
     * 责任是分担路由的工作
     */
    function __construct() {
        $path = parent::__construct();
        if (!empty($path)) {
            $_path = explode("-", $path);
            $res=$this->$_path['2']();
            exit(json_encode($res));
        }
    }

    /** 异步 */
    function sendSms() {
        $phone = !empty($this->get['phone']) ? $this->get['phone'] : 0;
        try {
            if (empty($phone)) {
                TigerDAL\CatchDAL::markError(code::$code[code::API_ENUM], code::API_ENUM, json_encode('phone empty'));
                self::$data['success'] = false;
                self::$data['data']['code'] = "phone empty";
                self::$data['msg'] = code::$code['phoneerror'];
                return self::$data;
            }
            $code = rand(10000, 99999);
            //判斷是否可以發送
            //插入发送记录
            $access = new AccessDAL();
            $data = [
                'phone' => $phone,
                'date' => date("Ymd"),
                'code' => $code,
                'bizid' => '',
                'add_time' => date("Y-m-d H:i:s", time()),
                'success' => false,
                'ip' => $access->getIP(),
            ];
            //Common::pr($data);
            $orderid = LinYuSmsDAL::insert($data);
            //发送信息
            $response = LinYuSmsDAL::sendSms($phone, $code);
            //记录成功
            //Common::pr($response);die;
            if($response['returnstatus']="Success"){
                $_data = [
                    'bizid' => $response['taskID'],
                    'success' => 1,
                ];
                self::$data['data'] = LinYuSmsDAL::update($orderid, $_data);
            }else if($response['returnstatus']="Success"){
                $_data = [
                    'bizid' => $response['taskID'],
                    'success' => 0,
                ];
                LogDAL::save(date("Y-m-d H:i:s") . "-sms---" . json_encode($response) . "");
                self::$data['data'] = LinYuSmsDAL::update($orderid, $_data);
            }else{
                Common::pr($response);die;
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::API_ENUM], code::API_ENUM, json_encode($ex));
            self::$data['success'] = false;
            self::$data['data']['code'] = json_encode($ex);
            self::$data['msg'] = code::$code['systemerror'];
        }
        return self::$data;
    }

    /** 异步 */
    function getSms() {
        try {
            $phone = "13564138770";
            $date = "20180105";
            $bizid = "226804715121129486^0";
            $response = SmsDAL::querySendDetails($phone, $date, $bizid);
            echo "查询短信发送情况(querySendDetails)接口返回的结果:\n";
            print_r($response);
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::API_ENUM], code::API_ENUM, json_encode($ex));
            echo json_encode(['success' => false, 'message' => '999']);
        }
    }

}
