<?php 

$appid = "wx3abef1b51f2838a1";
$appsecret = "ba328cab66a88d5bb4e996ba3f1e494f";

$openid = $_COOKIE["openid"]; //openid
$unionid = $_COOKIE["unionid"]; //unionid
$urlmark = $_COOKIE["urlmark"]; //判断外部链接标识
$openidStr = $_COOKIE["openidStr"]; //关注标识

//当前时间
$matchtime = time();

$query = C::t("#smart_vote#wxinfo")->fetch_all('0','1');
$id = $query["id"];
$tokenid = $query["tokenid"];  //access_token
$extime = $query["timedate"];  //到期时间

if($matchtime>$extime){

  $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  curl_close($ch);
  $jsoninfo = json_decode($output, true);
  $access_token = $jsoninfo["access_token"];

  $sql="update subinfo set tokenid='$access_token',timedate=".time()."+6000 where sid=$sid";
  mysql_query($sql, $connection);
  $subtoken = $access_token;
  
}else{

  $subtoken = $tokenid;
  
}
 ?>