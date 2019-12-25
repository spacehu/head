<?php
$data = \action\account::$data['data'];
$enterprise=\action\account::$data['enterprise'];
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script type="text/javascript" src="js/jquery.js"></script>
        <title>无标题文档</title>
    </head>

    <body>
        <div class="status r_top">
        </div>
        <div class="content">
            <form name="theForm" id="demo" action="./index.php?a=account&m=updateAccount" method="post" enctype='multipart/form-data'>
                <div class="pathA ">
                    <div class="leftA">
                        <div class="leftAlist" >
                            <span>PASSWORD 密码</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="r_row">
                                <input class="text" name="password" type="password" value="" />
                            </div>
                            <div class="r_row">
                                <input class="text" name="password_cfn" type="password" value="" />
                            </div>
                        </div>
                        <?php if(!empty($enterprise)){?>
                            <div class="leftAlist" >
                                <span>QRCODE 头像系统二维码开关</span>
                            </div>
                            <div class="leftAlist" >
                                <div class="r_row">
                                    <input type="radio" name="qrcode_status" value="0" <?php echo (!isset($enterprise['qrcode_status'])||$enterprise['qrcode_status']==0)?"checked":"";?> />：关
                                    <input type="radio" name="qrcode_status" value="1" <?php echo (isset($enterprise['qrcode_status'])&&$enterprise['qrcode_status']==1)?"checked":"";?> />：开
                                </div>
                            </div>
                        <?php }?>
                    </div>
                </div>
                <div class="pathB">
                    <div class="leftA">
                        <input name="" type="submit" id="submit" value="SUBMIT 提交" />
                    </div>
                </div>
            </form>	
        </div>
    </body>
</html>