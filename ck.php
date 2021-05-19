<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./bootstrap3/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/ck.css">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./bootstrap3/js/bootstrap.min.js"></script>
</head>
<body>
    <main class="container">
        <header></header>
        <section>
        <?php
            function dir_is_empty($dir){
                $temp = scandir($dir);
                foreach($temp as $val){
                    if($val != '.' && $val != '..'){
                        return true;// 存在文件
                    }
                }
                return false;// 不存在文件
            }
            if(count($_GET) != 0 && array_key_exists('id',$_GET)){
                $id = $_GET['id'];
                $dir = "./loadFile/";
                if(file_exists($dir.$id) && dir_is_empty($dir.$id.'/')){
                    // 存在 且 不为空
                    $temp = scandir($dir.$id);
                    foreach($temp as $val){
                        if($val != '.' && $val != '..'){
                            echo '<img src="'.$dir.$id.'/'.$val.'">';
                            echo '<button type="button" class="btn btn-danger delete" value="'.$id.'/'.$val.'">删除该照片</button>';
                        }
                    }
                }else{
                    echo "<p>该QQ下没有任何照片</p>";
                }
            }else{
                echo '<script>location.href="./sczp.html"</script>';
            }
        ?>
        </section>
        <footer>
            <a href="./sczp.html" class="btn btn-info">返回生成链接首页</a>
        </footer>
    </main>
    <script>
        $(document).ready(function(){
            $(".delete").click(function(){
                let con = confirm('确定要删除该照片吗？');
                if(con){
                    $.post('./del.php',{
                        'delete':$(this).val(),
                    },function(data){
                        let d = JSON.parse(data);
                        alert(d.msg);
                        location.reload();
                    });
                }
            });
        });
    </script>
</body>
</html>