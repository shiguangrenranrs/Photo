<?php
function base64_image_content($base64_image_content,$path){
    //匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        $type = $result[2];
        $new_file = $path."/".$_POST['id']."/";
        if(!file_exists($new_file)){
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0700);
        }
        $new_file = $new_file.date('Y-m-d H-i-s',time()).".{$type}";
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
            //return '/'.$new_file;
            return $new_file;
        }else{
            return false;
        }
    }else{
        return false;
    }
}
// 返回base64大小(KB)
function base64_image_size($base64_image_content){
    //data:image/jpeg;base64 这里要根据自己上传的图片格式进行相应的修改
    if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        $base64 = str_replace($result[1], '' ,$base64_image_content);
        $img_len = strlen($base64);
        $file_size = $img_len - ($img_len/8)*2;
        $file_size = $file_size/1024;
        // $file_size = number_format(($file_size/1024),2);
        return $file_size;
    }else{
        return false;
    }
}


if(count($_POST) != 0 && array_key_exists('img',$_POST) && array_key_exists('id',$_POST)){
    if(base64_image_size($_POST['img']) <= 1500){
        base64_image_content($_POST['img'],"./loadFile");
        echo json_encode([
            'code' => 0,
            'msg' => 'succeed'
        ]);
        // echo '<script>location.href="https://www.baidu.com";</script>';
    }else{
        echo json_encode([
            'code' => 999,
            'msg' => 'The size of the uploaded file exceeds 200KB'
        ]);
    }
}else{
    echo json_encode([
        'code' => 333,
        'msg' => 'Too few parameters, at least 2 parameters are required'
    ]);
}
?>