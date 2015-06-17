<?php 
//获取access_token权限
$appid = "wx3abef1b51f2838a1";
$appsecret = "ba328cab66a88d5bb4e996ba3f1e494f";

//Oauth2.0网页授权获取code
$code = $_GET['code'];
echo "code=" . $code;
// //获取openid
// $testurl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx3abef1b51f2838a1&secret=ba328cab66a88d5bb4e996ba3f1e494f";


$urla = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
echo $urla;
$cha = curl_init();
curl_setopt($cha, CURLOPT_URL, $urla);
curl_setopt($cha, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($cha, CURLOPT_SSL_VERIFYHOST, FALSE); 
curl_setopt($cha, CURLOPT_RETURNTRANSFER, 1);
$outputa = curl_exec($cha);
echo $outputa;
// $openid_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $appsecret ."&code=" . $code . "&grant_type=authorization_code";
curl_close($cha);
$jsoninfoa = json_decode($outputa, true);
// $openid = $jsoninfoa["openid"];

// echo "openid=" . $openid;
//当前时间
$matchtime = time();

$query = C::t("#smart_vote#wxinfo")->fetch_all('0','1');
$wxid = $query["id"];
$tokenid = $query["tokenid"];  //access_token
$extime = $query["timedate"];  //到期时间

if($matchtime>$extime){
  $token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $token_url);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  curl_close($ch);
  $jsoninfo = json_decode($output, true);
  $access_token = $jsoninfo["access_token"];
  $data = "`tokenid`='$access_token' and `timedate`='$matchtime' ";
  $query = C::t("#smart_vote#wxinfo")->update_by_id($data,'0');
  $servertoken = $access_token;
}else{
  $servertoken = $tokenid;
}

//获取唯一标识：unionid
$urlb = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$servertoken&openid=$openid";
$chb = curl_init();
curl_setopt($chb, CURLOPT_URL, $urlb);
curl_setopt($chb, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($chb, CURLOPT_SSL_VERIFYHOST, FALSE); 
curl_setopt($chb, CURLOPT_RETURNTRANSFER, 1);
$outputb = curl_exec($chb);
curl_close($chb);
$jsoninfob = json_decode($outputb, true);

$unionid = $jsoninfob["unionid"]; //唯一标识：unionid

echo "unid=" . $unionid;
$rs = C::t("#smart_vote#info_atten")->fetch_by_unionid($unionid);

if($rs>0){
  if($rs["cancel"] == 0){
    //openid存入COOIE
    setcookie("openid",$rs["openid"],time()+3600*365,"/");
    $_COOKIE["openid"] = $rs["openid"];
  
    //unionid存入COOIE
    setcookie("unionid",$rs["unionid"],time()+3600*365,"/");
    $_COOKIE["unionid"] = $rs["unionid"];
    
    //关注标识存入COOIE
    setcookie("openidStr","",time()+3600*365,"/");
    $_COOKIE["openidStr"] = "";
  }else{
    //关注标识存入COOIE
    setcookie("openidStr","null",time()+3600*365,"/");
    $_COOKIE["openidStr"] = "null";
    
    //外部标识存入Cooies
    setcookie("urlmark","null",time()+3600*365,"/");
    $_COOKIE["urlmark"] = "null"; 
  } 
}else{
  //关注标识存入COOIE
  setcookie("openidStr","null",time()+3600*365,"/");
  $_COOKIE["openidStr"] = "null";
  
  //外部标识存入Cooies
  setcookie("urlmark","null",time()+3600*365,"/");
  $_COOKIE["urlmark"] = "null"; 
}
    file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "success" , FILE_APPEND);
// header("Location: apply.php");

?>