<?php

namespace action\v6;

use mod\common as Common;
use mod\sts as STS;
use TigerDAL\Api\ImageDAL;
use TigerDAL\Cms\CategoryDAL;
use TigerDAL\Cms\SystemDAL;
use TigerDAL\Api\ArticleDAL;
use config\code;

class ApiBase extends \action\RestfulApi {

    public $user_id;
    public $server_id;

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
    
    /** 获取临时密钥 */
    function getCos(){
        try {
            $sts = new STS();
            $config = array(
                'url' => 'https://sts.tencentcloudapi.com/',
                'domain' => 'sts.tencentcloudapi.com',
                'proxy' => '',
                'secretId' => \mod\init::$config['env']['lib']['tencent']['cos']['secretId'], // 固定密钥
                'secretKey' => \mod\init::$config['env']['lib']['tencent']['cos']['secretKey'], // 固定密钥
                'bucket' => \mod\init::$config['env']['lib']['tencent']['cos']['bucket'], // 换成你的 bucket
                'region' => \mod\init::$config['env']['lib']['tencent']['cos']['region'], // 换成 bucket 所在园区
                'durationSeconds' => 1800, // 密钥有效期
                'allowPrefix' => 'exampleobject', // 这里改成允许的路径前缀，可以根据自己网站的用户登录态判断允许上传的具体路径，例子： a.jpg 或者 a/* 或者 * (使用通配符*存在重大安全风险, 请谨慎评估使用)
                // 密钥的权限列表。简单上传和分片需要以下的权限，其他权限列表请看 https://cloud.tencent.com/document/product/436/31923
                'allowActions' => array (
                    // 简单上传
                    'name/cos:PutObject',
                    'name/cos:PostObject',
                    // 分片上传
                    'name/cos:InitiateMultipartUpload',
                    'name/cos:ListMultipartUploads',
                    'name/cos:ListParts',
                    'name/cos:UploadPart',
                    'name/cos:CompleteMultipartUpload'
                )
            );
            // 获取临时密钥，计算签名
            $tempKeys = $sts->getTempKeys($config);
            self::$data['data']['info'] = $tempKeys;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::HOME_INDEX], code::HOME_INDEX, json_encode($ex));
        }
        return self::$data;
    }
}
