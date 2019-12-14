<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;
use TigerDAL\cli\MessageQueueDAL;

class SignDAL {


    /** 新增用户信息 */
    public function add($enterprise_id,$name) {
        $_mq=[
            'type'=>'rpc',
            'name'=>'sign',
            'value'=>json_encode(["action"=>"RpcClient","method"=>"","name"=>$name],JSON_UNESCAPED_UNICODE),
            'add_time'=>date("Y-m-d H:i:s"),
            'status'=>0,
        ];
        return MessageQueueDAL::insert($_mq);
    }

}
