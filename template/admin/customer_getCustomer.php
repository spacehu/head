<?php
$data = \action\customer::$data['data'];
$class = \action\customer::$data['class'];
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
            <p>用户信息</p>
        </div>
        <div class="content">
            <form name="theForm" id="demo" action="./index.php?a=<?php echo $class; ?>&m=updateCustomer&id=<?php echo isset($data['id']) ? $data['id'] : ""; ?>" method="post" enctype='multipart/form-data'>
                <div class="pathA ">
                    <div class="leftA">
                        <div class="leftAlist" >
                            <span>NAME 用户名</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="r_row">
                                <input class="text" name="name" type="text" value="<?php echo isset($data['name']) ? $data['name'] : ""; ?>" />
                            </div>
                        </div>
                        <div class="leftAlist" >
                            <span>PHONE 手机</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="r_row">
                                <input class="text" name="phone" type="text" value="<?php echo isset($data['phone']) ? $data['phone'] : ""; ?>" />
                            </div>
                        </div>
                        <div class="leftAlist" >
                            <span>EMAIL 电子邮件</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="r_row">
                                <input class="text" name="email" type="text" value="<?php echo isset($data['email']) ? $data['email'] : ""; ?>" />
                            </div>
                        </div>
                        <div class="leftAlist" >
                            <span>SEX 性别</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="r_row">
                                <input class="text" name="sex" type="text" value="<?php echo isset($data['sex']) ? $data['sex'] : ""; ?>" />
                            </div>
                        </div>
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