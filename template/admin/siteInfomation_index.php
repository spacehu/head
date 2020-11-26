<?php
$data = \action\siteInfomation::$data['data'];
$Total = \action\siteInfomation::$data['total'];
$currentPage = \action\siteInfomation::$data['currentPage'];
$pagesize = \action\siteInfomation::$data['pagesize'];
$keywords = \action\siteInfomation::$data['keywords'];
$class = \action\siteInfomation::$data['class'];
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
                $('.button_allow_add').click(function () {
                    if (ids.length === 0) {
                        alert("请选择讯息执行该操作");
                        return;
                    }
                    console.log(ids);
                    //alert("请选择学员执行该操作");
                    window.location.href = 'index.php?a=<?php echo $class; ?>&m=setEu&ids=' + ids + '&status=1';
                });
                $('.button_refuse_add').click(function () {
                    if (ids.length === 0) {
                        alert("请选择讯息执行该操作");
                        return;
                    }
                    window.location.href = 'index.php?a=<?php echo $class; ?>&m=setEu&ids=' + ids + '&status=2';
                });
                $('.ids').on('click', function () {
                    if ($(this).attr("checked") == "checked") {
                        ids[ids.length] = $(this).attr("data-value");
                    } else {
                        ids.splice($.inArray($(this).attr("data-value"), ids), 1);
                        $("#allids").attr("checked", false);
                    }
                    //console.log(ids);
                });
                $('#allids').click(function () {
                    var check = 0;
                    $(".ids").each(function () {
                        if ($(this).attr("checked") == "checked") {
                            check++;
                        }
                    });
                    if (check == 0) {
                        $(".ids").each(function () {
                            $(this).attr("checked", "checked");
                            ids[ids.length] = $(this).attr("data-value");
                        });
                    } else {
                        $(".ids").attr("checked", false);
                        $(this).attr("checked", false);
                        ids = [];
                    }
                    //console.log(ids);
                });
            });
        </script>
    </head>

    <body>
        <div class="menu">
            <input type="text" name="keywords" class="keywords" value="<?php echo isset($keywords) ? $keywords : ""; ?>" placeholder="请输入关键字" />
            <a class="button_find " href="javascript:void(0);" >查找</a>
            <a class="button_allow_add" href="javascript:void(0);">审核通过</a>
            <a class="button_refuse_add" href="javascript:void(0);">审核不通过</a>
        </div>
        <div class="content">
            <table class="mytable" cellspacing="0" >
                <tr bgcolor="#656565" style=" font-weight:bold; color:#FFFFFF;">
                    <td class="td1" width="5%"><input type="checkbox" class="checkbox" id="allids" /></td>
                    <td class="td1" >姓名</td>
                    <td class="td1" >城市</td>
                    <td class="td1" >内容</td>
                    <td class="td1" >状态</td>
                    <td class="td1" width="20%">录入时间</td>
                    <td class="td1" width="9%">操作</td>
                </tr>
                <?php
                $sum_i = 1;
                if (!empty($data)) {
                    foreach ($data as $v) {
                        ?>
                        <tr<?php if ($sum_i % 2 != 1) { ?>  class="tr2"<?php } ?>>
                            <td class="td1"><input type="checkbox" class="checkbox ids"  data-value="<?php echo $v['id']; ?>" /></td>
                            <td class="td1"><?php echo $v['name']; ?></td>
                            <td class="td1"><?php echo $v['city']; ?></td>
                            <td class="td1">
                                <?php if($v['sub_type']=='blessing'){?>
                                    <?php echo $v['value'];?>
                                <?php }else if($v['sub_type']=='link'){?>
                                    <audio src="<?php echo $v['value']; ?>" controls preload ></audio>
                                <?php }?>
                            </td>
                            <td class="td1">
                                <?php if($v['sub_type']=='blessing'){?>
                                    <?php echo ($v['status']==0)?"未审核":"通过审核"; ?>    
                                <?php }else if($v['sub_type']=='link'){?>
                                    <a target="_blank" href="<?php echo $v['value']; ?>">下载</a>
                                <?php }?>
                            </td>
                            <td class="td1"><?php echo $v['add_time']; ?></td>
                            <td class="td1">
                                <?php if($v['sub_type']=='blessing'){?>
                                    <a href="index.php?a=<?php echo $class; ?>&m=updateSiteInfomation&id=<?php echo $v['id']; ?>&status=1">通过</a>
                                <?php }?>
                                <a href="index.php?a=<?php echo $class; ?>&m=deleteSiteInfomation&id=<?php echo $v['id']; ?>" onclick="return confirm('确定将此客户删除?')">删除</a>
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
