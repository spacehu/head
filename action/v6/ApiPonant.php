<?php

namespace action\v6;

use mod\common as Common;
use TigerDAL;
use TigerDAL\AccessDAL;
use config\code;
use TigerDAL\Api\LogDAL;

class ApiPonant extends \action\RestfulApi {

    /**
     * 主方法引入父类的基类
     * 责任是分担路由的工作
     */
    function __construct() {
        $path = parent::__construct();
        if (!empty($path)) {
            $_path = explode("-", $path);
            $mod= $_path['2'];
            $res=$this->$mod();
            exit(json_encode($res));
        }
    }

    /** 
     * 校验bin码
     * 维度bin码
     */
    function checkBin() {
        try {
            if(empty($this->get['bin'])){
                self::$data['success'] = false;
                self::$data['data']['code'] = 'emptybin';
                self::$data['msg'] = 'bin码不能为空';
                return false;
            }
            $bin=$this->get['bin'];
            if($bin==\mod\init::$config['env']['data']['bin']){
                self::$data['data']['code'] = 'successbin';
                self::$data['msg'] = '校验成功';
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::API_ENUM], code::API_ENUM, json_encode($ex));
            self::$data['success'] = false;
            self::$data['data']['code'] = json_encode($ex);
            self::$data['msg'] = code::$code['systemerror'];
        }
        return self::$data;
    }

    /** 
     * 保存用户信息 
     * 维度
     * name
     * phone
     * card
     * time
     */
    function saveUserInfo() {
        try {
            $response = LinYuSmsDAL::getSmsInfo();
            echo "查询短信发送情况(querySendDetails)接口返回的结果:\n";
            var_dump($response);
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::API_ENUM], code::API_ENUM, json_encode($ex));
            echo json_encode(['success' => false, 'message' => '999']);
        }
    }

}
