<?php

namespace action\cli;

use mod\common as Common;
use TigerDAL\cli\LogDAL;
use TigerDAL\cli\MessageQueueDAL;
use TigerDAL\cli\EnterpriseDAL;
use config\code;

class mq {
    private $cli_status=0;
    private $cli_config;
    /**
     * 主方法引入父类的基类
     * 责任是分担路由的工作
     */
    function __construct($argv) {
        $this->cli_config=EnterpriseDAL::getByCode($argv[1]);
        //print_r($argv);die;
        LogDAL::save(date("Y-m-d H:i:s") . "-start", "cli");
        LogDAL::save(date("Y-m-d H:i:s") . "-config-".json_encode($this->cli_config), "cli");
    }

    function __destruct() {
        LogDAL::save(date("Y-m-d H:i:s") . "-end", "cli");
        LogDAL::_saveLog();
    }

    /** 向企业管理员发送邮件的方法 */
    function checkMQ() {
        try {
            $_conf=json_decode($this->cli_config['cli_config']);
            //var_dump($_conf);die;
            $data_sleep=(int)$_conf->data_sleep;
            $cli_sleep=(int)$_conf->cli_sleep;
            while(true){
                $_mq = MessageQueueDAL::getAll();
                //LogDAL::save(date("Y-m-d H:i:s") . "-data", json_encode($_mq));
                if(!empty($_mq)){
                    foreach($_mq as $k=>$v){
                        $obj=(array)json_decode($v['value']);
                        if($v['name']=='blessing'){
                            $row= array(
                                'name'=>$obj['name'],
                                'city'=>$obj['city'],
                                'value'=>$v['name'].'[|]'.$obj['name'].'[|]'.$obj['city'].'[|]'.$obj['value'],
                            );
                        }else if($v['name']=='sign'){
                            $row= array(
                                'name'=>$obj['name'],
                                'city'=>'',
                                'value'=>$v['name'].'[|]'.$obj['name'],
                            );
                        }
                        
                        $rpc=new RpcClient();
                        $input=$rpc->client($row);
                        LogDAL::save(date("Y-m-d H:i:s") . "-$input", "cli");
                        echo $input;
                        if($input==$row['value']){
                            $_data['status']=1;
                            MessageQueueDAL::update($v['id'],$_data);
                        }
                        sleep($data_sleep);
                        //var_dump($data_sleep);
                    }
                }
                sleep($cli_sleep);
                //var_dump($cli_sleep);
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
    }
}
