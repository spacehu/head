<?php

namespace action;

use mod\common as Common;
use TigerDAL;
use TigerDAL\Cms\SiteInfomationDAL;
use TigerDAL\Cms\EnterpriseDAL;
use TigerDAL\cli\MessageQueueDAL;
use config\code;

class siteInfomation {

    private $class;
    public static $data;
    private $enterprise_id;

    function __construct() {
        $this->class = str_replace('action\\', '', __CLASS__);
        try {
            $_enterprise = EnterpriseDAL::getByUserId(Common::getSession("id"));
            if (!empty($_enterprise)) {
                $this->enterprise_id = $_enterprise['id'];
            } else {
                $this->enterprise_id = '';
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_INDEX], code::CATEGORY_INDEX, json_encode($ex));
        }
    }

    function index() {
        //Common::pr(date("Y-m-d H:i:s"));die;
        Common::isset_cookie();
        Common::writeSession($_SERVER['REQUEST_URI'], $this->class);
        //Common::pr(Common::getSession($this->class));die;
        $currentPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 1;
        $pagesize = isset($_GET['pagesize']) ? $_GET['pagesize'] : \mod\init::$config['page_width'];
        $keywords = isset($_GET['keywords']) ? $_GET['keywords'] : "";
        try {
            self::$data['data'] = SiteInfomationDAL::getAll($currentPage, $pagesize, $keywords, $this->enterprise_id);
            self::$data['total'] = SiteInfomationDAL::getTotal($keywords, $this->enterprise_id);

            self::$data['currentPage'] = $currentPage;
            self::$data['pagesize'] = $pagesize;
            self::$data['keywords'] = $keywords;
            self::$data['class'] = $this->class;
            self::$data['enterprise_id']=$this->enterprise_id;
            //echo $this->enterprise_id;die;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::USER_INDEX], code::USER_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    function getCustomer() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                $res = SiteInfomationDAL::getOne($id);
                self::$data['data'] = $res;
            } else {
                self::$data['data'] = null;
            }
            self::$data['class'] = $this->class;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::USER_INDEX], code::USER_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    function updateCustomer() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if($id!=null){
                $_data = [
                    'status' => $_GET['status'],
                ];
                self::$data = SiteInfomationDAL::update($id,$_data);
                if($_GET['status']==1){
                    $row=SiteInfomationDAL::getOne($id);
                    $_mq=[
                        'type'=>'rpc',
                        'name'=>$row['sub_type'],
                        'value'=>json_encode(["action"=>"RpcClient","method"=>"","name"=>$row['name'],"city"=>$row['city'],"value"=>$row['value']],JSON_UNESCAPED_UNICODE),
                        'add_time'=>date("Y-m-d H:i:s"),
                        'status'=>0,
                    ];
                    MessageQueueDAL::insert($_mq);
                }
            }
            if (self::$data) {
                //Common::pr(Common::getSession($this->class));die;
                Common::js_redir(Common::getSession($this->class));
            } else {
                Common::js_alert('修改失败，请联系系统管理员');
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_UPDATE], code::CATEGORY_UPDATE, json_encode($ex));
        }
    }

    function deleteCustomer() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                self::$data = SiteInfomationDAL::delete($id);
            }
            Common::js_redir(Common::getSession($this->class));
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_DELETE], code::CATEGORY_DELETE, json_encode($ex));
        }
    }
}
