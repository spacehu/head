<?php
$data = \action\customer::$data['data'];
$Total = \action\customer::$data['total'];
$currentPage = \action\customer::$data['currentPage'];
$pagesize = \action\customer::$data['pagesize'];
$keywords = \action\customer::$data['keywords'];
$class = \action\customer::$data['class'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script type="text/javascript" src="js/jquery.js" ></script>
        <link rel="stylesheet" type="text/css" href="css/verUpload.css" />
        <script type="text/javascript" src="js/verUpload.js" ></script>
        <title>无标题文档</title>
        <script>
            $(function () {
                var ids = [];
                $('.button_find').click(function () {
                    window.location.href = 'index.php?a=<?php echo $class; ?>&m=index&keywords=' + $('.keywords').val();
                });
                new verUpload({
                    files: "#files",
                    name: "files",
                    load_list: true,
                    method:"./index.php?a=<?php echo $class; ?>&m=uploadcsv",
                    success: function (d) {
                        alert(d);
                        window.location.href = 'index.php?a=<?php echo $class; ?>&m=index&keywords=' + $('.keywords').val();
                    },
                    fail: function (d) {
                        alert(d)
                        window.location.href = 'index.php?a=<?php echo $class; ?>&m=index&keywords=' + $('.keywords').val();
                    },
                });
            });
        </script>
    </head>

    <body>
        <div class="menu">
            <input type="text" name="keywords" class="keywords" value="<?php echo isset($keywords) ? $keywords : ""; ?>" placeholder="请输入关键字" />
            <a class="button_find " href="javascript:void(0);" >查找</a>
            <button id="files">导入</button>
            <a href="javascript:void(0);" class="updateButton" onclick="javascript:parent.mainFrame.location.href = 'index.php?a=<?php echo $class; ?>&m=getCustomer'">添加客户</a>
        </div>
        <div class="content">
            <table class="mytable" cellspacing="0" >
                <tr bgcolor="#656565" style=" font-weight:bold; color:#FFFFFF;">
                    <td class="td1" >用户名</td>
                    <td class="td1" >企业名</td>
                    <td class="td1" >电话</td>
                    <td class="td1" >性别</td>
                    <td class="td1" width="20%">录入时间</td>
                    <td class="td1" width="9%">操作</td>
                </tr>
                <?php
                $sum_i = 1;
                if (!empty($data)) {
                    foreach ($data as $v) {
                        ?>
                        <tr<?php if ($sum_i % 2 != 1) { ?>  class="tr2"<?php } ?>>
                            <td class="td1"><?php echo $v['name']; ?></td>
                            <td class="td1"><?php echo $v['eName']; ?></td>
                            <td class="td1"><?php echo $v['phone']; ?></td>
                            <td class="td1"><?php echo $v['sex']; ?></td>
                            <td class="td1"><?php echo $v['add_time']; ?></td>
                            <td class="td1">
                                <a href="index.php?a=<?php echo $class; ?>&m=getCustomer&id=<?php echo $v['id']; ?>">查看</a>
                                <a href="index.php?a=<?php echo $class; ?>&m=deleteCustomer&id=<?php echo $v['id']; ?>" onclick="return confirm('确定将此客户删除?')">删除</a>
                            </td>
                        </tr>
                        <?php
                        $sum_i++;
                    }
                }
                ?>
            </table>
            <div class="num_bar">
                总数<b><?php echo $Total; ?></b>
            </div>
            <?php
            $url = 'index.php?a=' . $class . '&m=index&keywords=' . $keywords;
            $Totalpage = ceil($Total / mod\init::$config['page_width']);
            include_once 'page.php';
            ?>
        </div>
    </body>
</html>
