<?php

namespace action;

use mod\common as Common;
use mod\upload as Upload;
use TigerDAL;
use TigerDAL\Cms\UserInfoDAL;
use TigerDAL\Cms\EnterpriseDAL;
use TigerDAL\Cms\CourseDAL;
use config\code;

class customer {

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
            self::$data['data'] = UserInfoDAL::getAll($currentPage, $pagesize, $keywords, $this->enterprise_id);
            self::$data['total'] = UserInfoDAL::getTotal($keywords, $this->enterprise_id);

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
                $res = UserInfoDAL::getOne($id);
                self::$data['data'] = $res;
                if (!empty($this->enterprise_id)) {
                    $resCourse = UserInfoDAL::getUserEnterpriseCourseList($id, $this->enterprise_id);
                    $course = CourseDAL::getAll(1, 999, '', '', $this->enterprise_id);
                    self::$data['userCourse'] = $resCourse;
                    self::$data['course'] = $course;
                } else {
                    self::$data['userCourse'] = null;
                    self::$data['course'] = null;
                }
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
                    'name' => $_POST['name'],
                    'phone' => $_POST['phone'],
                    'email' => $_POST['email'],
                    'sex' => $_POST['sex'],
                    'edit_time' => date("Y-m-d H:i:s"),
                ];
                self::$data = UserInfoDAL::update($id,$_data);
            }else{
                $_data=$_POST;
                $_data['enterprise_id']=$this->enterprise_id;
                self::$data = $this->addOne($_data);
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
                self::$data = UserInfoDAL::delete($id);
            }
            Common::js_redir(Common::getSession($this->class));
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::CATEGORY_DELETE], code::CATEGORY_DELETE, json_encode($ex));
        }
    }

    function uploadcsv(){
        
        $upload = new Upload();
        try {
            $_data = $upload->getTmpName('files');
            $file_name=$_data['tmp_name'];
            $ext=$_data['ext'];
            if($file_name == '')
            {
                die("请选择要上传的csv文件");
            }
            if($ext!=='.csv'){
                die("请上传csv文件");
            }
            // 以只读的方式打开文件
            $handle = fopen($file_name, 'r');
            if($handle === FALSE) die("打开文件资源失败");

            // setlocale() 函数设置地区信息（地域信息）。
            @setlocale(LC_ALL, 'zh_CN');

            // CSV对应的字段名
            $csv_val = array('name','phone','email','sex');
            $csv_arr = array();

            while(($data = fgetcsv($handle)) !== FALSE)
            {
                // TODO::这里需要检查和给的字段数是否一致，如果不一致，则不能写入
                $tmp_row = array();
                foreach ($csv_val as $k => $v)
                {
                    $tmp_row[$v] = trim(iconv('gbk','utf-8', ltrim($data[$k], '`')));
                }

                $csv_arr[] = $tmp_row;
            }

            // 关闭资源
            fclose($handle);
            //Common::pr($csv_arr);die;
            if(!empty($csv_arr)){
                foreach($csv_arr as $k=>$v){
                    $_row=$v;
                    $_row['enterprise_id']=$this->enterprise_id;
                    $this->addOne($_row);
                }
                exit("导入完成");
            }
            exit("没有数据进入");
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::MATERIAL_UPDATE], code::MATERIAL_UPDATE, json_encode($ex));
        }
    }

    function addOne($_data){
        $data = [
            'name' => $_data['name'],
            'phone' => $_data['phone'],
            'nickname' => !empty($_data['nickname'])?$_data['nickname']:'',
            'photo' => !empty($_data['photo'])?$_data['photo']:'',
            'brithday' => !empty($_data['brithday'])?$_data['brithday']:'',
            'province' => !empty($_data['province'])?$_data['province']:'',
            'city' => !empty($_data['city'])?$_data['city']:'',
            'district' => !empty($_data['district'])?$_data['district']:'',
            'email' => !empty($_data['email'])?$_data['email']:'',
            'sex' => !empty($_data['sex'])?$_data['sex']:'',
            'add_time' => date("Y-m-d H:i:s"),
            'edit_time' => date("Y-m-d H:i:s"),
            'enterprise_id' => !empty($_data['enterprise_id'])?$_data['enterprise_id']:'',
            'password' => !empty($_data['password'])?$_data['password']:'',
            'last_login_time' => !empty($_data['last_login_time'])?$_data['last_login_time']:'',
            'delete' => 0,
        ];
        return UserInfoDAL::insert($data);
    }
}
