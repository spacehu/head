<?php
$data = \action\show::$data['data'];
$Total = \action\show::$data['total'];
$currentPage = \action\show::$data['currentPage'];
$pagesize = \action\show::$data['pagesize'];
$keywords = \action\show::$data['keywords'];
$class = \action\show::$data['class'];
$list = \action\show::$data['list'];
$category = \action\show::$data['category'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script type="text/javascript" src="js/jquery.js" ></script>
        <title>无标题文档</title>
        <script>
            $(function () {
                $('.button_find').click(function () {
                    window.location.href = 'index.php?a=<?php echo $class; ?>&m=index&keywords=' + $('.keywords').val() + '&category=' + $('.list_select').val();
                });
                $('.list_select').on("change", function () {
                    window.location.href = 'index.php?a=<?php echo $class; ?>&m=index&keywords=' + $('.keywords').val() + '&category=' + $('.list_select').val();
                });
            });
        </script>
    </head>

    <body>
        <div class="menu">
            <input type="text" name="keywords" class="keywords" value="<?php echo isset($keywords) ? $keywords : ""; ?>" />
            <a class="button_find " href="javascript:void(0);">查找</a>
            <select class="listSelect list_select" >
                <option value="">请选择</option>
                <?php if (!empty($list)) { ?>
                    <?php foreach ($list as $k => $v) { ?>
                        <option value="<?php echo $v['id']; ?>" <?php echo $v['id'] == $category ? "selected" : ""; ?>><?php echo $v['name']; ?></option>
                    <?php } ?>              
                <?php } ?>
            </select>
            <a href="javascript:void(0);" class="updateButton"  onclick="javascript:parent.mainFrame.location.href = 'index.php?a=<?php echo $class; ?>&m=getShow&cat_id=<?php echo $category; ?>'">添加新展示</a>
        </div>
        <div class="content">
            <table class="mytable" cellspacing="0" >
                <tr bgcolor="#656565" style=" font-weight:bold; color:#FFFFFF;">
                    <td class="td1" >活动名</td>
                    <td class="td1" width="10%">企业</td>
                    <td class="td1" width="10%">属于</td>
                    <td class="td1" width="10%">投递人数</td>
                    <td class="td1" width="20%">操作</td>
                </tr>
                <?php
                $sum_i = 1;
                if (!empty($data)) {
                    foreach ($data as $v) {
                        ?>
                        <tr<?php if ($sum_i % 2 != 1) { ?>  class="tr2"<?php } ?>>
                            <td class="td1"><?php echo $v['name']; ?></td>
                            <td class="td1"><?php echo $v['eName']; ?></td>
                            <td class="td1"><?php echo $v['add_by']; ?></td>
                            <td class="td1">
                                <?php if (!empty($v['resumeCount'])) { ?>
                                    <a href="index.php?a=<?php echo $class; ?>&m=getResumeList&id=<?php echo $v['id']; ?>"><?php echo $v['resumeCount']; ?></a>
                                <?php } ?>
                            </td>
                            <td class="td1">
                                <a href="index.php?a=<?php echo $class; ?>&m=getShow&id=<?php echo $v['id']; ?>">编辑</a>
                                <a href="index.php?a=<?php echo $class; ?>&m=deleteShow&id=<?php echo $v['id']; ?>" onclick="return confirm('确定将此展示删除?')">删除</a>
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
            $url = 'index.php?a=' . $class . '&m=index&keywords=' . $keywords . '&category=' . $category;
            $Totalpage = ceil($Total / mod\init::$config['page_width']);
            include_once 'page.php';
            ?>
        </div>
    </body>
</html>
