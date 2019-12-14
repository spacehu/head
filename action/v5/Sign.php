<?php

namespace action\v5;

use mod\common as Common;
use TigerDAL\Api\SignDAL;
use TigerDAL\Api\LogDAL;
use config\code;

class Sign extends \action\RestfulApi {

    /**
     * 主方法引入父类的基类
     * 责任是分担路由的工作
     */
    function __construct() {
        $path = parent::__construct();
        if (!empty($path)) {
            $_path = explode("-", $path);
            $actEval = "\$res = \$this ->" . $_path['2'] . "();";
            eval($actEval);
            exit(json_encode($res));
        }
    }

    /** 检查手机号是否已用 */
    function add() {
        try {
            $enterprise_id = Common::specifyChar($this->post['enterprise_id']);
            $name = Common::specifyChar($this->post['name']);
            $SignDAL = new SignDAL();
            $check = $SignDAL->add($enterprise_id,$name);
            if ($check !== true) {
                self::$data['success'] = false;
                self::$data['data']['code'] = $check;
                self::$data['msg'] = code::$code[$check];
                return self::$data;
            }
            self::$data['data'] = $check;
            return self::$data;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
    }


}
