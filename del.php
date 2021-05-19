<?php
    if(count($_POST) != 0 && array_key_exists('delete',$_POST)){
        $id = $_POST['delete'];
        $dir = "./loadFile/";
        if(file_exists($dir.$id)){
            $end = unlink($dir.$id);
            if($end){
                echo json_encode([
                    'code' => 0,
                    'msg' => '文件删除成功'
                ]);
            }else{
                echo json_encode([
                    'code' => 333,
                    'msg' => '文件删除失败'
                ]);
            }
        }else{
            echo json_encode([
                'code' => 444,
                'msg' => '未找到该文件'
            ]);
        }
    }
?>