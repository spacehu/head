<?php

namespace action;

use mod\common as Common;
use mod\upload as Upload;
use TigerDAL;
use TigerDAL\Cms\ImageDAL;
use TigerDAL\Cms\MediaDAL;
use TigerDAL\Cms\EnterpriseDAL;
use config\code;

class material {

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
        Common::isset_cookie();
        Common::writeSession($_SERVER['REQUEST_URI'], $this->class);
        try {
            $type = $_GET['type'];
            $currentPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 1;
            $pagesize = isset($_GET['pagesize']) ? $_GET['pagesize'] : \mod\init::$config['page_width'];
            $keywords = isset($_GET['keywords']) ? $_GET['keywords'] : "";
            $st = $_GET['st'];

            self::$data['type'] = $type;
            self::$data['currentPage'] = $currentPage;
            self::$data['pagesize'] = $pagesize;
            self::$data['keywords'] = $keywords;
            self::$data['class'] = $this->class;
            self::$data['st'] = $st;

            if ($type == 'image') {
                self::$data['total'] = ImageDAL::getTotal($keywords, $st);
                self::$data['data'] = ImageDAL::getAll($currentPage, 9, $keywords, $st);
//                self::$data['imgsrc'] = ImageDAL::getOne(self::$data['data']['id']);
            } else if ($type == 'music' || $type == 'video') {
                self::$data['data'] = MediaDAL::getAll($currentPage, $pagesize, $keywords, $type);
                self::$data['total'] = MediaDAL::getTotal($keywords, $type);
            } else {
                TigerDAL\CatchDAL::markError(code::$code[code::MATERIAL_INDEX], code::MATERIAL_INDEX, json_encode($_GET));
            }
            \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__ . '_' . $type);
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::MATERIAL_INDEX], code::MATERIAL_INDEX, json_encode($ex));
        }
    }

    function getImage() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $st = $_GET['st'];
        try {
            if ($id != null) {
                self::$data['data'] = ImageDAL::getOne($id);
            } else {
                self::$data['data'] = null;
            }
            //Common::pr(self::$data['data']);die;
            self::$data['st'] = $st;
            self::$data['config'] = \mod\init::$config['env'];
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::MATERIAL_INDEX], code::MATERIAL_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    function updateImage() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                /** 更新操作 */
                $data = [
                    'name' => $_POST['name'],
                    'edit_by' => Common::getSession("id"),
                ];
                $data['original_src'] = $_POST['edit_doc'];
                $data['unique'] = md5($_POST['edit_doc']);
                //Common::pr($data);die;
                self::$data = ImageDAL::update($id, $data);
            } else {
                /** 新增操作 */
                $filePath = $_POST['edit_doc'];
                $unique = md5($_POST['edit_doc']);
                //Common::pr(UserDAL::getUser($_POST['name']));die;
                $data = [
                    'name' => $_POST['name'],
                    'original_src' => $filePath,
                    'original_link' => "",
                    'order_by' => 50,
                    'add_by' => Common::getSession("id"),
                    'add_time' => date("Y-m-d H:i:s"),
                    'edit_by' => Common::getSession("id"),
                    'edit_time' => date("Y-m-d H:i:s"),
                    'delete' => 0,
                    'unique' => $unique,
                    'enterprise_id' => $this->enterprise_id,
                    'st' => $_POST['st'],
                ];
                self::$data = ImageDAL::insert($data);
            }
            if (self::$data) {
                //Common::pr(Common::getSession($this->class));die;
                Common::js_redir(Common::getSession($this->class));
            } else {
                Common::js_alert('修改失败，请联系系统管理员');
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::MATERIAL_UPDATE], code::MATERIAL_UPDATE, json_encode($ex));
        }
    }

    function deleteImage() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                self::$data = ImageDAL::delete($id);
            }
            Common::js_redir(Common::getSession($this->class));
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::MATERIAL_DELETE], code::MATERIAL_DELETE, json_encode($ex));
        }
    }

    function getMedia() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $type = isset($_GET['type']) ? $_GET['type'] : null;
        self::$data['type'] = $type;
        try {
            if ($id != null) {
                self::$data['data'] = MediaDAL::getOne($id);
                self::$data['type'] = self::$data['data']['type'];
            } else {
                self::$data['data'] = null;
            }
            //Common::pr(self::$data['data']);die;
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::MATERIAL_INDEX], code::MATERIAL_INDEX, json_encode($ex));
        }
        \mod\init::getTemplate('admin', $this->class . '_' . __FUNCTION__);
    }

    function updateMedia() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $type = $_POST['type'];
        $path = "/data/media_doc";
        $upload = new Upload();
        try {
            if ($id != null) {
                /** 更新操作 */
                $data = [
                    'name' => $_POST['name'],
                    'overview' => isset($_POST['overview']) ? $_POST['overview'] : "",
                    'minstrel' => isset($_POST['minstrel']) ? $_POST['minstrel'] : "",
                    'duration' => isset($_POST['duration']) ? $_POST['duration'] : "",
                    'edit_by' => Common::getSession("id"),
                ];
                if ($_POST['edit_doc'] == 1) {
                    $_backData = $upload->uploaded_file("media", "unique", "file_url", $path, $id);
                    if (!$_backData['success']) {
                        Common::js_alert(code::ALREADY_EXISTING_DATA . " 重复素材为：" . $_backData['data']['name'] . "。");
                        TigerDAL\CatchDAL::markError(code::$code[code::ALREADY_EXISTING_DATA], code::ALREADY_EXISTING_DATA, json_encode($_POST));
                        Common::js_redir(Common::getSession($this->class));
                    } else {
                        $data['src'] = $_backData['path'];
                        $data['unique'] = $_backData['md5'];
                    }
                }
                self::$data = MediaDAL::update($id, $data);
            } else {
                if ($_POST['edit_doc'] == 1) {
                    $_backData = $upload->uploaded_file("media", "unique", "file_url", $path, "");
                    //Common::pr($filePath);die;
                    if (!$_backData['success']) {
                        Common::js_alert(code::ALREADY_EXISTING_DATA . " 重复素材为：" . $_backData['data']['name'] . "。");
                        TigerDAL\CatchDAL::markError(code::$code[code::ALREADY_EXISTING_DATA], code::ALREADY_EXISTING_DATA, json_encode($_POST));
                        Common::js_redir(Common::getSession($this->class));
                    } else {
                        $filePath = $_backData['path'];
                        $unique = $_backData['md5'];
                        /** 新增操作 */
                        $data = [
                            'name' => $_POST['name'],
                            'overview' => isset($_POST['overview']) ? $_POST['overview'] : "",
                            'src' => $filePath,
                            'type' => $type,
                            'order_by' => 50,
                            'add_by' => Common::getSession("id"),
                            'add_time' => date("Y-m-d H:i:s"),
                            'edit_by' => Common::getSession("id"),
                            'edit_time' => date("Y-m-d H:i:s"),
                            'delete' => 0,
                            'minstrel' => isset($_POST['minstrel']) ? $_POST['minstrel'] : "",
                            'duration' => isset($_POST['duration']) ? $_POST['duration'] : "",
                            'unique' => $unique,
                        ];
                        self::$data = MediaDAL::insert($data);
                    }
                } else {
                    Common::js_alert(code::NULL_DATA . " 文件不能为空。");
                    TigerDAL\CatchDAL::markError(code::$code[code::NULL_DATA], code::NULL_DATA, json_encode($_POST));
                    Common::js_redir(Common::getSession($this->class));
                }
            }
            if (self::$data) {
                //Common::pr(Common::getSession($this->class));die;
                Common::js_redir(Common::getSession($this->class));
            } else {
                Common::js_alert('修改失败，请联系系统管理员');
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::MATERIAL_UPDATE], code::MATERIAL_UPDATE, json_encode($ex));
        }
    }

    function deleteMedia() {
        Common::isset_cookie();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        try {
            if ($id != null) {
                self::$data = MediaDAL::delete($id);
            }
            Common::js_redir(Common::getSession($this->class));
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::MATERIAL_DELETE], code::MATERIAL_DELETE, json_encode($ex));
        }
    }

    /** 异步 */
    function saveImage() {
        //Common::isset_cookie();
        try {
            $filePath = $_GET['path'];
            $filePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $filePath);
            $unique = $_GET['md5'];
            //Common::pr(UserDAL::getUser($_POST['name']));die;
            $data = [
                'name' => $_GET['name'],
                'original_src' => $filePath,
                'original_link' => "",
                'order_by' => 50,
                'add_by' => 0,
                'add_time' => date("Y-m-d H:i:s"),
                'edit_by' => 0,
                'edit_time' => date("Y-m-d H:i:s"),
                'delete' => 0,
                'unique' => $unique,
            ];
            self::$data = ImageDAL::insert($data);
            if (self::$data) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => '修改失败，请联系系统管理员']);
            }
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::MATERIAL_UPDATE], code::MATERIAL_UPDATE, json_encode($ex));
            echo json_encode(['success' => false, 'message' => '999']);
        }
    }

    function getIndexList() {
        //Common::isset_cookie();
        try {
            $currentPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 1;
            $pagesize = isset($_GET['pagesize']) ? $_GET['pagesize'] : \mod\init::$config['page_width'];
            $keywords = isset($_GET['keywords']) ? $_GET['keywords'] : "";

            self::$data['currentPage'] = $currentPage;
            self::$data['pagesize'] = $pagesize;
            self::$data['keywords'] = $keywords;
            self::$data['class'] = $this->class;

            self::$data['data'] = ImageDAL::getAll($currentPage, $pagesize, $keywords);
            self::$data['total'] = ImageDAL::getTotal($keywords);
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::MATERIAL_INDEX], code::MATERIAL_INDEX, json_encode($ex));
        }
        exit(json_encode(self::$data));
    }

    /** 内部调用 */
    function _saveImage($filePath) {
        try {
            $filePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $filePath);
            $unique = '';
            //Common::pr(UserDAL::getUser($_POST['name']));die;
            $data = [
                'name' => $filePath,
                'original_src' => $filePath,
                'original_link' => "",
                'order_by' => 50,
                'add_by' => 0,
                'add_time' => date("Y-m-d H:i:s"),
                'edit_by' => 0,
                'edit_time' => date("Y-m-d H:i:s"),
                'delete' => 0,
                'unique' => $unique,
            ];
            return ImageDAL::insert_return_id($data);
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::MATERIAL_UPDATE], code::MATERIAL_UPDATE, json_encode($ex));
            return false;
        }
    }

    function _saveMedia($filePath, $type) {
        try {
            $filePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $filePath);
            $unique = '';
            /** 新增操作 */
            $data = [
                'name' => $filePath,
                'overview' => $filePath,
                'src' => $filePath,
                'type' => $type,
                'order_by' => 50,
                'add_by' => Common::getSession("id"),
                'add_time' => date("Y-m-d H:i:s"),
                'edit_by' => Common::getSession("id"),
                'edit_time' => date("Y-m-d H:i:s"),
                'delete' => 0,
                'minstrel' => isset($_POST['minstrel']) ? $_POST['minstrel'] : "",
                'duration' => isset($_POST['duration']) ? $_POST['duration'] : "",
                'unique' => $unique,
            ];
            return MediaDAL::insert_return_id($data);
        } catch (Exception $ex) {
            TigerDAL\CatchDAL::markError(code::$code[code::MATERIAL_UPDATE], code::MATERIAL_UPDATE, json_encode($ex));
            return false;
        }
    }

}
