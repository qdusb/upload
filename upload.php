<?php
$uploaddir = './uploads/';//设置存储路径
$filename = $_FILES['Filedata']['name'];//获得选择的文件
$uploadfile = $uploaddir . $filename;//存储文件路径
$uploadfile = iconv('utf-8', 'gb2312', $uploadfile);//设置文件格式
move_uploaded_file($_FILES['Filedata']['tmp_name'], $uploadfile);//开始上传
?>