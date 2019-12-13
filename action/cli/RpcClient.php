<?php

namespace action\cli;

use mod\common as Common;
use TigerDAL\cli\LogDAL;
use TigerDAL\cli\MessageQueueDAL;
use config\code;
/**
 * User: yuzhao
 * CreateTime: 2018/11/16 上午12:2
 * Description: Rpc客户端
 */
class RpcClient {
    /**
     * User: yuzhao
     * CreateTime: 2018/11/16 上午12:21
     * @var array
     * Description: 调用的地址
     */
    private $urlInfo = array();
    private $url="192.168.31.189";
    private $port="12345";

    /**
     * RpcClient constructor.
     */
    public function __construct()
    {
        $url="192.168.31.189:12345";
        $this->urlInfo = parse_url($url);
        LogDAL::save(date("Y-m-d H:i:s") . "-start", "cli");
    }

    function __destruct() {
        LogDAL::save(date("Y-m-d H:i:s") . "-end", "cli");
        LogDAL::_saveLog();
    }

    /**
     * User: yuzhao
     * CreateTime: 2018/11/16 上午12:2
     * Description: 返回当前对象
     */
    public static function instance($url) {
        return new RpcClient($url);
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        //创建一个客户端
        $socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
        if ($socket < 0) return false;
        $result = @socket_connect($socket,$this->url,$this->port);
        if ($result == false)return false;
        //var_dump($arguments);die;
        $str=$this->data($name,$arguments[0]);
        //var_dump($str);die;
        $back=socket_write($socket,$str,strlen($str));
        //echo $back;
        if($back!=0){
            $input = socket_read($socket,1024);
            socket_close ($socket);    
            return $input;
        }else{
            socket_close ($socket);    
            return true;    
        }    
    }
    public function data($name,$_data){
        return json_encode(["tag"=>$name,"obj"=>$_data]);
    }
}
//$cli = new RpcClient('http://127.0.0.1:8888/test');
//echo $cli->tuzisir1()."\n";
//echo $cli->tuzisir2(array('name' => 'tuzisir', 'age' => 23));
