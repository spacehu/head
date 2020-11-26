<?php

namespace TigerDAL\Api;

use TigerDAL\BaseDAL;
use TigerDAL\Cms\SiteInfomationDAL as cmsSiteInfomationDAL;

class SiteInfomationDAL {


    /** 新增用户信息 */
    public function add($enterprise_id,$name,$city,$value,$type=null,$sub_type=null) {
        $data=[
            'type'=>'1',
            'sub_type'=>'blessing',
            'name'=>$name,
            'value'=>$value,
            'add_time'=>date("Y-m-d H:i:s"),
            'enterprise_id'=>$enterprise_id,
            'user_id'=>'',
            'customer_id'=>'',
            'city'=>$city,
            'status'=>'0',
        ];
        if(!empty($type)){
            $data['type']=$type;
        }
        if(!empty($sub_type)){
            $data['sub_type']=$sub_type;
        }
        return cmsSiteInfomationDAL::insert($data);
    }

}
