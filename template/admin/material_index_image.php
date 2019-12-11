<?php
$data = \action\material::$data['data'];
$Total = \action\material::$data['total'];
$type = \action\material::$data['type'];
$st = \action\material::$data['st'];
$currentPage = \action\material::$data['currentPage'];
$pagesize = \action\material::$data['pagesize'];
$keywords = \action\material::$data['keywords'];
$class = \action\material::$data['class'];
//$imgsrc=\action\material::$data['imgsrc'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script type="text/javascript" src="js/jquery.js" ></script>
        <title>无标题文档</title>
        <script id="hoverscript">
            $(function () {
                $('.button_find').click(function () {
                    window.location.href = 'index.php?a=<?php echo $class; ?>&m=index&keywords=' + $('.keywords').val() + '&type=<?php echo $type; ?>&st=<?php echo $st; ?>';
                });

            });
        </script>
        <style>

            .hovershow{
                display: block;
                position: absolute;
                z-index: 100;
                width: 100%;
                text-align: center;
                bottom: 0;
                height: 100%;
                line-height: 30px;
                background-color: black;
                opacity: 0;

            }
            .hovershow:hover{
                display: block;
                position: absolute;
                z-index: 100;
                width: 100%;
                text-align: center;
                bottom: 0;
                height: 100%;
                line-height: 30px;
                background-color: black;
                opacity: 0.8;

            }

            .border{
                position: relative;
            }
            .border:hover{
                box-shadow: 10px 10px 5px #888888;
            }

            .hovershow span{
                text-align: center;
                display: block;
                top: 50%;
                color: #EDEDED;
                position: relative;
                height: 30px;
                line-height: 30px;
                /*font-weight: 700;*/
                font-family: "Microsoft YaHei", YaHei, "微软雅黑", SimHei, "黑体";
                font-size: 0.3rem;
            }
            .hovershow a:hover{
                color: red;
            }
            .hovershow a{
                text-align: center;
                display: block;
                color: white;
                position: relative;
                width: 40%;
                margin:0 5%;
                height: 0.1rem;
                line-height: 0.1rem;
                float: left;
                padding: 10px 0;
                font-weight: 700;
                font-family: "Microsoft YaHei", YaHei, "微软雅黑", SimHei, "黑体";
                text-decoration: none;
                font-size: 0.9rem;
            }
            #Imgs-List-Box{overflow: hidden;}
            .img-list{width: 32%;height: 110px;margin:0 1% 15px 0;overflow: hidden;border: 1px solid #d1d1d1;border-radius: 5px;float: left;}
            .img{width: 50%;height: 100%;float: left;display: flex;justify-content: center;align-items: center;align-content: center;background: #eee;background-size: contain;background-position: 50% 50%;background-repeat: no-repeat;}
            .img-edit-box{float: right;width: 50%;}
            .img-edit-main{padding: 10px;}
            .img-name{font-size: 16px;line-height: 1.4;margin-bottom: 10px;height: 42px;overflow: hidden;}
            .img-edit-btn a{display: inline-block;text-decoration: none;border: 1px solid #ccc;background: #D8D8D8;padding: 1px 4px;border-radius: 2px;color: #333;font-size: 12px;margin-right: 6px}
            .img-edit-btn a.img-edit{background: #2998E0;border-color: #4D9DE3;color: #fff}
            #GetMore{text-align: center;font-size: 16px;line-height: 2;padding: 15px;color: #999;cursor:pointer}
        </style>
    </head>

    <body>
        <div class="menu" >
            <input type="text" name="keywords" class="keywords" value="<?php echo isset($keywords) ? $keywords : ""; ?>" />
            <a class="button_find " href="javascript:void(0);">查找</a>
            <a href="javascript:void(0);" class="updateButton"  onclick="javascript:parent.mainFrame.location.href = 'index.php?a=<?php echo $class; ?>&m=getImage&st=<?php echo $st; ?>'">添加新素材</a>
        </div>
        <div class="content" style="padding-top: 10px;">
            <div id="Imgs-List-Box">
                <?php
                if (!empty($data)) {
                    foreach ($data as $k => $v) {
                        ?>
                        <div id="<?php echo $v['id'] ?>" class="img-list">
                            <div class="img" style="background-image:url(<?php
                            echo $v['original_src'];
                            ?>)">
                                <!-- <img style="width: 100%;" class="" src=".<?php
                                //echo $v['original_src'];
                                ?>"/> -->
                            </div>
                            <div class="img-edit-box">
                                <div class="img-edit-main">
                                    <div class="img-name"><?php echo $v['name'] ?></div>
                                    <div class="img-edit-btn">
                                        <a href="index.php?a=<?php echo $class; ?>&m=getImage&id=<?php echo $v['id']; ?>&st=<?php echo $st; ?>" class="img-edit">编辑</a>
                                        <a href="index.php?a=<?php echo $class; ?>&m=deleteImage&id=<?php echo $v['id']; ?>" class="img-dlt" onclick="return confirm('确定将此素材删除?')">删除</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                }
                ?>
            </div>
            <!-- <div id="GetMore" >查看更多>>></div> -->
            <div class="clear">
            </div>
            <script>
                var currentpage = 1;
                var st =<?php echo $st; ?>;
                $(document).ready(function () {
                    $(document).on('click', '#GetMore', function (event) {
                        getMore();
                    });
                    if (window.innerHeight >= document.body.offsetHeight) {
                        getMore();
                    }
                });
                $(document).scroll(function () {
                    var scrollT = $(this).scrollTop(),
                            winH = window.innerHeight,
                            documentH = $(this).height();
                    if (scrollT + winH == documentH || scrollT == documentH) {
                        getMore();
                    }
                });
                function getMore() {
                    currentpage++;
                    $.ajax({
                        type: "get",
                        url: './index.php?a=<?php echo $class; ?>&m=getIndexList&keywords=' + $('.keywords').val() + '&type=<?php echo $type; ?>&keywords=' + $('.keywords').val() + '&st=<?php echo $st; ?>&currentPage=' + currentpage + '&pagesize=<?php echo mod\init::$config['page_width']; ?>',
                        contentType: "application/json",
                        dataType: 'json',
                        success: function (json) {
                            var data = json.data;
                            if (data) {
                                var num = data.length
                                for (var i = 0; i < num; i++) {
                                    addhtml(data[i].id, data[i].original_src, data[i].name, json.class, st);
                                }
                            }
                        }
                    })
                }
                function addhtml(id, src, name, cls, st) {
                    // html='<div id="'+id+'" class="border" style="margin: 0 10px 30px">' +
                    //     ' <img style="width: 100%;" src=".'+src+'"  />' +
                    //     '<div class="hovershow">' +
                    //     '<span class="name">'+name+'</span>' +
                    //     '<a href="index.php?a='+cls+'&m=getImage&id='+id+'">编辑</a>' +
                    //     '<a href="index.php?a='+cls+'&m=deleteImage&id='+id+'" onclick="return confirm(\'确定将此素材删除?\')">删除</a></div>' +
                    //     '</div>'
                    html = '<div id="' + id + '" class="img-list">' +
                            '<div class="img" style="background-image:url(.' + src + ')"></div>' +
                            '<div class="img-edit-box">' +
                            '<div class="img-edit-main">' +
                            '<div class="img-name">' + name + '</div>' +
                            '<div class="img-edit-btn">' +
                            '<a href="index.php?a=' + cls + '&m=getImage&id=' + id + '&st=' + st + '" class="img-edit">编辑</a>' +
                            '<a href="index.php?a=' + cls + '&m=deleteImage&id=' + id + '" class="img-dlt" onclick="return confirm(\'确定将此素材删除?\')">删除</a>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>';
                    $("#Imgs-List-Box").append(html);
                }
                function addscript(id) {
                    script1 = document.getElementById("hoverscript")

                    scripttext = '$(function(){$("#' + id + '").hover(function () {' +
                            '$("#' + id + '.hovershow").toggle();' +
                            ' });})';
                    var t = document.createTextNode(scripttext);
                    script1.appendChild(t);
                }

            </script>
            <!--            <div class="num_bar">-->
            <!--                总数<b>--><?php //echo $Total;   ?><!--</b>-->
            <!--            </div>-->
            <!--            --><?php
//            $url = 'index.php?a=' . $class . '&m=index&keywords=' . $keywords . '&type=' . $type;
//            $Totalpage = ceil($Total / mod\init::$config['page_width']);
//            include_once 'page.php';
//            
            ?>
        </div>
    </body>
</html>

