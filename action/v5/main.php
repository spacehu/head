<?php

namespace action\v5;

use mod\common as Common;
use TigerDAL\Api\LogDAL;
use TigerDAL\Cms\EnterpriseDAL;
use config\code;

class main extends \action\RestfulApi {

    public $enterprise_id;

    function __construct() {
        $path = parent::__construct();
        //载入配置文件
        $this->enterprise = EnterpriseDAL::getByCode($this->get["code"]);
        //Common::pr($this->enterprise);die;
        if (!empty($path)) {
            $_path = explode("-", $path);
            $actEval = "\$res = \$this ->" . $_path['2'] . "();";
            eval($actEval);
            exit(json_encode($res));
        }
    }

    function socketStart() {
        //start socket
        try {
            //$_key = rand(0, 9999999);
            $command = "php cli.php ".$this->enterprise['code']." &";

            $process = proc_open($command, array(), $pipes);

            $var = proc_get_status($process);

            LogDAL::save(date("Y-m-d H:i:s") . "-".($var['pid'] + 1)."", "cli");
            $_data['cli_status']=$var['pid'] + 1;
            EnterpriseDAL::update($this->enterprise['id'],$_data);
            proc_close($process);
            return ['success' => true, 'data' => $var];
        } catch (Exception $ex) {
            return ['success' => false, 'data' => $ex];
        }
    }

    function socketStop() {
        try {
            $var = $this->enterprise['cli_status'];
            $str = "kill -9 " . $var;
            LogDAL::save(date("Y-m-d H:i:s") . "-".'exec : ' . json_encode($str)."", "cli");
            $process = exec($str);
            $_data['cli_status']=0;
            EnterpriseDAL::update($this->enterprise['id'],$_data);
            return ['success' => true, 'data' => $process];
        } catch (Exception $ex) {
            return ['success' => false, 'data' => $ex];
        }
    }

}
