<?php

/**
 * restful api 的基类
 * 用来获取请求的头内容并解析下发给路由
 */

namespace action;

use mod\common as Common;
use TigerDAL;
use TigerDAL\Api\SmsDAL;
use TigerDAL\AccessDAL;
use config\code;
use TigerDAL\Web\StatisticsDAL;
use TigerDAL\Api\LogDAL;
use TigerDAL\Api\EnterpriseDAL;

class RestfulApi {

    public $_method = 'GET';
    public $_path = '';
    public static $data = [
        'success' => true,
        'data' => '',
    ];
    public $header;
    public $post;
    public $get;
    public $enterprise_id;

    /**
     * 整理路由的方法
     * @return boolean
     */
    function __construct() {
        $this->_method = $this->getMethod();
        $this->_path = $this->getPath();
        $this->header = Common::exchangeHeader();
        $this->post = Common::exchangePost();
        $this->get = Common::exchangeGet();
        $this->insertStatistics($_SERVER);
        try {
            if(!empty($this->get['eCode'])){
                $_enterprise = EnterpriseDAL::getByCode($this->get['eCode']);
                if (!empty($_enterprise)) {
                    $this->enterprise_id = $_enterprise['id'];
                } else {
                    $this->enterprise_id = '';
                }
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_INDEX], code::CATEGORY_INDEX, json_encode($ex));
        }
        if (\mod\init::$config['restful_api']['isopen']) {
            try {
                LogDAL::save(date("Y-m-d H:i:s") . "-------------------------------------" . $this->_path . "", "DEBUG");
                LogDAL::save(date("Y-m-d H:i:s") . "-------------------------------------" . Common::getIP() . "", "DEBUG");
                LogDAL::save(date("Y-m-d H:i:s") . "-------------------------------------" . json_encode($this->post) . "", "DEBUG");
                if (!empty(\mod\init::$config['restful_api']['path'][$this->_method . ' ' . $this->_path])) {
                    return \mod\init::$config['restful_api']['path'][$this->_method . ' ' . $this->_path];
                } else {
                    self::$data['success'] = false;
                    self::$data['data']['code'] = "url is wrong.";
                    self::$data['msg'] = code::$code['urlerror'];
                    exit(json_encode(self::$data));
                }
            } catch (Exception $ex) {
                self::$data['success'] = false;
                self::$data['data']['code'] = json_encode($ex);
                self::$data['msg'] = code::$code['systemerror'];
                exit(json_encode(self::$data));
            }
        } else {
            return false;
        }
        //Common::pr($this);
        exit();
    }

    function __destruct() {
        LogDAL::_saveLog();
    }

    /** 记录日志 */
    private function insertStatistics($method) {
        $access = new AccessDAL();
        $statistics = new StatisticsDAL();
        $data = [
            'ip' => $access->getIP(),
            'machine' => $access->getOS(),
            'browser' => $access->getBrowse(),
            'user_id' => !empty(Common::getSession("user_id")) ? Common::getSession("user_id") : "",
            'action' => $_GET['a'],
            'model' => $_GET['m'],
            'page' => $_GET['m'],
            'page_url' => $method['HTTP_HOST'] . $method['REQUEST_URI'],
            'add_time' => date("Y-m-d H:i:s"),
            'channel_type' => !empty($this->header['channel_code']) ? $this->header['channel_code'] : '',
        ];
        $statistics->insert($data);
    }

    /** 基类 测试用 -废弃 */
    function get() {
        try {
            //Common::pr($_SERVER);
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::API_ENUM], code::API_ENUM, json_encode($ex));
            echo json_encode(['success' => false, 'message' => '999']);
        }
    }

    /** 获取请求头 */
    function getMethod() {
        try {
            return $_SERVER['REQUEST_METHOD'];
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::API_ENUM], code::API_ENUM, json_encode($ex));
            self::$data['success'] = false;
            self::$data['data']['code'] = json_encode($ex);
            self::$data['msg'] = code::$code['systemerror'];
            echo json_encode(self::$data);
        }
    }

    /** 获取请求路径 */
    function getPath() {
        try {
            $_path = explode('?', $_SERVER['REQUEST_URI']);
            if (!empty($_path[1])) {
                $get = explode('&', $_path[1]);
                if (!empty($get)) {
                    foreach ($get as $v) {
                        $ac = explode("=", $v);
                        $_GET[$ac[0]] = $ac[1];
                    }
                }
            }
            return $_path[0];
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::API_ENUM], code::API_ENUM, json_encode($ex));
            self::$data['success'] = false;
            self::$data['data']['code'] = json_encode($ex);
            self::$data['msg'] = code::$code['systemerror'];
            echo json_encode(self::$data);
        }
    }

}
