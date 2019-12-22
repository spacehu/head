<?php

namespace action\v6;

use mod\common as Common;
use TigerDAL\Api\CustomerDAL;
use config\code;

class ApiCustomer extends \action\RestfulApi {

    public $user_id;
    public $server_id;

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
    
    
    /** 保存头像数据 */
    function headImage(){
        try {
            $res = CustomerDAL::saveImage($this->post['path'],$this->enterprise_id,$this->get['openid']);
            //print_r($res);die;
            self::$data['data'] = $res;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }
    
}
