<?php
$data = \action\slideShow::$data['data'];
$Total = \action\slideShow::$data['total'];
$currentPage = \action\slideShow::$data['currentPage'];
$pagesize = \action\slideShow::$data['pagesize'];
$class = \action\slideShow::$data['class'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script type="text/javascript" src="js/jquery.js" ></script>
        <title>无标题文档</title>
    </head>

    <body>
        <div class="menu">
            <a href="javascript:void(0);" class="updateButton"  onclick="javascript:parent.mainFrame.location.href = 'index.php?a=<?php echo $class; ?>&m=getSlideShow'">添加新轮播</a>
        </div>
        <div class="content">
            <table class="mytable" cellspacing="0" >
                <tr bgcolor="#656565" style=" font-weight:bold; color:#FFFFFF;">
                    <td class="td1" >排序</td>
                    <td class="td1" width="40%">属于</td>
                    <td class="td1" width="20%">操作</td>
                </tr>
                <?php
                $sum_i = 1;
                if (!empty($data)) {
                    foreach ($data as $v) {
                        ?>
                        <tr<?php if ($sum_i % 2 != 1) { ?>  class="tr2"<?php } ?>>
                            <td class="td1"><?php echo $v['order_by']; ?></td>
                            <td class="td1"><?php echo $v['name']; ?></td>
                            <td class="td1">
                                <?php if($v['status']==0){?>
                                    <a href="index.php?a=<?php echo $class; ?>&m=updateSlideShowStatus&id=<?php echo $v['id']; ?>&status=1">开启</a>
                                <?php }else{?>
                                    <a href="index.php?a=<?php echo $class; ?>&m=updateSlideShowStatus&id=<?php echo $v['id']; ?>&status=0">关闭</a>
                                <?php }?>
                                <a href="index.php?a=<?php echo $class; ?>&m=getSlideShow&id=<?php echo $v['id']; ?>">编辑</a>
                                <a href="index.php?a=<?php echo $class; ?>&m=deleteSlideShow&id=<?php echo $v['id']; ?>" onclick="return confirm('确定将此轮播删除?')">删除</a>
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
            $url = 'index.php?a=' . $class . '&m=index';
            $Totalpage = ceil($Total / mod\init::$config['page_width']);
            include_once 'page.php';
            ?>
        </div>
    </body>
</html>
