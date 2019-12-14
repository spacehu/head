<?php

namespace action\cli;

use mod\common as Common;
use TigerDAL\cli\LogDAL;
use TigerDAL\cli\MessageQueueDAL;
use config\code;

class mq {

    /**
     * 主方法引入父类的基类
     * 责任是分担路由的工作
     */
    function __construct() {
        LogDAL::save(date("Y-m-d H:i:s") . "-start", "cli");
    }

    function __destruct() {
        LogDAL::save(date("Y-m-d H:i:s") . "-end", "cli");
        LogDAL::_saveLog();
    }

    /** 向企业管理员发送邮件的方法 */
    function checkMQ() {
        try {
            while(true){
                $_mq = MessageQueueDAL::getAll();
                //LogDAL::save(date("Y-m-d H:i:s") . "-data", json_encode($_mq));
                if(!empty($_mq)){
                    foreach($_mq as $k=>$v){
                        $obj=(array)json_decode($v['value']);
                        $row= array(
                            'name'=>$obj['name'],
                            'city'=>$obj['city'],
                            'value'=>$v['name'].'[|]'.$obj['name'].'[|]'.$obj['city'].'[|]'.$obj['value'],
                        );
                        
                        $rpc=new RpcClient();
                        $input=$rpc->client($row);
                        LogDAL::save(date("Y-m-d H:i:s") . "-$input", "cli");
                        //echo $input;die;
                        if($input==$row['value']){
                            $_data['status']=1;
                            MessageQueueDAL::update($v['id'],$_data);
                        }
                        sleep(5);
                    }
                }
                sleep(5);
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
    }
}
