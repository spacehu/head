<?php

namespace action\v6;

use mod\common as Common;
use TigerDAL;
use TigerDAL\AccessDAL;
use config\code;
use TigerDAL\Api\LogDAL;
use TigerDAL\Api\ImageDAL;

class ApiMaterial extends \action\RestfulApi {

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
    function getMaterialList() {
        try {
            if(empty($this->get['st'])){
                self::$data['success'] = false;
                self::$data['data']['code'] = 'emptyst';
                self::$data['msg'] = '类型码不能为空';
                return self::$data;
            }
            $currentPage = isset($this->get['currentPage']) ? $this->get['currentPage'] : 1;
            $pagesize = isset($this->get['pagesize']) ? $this->get['pagesize'] : \mod\init::$config['page_width'];
            $st=$this->get['st'];
            $enterprise_id=$this->enterprise_id;
            self::$data['data']['list'] = ImageDAL::getAll($currentPage, $pagesize,$enterprise_id, $st);
            self::$data['data']['total'] = ImageDAL::getTotal($this->enterprise_id, $st);
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::API_ENUM], code::API_ENUM, json_encode($ex));
            self::$data['success'] = false;
            self::$data['data']['code'] = json_encode($ex);
            self::$data['msg'] = code::$code['systemerror'];
        }
        return self::$data;
    }
}
