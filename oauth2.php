<?php 
//获取access_token权限
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'common.php';
C::app()->init();
// set_error_handler('errorHandler');

// function errorHandler( $errno, $errstr, $errfile, $errline, $errcontext)
// {
//   $str = 'Into '.__FUNCTION__.'() at line '.__LINE__.
//   "\n\n---ERRNO---\n". print_r( $errno, true).
//   "\n\n---ERRSTR---\n". print_r( $errstr, true).
//   "\n\n---ERRFILE---\n". print_r( $errfile, true).
//   "\n\n---ERRLINE---\n". print_r( $errline, true).
//   "\n\n---ERRCONTEXT---\n".print_r( $errcontext, true).
//   "\n\nBacktrace of errorHandler()\n". print_r( debug_backtrace(), true);
// file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "str=".$str."\r\n", FILE_APPEND);
// }
function curl_get($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  curl_close($ch);
  return $output;
}
$appid = "wx3abef1b51f2838a1";
$appsecret = "ba328cab66a88d5bb4e996ba3f1e494f";
//Oauth2.0网页授权获取code
$code = $_GET["code"];
// //获取openid
// $state = $_GET["state"];

$openid_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";


$output = curl_get($openid_url);
// echo $outputa;
$jsoninfo = json_decode($output, true);

$openid = $jsoninfo["openid"];
// echo $openid . "/";

//当前时间
$matchtime = time();
$token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $query = C::t("#smart_vote#smart_token")->fetch_all('0','1');
// file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", print_r($query,true), FILE_APPEND);        
if ($query == "" || $query == "null" || empty($query)) {
  $output_token = curl_get($token_url);
  $jsontoken = json_decode($output_token, true);
  $data = array('tokenid' => $jsontoken['access_token'] ,'timedate' => $matchtime );
  C::t("#smart_vote#smart_token")->insert($data);

}else if (is_array($query) && !empty($query)){
  $id = $query["id"];
  $tokenid = $query["tokenid"];  //access_token
  $extime = $query["timedate"];  //到期时间
  if ($matchtime>$extime) {
    $output_token = curl_get($token_url);
    $jsontoken = json_decode($output_token, true);
    $insertime = $matchtime + 6000 ;
    $data = "`tokenid`= {$jsontoken['access_token']}  and `timedate`='$insertime' ";
    echo $data;
    
    C::t("#smart_vote#smart_token")->update_by_id($data,$id);
    $servertoken = $jsontoken['access_token'];
  }else{
    $servertoken = $tokenid;
  }
}
echo $servertoken;
// if($matchtime>$extime){
//   $token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
//   $ch = curl_init();
//   curl_setopt($ch, CURLOPT_URL, $token_url);
//   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
//   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//   $output = curl_exec($ch);
//   curl_close($ch);
//   $jsoninfo = json_decode($output, true);
//   $access_token = $jsoninfo["access_token"];
//   $data = "`tokenid`='$access_token' and `timedate`='$matchtime' ";
//   $query = C::t("#smart_vote#smart_token")->update_by_id($data,'0');
//   $servertoken = $access_token;
// }else{
//   $servertoken = $tokenid;
// }

// //获取唯一标识：unionid
// $urlb = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$servertoken&openid=$openid";
// $chb = curl_init();
// curl_setopt($chb, CURLOPT_URL, $urlb);
// curl_setopt($chb, CURLOPT_SSL_VERIFYPEER, FALSE); 
// curl_setopt($chb, CURLOPT_SSL_VERIFYHOST, FALSE); 
// curl_setopt($chb, CURLOPT_RETURNTRANSFER, 1);
// $outputb = curl_exec($chb);
// curl_close($chb);
// $jsoninfob = json_decode($outputb, true);

// $unionid = $jsoninfob["unionid"]; //唯一标识：unionid

// echo "unid=" . $unionid;
// $rs = C::t("#smart_vote#info_atten")->fetch_by_unionid($unionid);

// if($rs>0){
//   if($rs["cancel"] == 0){
//     //openid存入COOIE
//     setcookie("openid",$rs["openid"],time()+3600*365,"/");
//     $_COOKIE["openid"] = $rs["openid"];
  
//     //unionid存入COOIE
//     setcookie("unionid",$rs["unionid"],time()+3600*365,"/");
//     $_COOKIE["unionid"] = $rs["unionid"];
    
//     //关注标识存入COOIE
//     setcookie("openidStr","",time()+3600*365,"/");
//     $_COOKIE["openidStr"] = "";
//   }else{
//     //关注标识存入COOIE
//     setcookie("openidStr","null",time()+3600*365,"/");
//     $_COOKIE["openidStr"] = "null";
    
//     //外部标识存入Cooies
//     setcookie("urlmark","null",time()+3600*365,"/");
//     $_COOKIE["urlmark"] = "null"; 
//   } 
// }else{
//   //关注标识存入COOIE
//   setcookie("openidStr","null",time()+3600*365,"/");
//   $_COOKIE["openidStr"] = "null";
  
//   //外部标识存入Cooies
//   setcookie("urlmark","null",time()+3600*365,"/");
//   $_COOKIE["urlmark"] = "null"; 
// }
//     file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "success" , FILE_APPEND);
// // header("Location: apply.php");

?>