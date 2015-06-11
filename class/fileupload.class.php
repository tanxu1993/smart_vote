<?php
$uptypes=array(
'image/jpg',
'image/jpeg',
'image/pjpeg',
'image/png',
'image/gif',
'image/bmp',
'image/x-png'
);
?>
<html>
<head>
<title>图片上传程序</title>
</head>
<body>
<form name="upform" method="post" enctype="multipart/form-data" />
上传文件：
<input name="upfile" type="file"/>
<input type="submit" value="上传"/><br />
允许上传的文件类型：<?=implode(", ",$uptypes)?>
</form>
<?php

$max_file_size=2000000;
$destination_folder="uploadimg/";
$imgpreview=1;
$imgpreviewsize=1/2;
if($_SERVER["REQUEST_METHOD"]== "POST") {
    if(!is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
        //检查文件是否存在
        echo "文件不存在!";
        exit;
    }
    $file=$_FILES["upfile"];
    if($max_file_size<$file["size"]) {
        //检查文件大小
        echo "文件太大!";
        exit;
    }
    if(!in_array($file["type"],$uptypes)) {
        //检查文件类型
        echo "文件类型错误".$file["type"];
        exit;
    }
    if(!file_exists($destination_folder)) {
        mkdir($destination_folder);
    }
    $filename=$file["tmp_name"];      //上传到缓存区的临时文件名称
//echo $filename;
    $image_size=getimagesize($filename);
//echo $file["name"];
    $pinfo=pathinfo($file["name"]); // $file["name"] 客户端文件的原名称
    $ftype=$pinfo["extension"];
    $destination=$destination_folder.time().".".$ftype;
    if(file_exists($destination)&&$overwrite !=true) {
        echo "同名文件已经存在了!";
        exit;
    }
    if(!move_uploaded_file($filename,$destination)) {
        echo "移动文件错误!";
        exit;
    }
    $pinfo=pathinfo($destination);
    $fname=$pinfo["basename"];
    echo "<font color=red>已经成功上传!</font><br />文件名：<font color=blue>".$destination_folder.$fname."</font><br />";
    echo " 宽度: ".$image_size[0]." 长度： ".$image_size[1]."<br />大小：".$file["size"]." bytes";
    if($imgpreview==1) {
        echo "<br/>图片浏览:<br />";
        echo "<img src="/" mce_src="/""".$destination."/" width=".($image_size[0]*$imgpreviewsize)."height=".($image_size[1]*$imgpreviewsize);
        echo " alt=/"图片浏览:/r文件名".$destination."/r上传时间:/" />";
    }
}
?>