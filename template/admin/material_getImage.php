<?php
$data = \action\material::$data['data'];
$config = \action\material::$data['config'];
$st = \action\material::$data['st'];
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script type="text/javascript" src="js/jquery.js"></script>
        <!-- 图片控件 -->
        <script src="lib/cos-js-sdk-v5-master/dist/cos-js-sdk-v5.js"></script>
        <script type="text/javascript" src="js/tencent_cos.js"></script>
        <title>无标题文档</title>
    </head>

    <body>
        <div class="status r_top">
        </div>
        <div class="content">
            <form name="theForm" id="demo" action="./index.php?a=material&m=updateImage&id=<?php echo $data['id']; ?>" method="post" enctype='multipart/form-data'>
                <div class="pathA ">
                    <div class="leftA">
                        <div class="leftAlist" >
                            <span>NAME 素材名</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="r_row">
                                <input class="text" name="name" type="text" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" />
                                <input type="hidden" name="st" value="<?php echo $st;?>">
                            </div>
                        </div>
                        <div class="leftAlist" >
                            <span>ORIGINAL SRC 原图</span>
                        </div>
                        <div class="leftAlist" >
                            <div class="r_row">
                                <INPUT TYPE="file" NAME="file_url" id="f1" />
                                <input type="hidden" name="edit_doc" id="edit_doc" value="./img/no_img.jpg" />
                            </div>
                            <div class="r_row">
                                <div class="r_title">&nbsp;</div>
                                <img class="r_row_img" src="<?php echo isset($data['original_src']) ? $data['original_src'] : './img/no_img.jpg'; ?>" />
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
        
        <script type="text/javascript">
            $(function () {
                var config = {
                    Bucket: "<?php echo $config['lib']['tencent']['cos']['bucket']; ?>",
                    Region: "<?php echo $config['lib']['tencent']['cos']['region']; ?>",
                    imagePath: "<?php echo $config['path']['image']; ?>",
                    mediaPath: "<?php echo $config['path']['media']; ?>",
                    filename: "<?php echo time(); ?>",
                    url: "<?php echo $config['lib']['tencent']['cos']['url']; ?>"
                };
                // 监听选文件
                $("#f1").live("change", function () {
                    var file = this.files[0];
                    var obj = $(this);
                    if (!file)
                        return;
                    var fileExtension = file.name.split('.').pop();
                    var _file = config.imagePath + "/" +  (new Date()).valueOf() + "." + fileExtension;
                    cos.putObject({
                        Bucket: config.Bucket, /* 必须 */
                        Region: config.Region, /* 必须 */
                        //Key:  file.name,              /* 必须 */
                        Key: _file,
                        Body: file
                    }, function (err, data) {
                        console.log(err || data);
                        obj.parent().next().find(".r_row_img").attr("src", config.url + _file);
                        obj.next().attr("value", config.url + _file);
                        $(".f2").attr("value", "");
                    });
                });
            });
        </script>
    </body>
</html>