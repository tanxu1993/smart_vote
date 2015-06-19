<?php 
//获取access_token权限
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'common.php';
// $helper = new helper();
// $ip = $helper->getIp();
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
  $jsoninfo = json_decode($output, true);
  return $jsoninfo;
}
$appid = "wx3abef1b51f2838a1";
$appsecret = "ba328cab66a88d5bb4e996ba3f1e494f";
//Oauth2.0网页授权获取code
$code = $_GET["code"];
//获取openid
$openid_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
$result = curl_get($openid_url);
$openid = $result["openid"];
    file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "result=" . print_r($result,true) , FILE_APPEND);
//当前时间
$matchtime = time();
$token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
$query = C::t("#smart_vote#smart_token")->fetch_all('0','1');
if ($query == "" || $query == "null" || empty($query)) {
  $output_token = curl_get($token_url);
  $data = array('tokenid' => $output_token['access_token'] ,'timedate' => $matchtime );
  C::t("#smart_vote#smart_token")->insert($data);
}else if (is_array($query) && !empty($query)){
  $id = $query["4"]["id"];
  $tokenid = $query["4"]["tokenid"];  //access_token
  $extime = $query["4"]["timedate"];  //到期时间
  if ($matchtime>$extime) {
    $output_token = curl_get($token_url);
    $insertime = $matchtime + 6000 ;
    $data = array("tokenid" => $output_token['access_token'], "timedate" => $insertime);
    $where = "id IN ({$id})";
    C::t("#smart_vote#smart_token")->update_by_id($data,$where);
    $servertoken = $output_token['access_token'];
  }else{
    $servertoken = $tokenid;
  }
}
// echo $servertoken;

//获取唯一标识：unionid
$info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$servertoken&openid=$openid";
$output_info = curl_get($info_url);
    file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "output_info". print_r($output_info,true) , FILE_APPEND);
// $unionid = $output_info["unionid"]; //唯一标识：unionid

// echo "unid=" . $unionid;
$nickname = $output_info['nickname'];
// $nickname = urldecode($nickname);
// $nickname = iconv('utf-8','gbk',$nickname);
$openid = $output_info['openid'];
$rs = C::t("#smart_vote#smart_info")->fetch_by_openid($openid);
    file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "rs=" . print_r($rs,true) , FILE_APPEND);
if(!empty($rs)){
  if($rs["cancel"] == 1){
    //openid存入COOIE
    setcookie("openid",$rs["openid"],time()+3600*24,"/");
    $_COOKIE["openid"] = $rs["openid"];
    file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "cook_openid=" . $_COOKIE["openid"] , FILE_APPEND);
    //unionid存入COOIE
    // setcookie("unionid",$rs["unionid"],time()+3600*24,"/");
    // $_COOKIE["unionid"] = $rs["unionid"];
    
    //关注标识存入COOIE
    setcookie("openidStr","1",time()+3600*24,"/");
    $_COOKIE["openidStr"] = "1";
    file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "cook_openid=" . $_COOKIE["openidStr"] , FILE_APPEND);
  }else{
    setcookie("openid",$rs["openid"],time()+3600*24,"/");
    $_COOKIE["openid"] = $rs["openid"];
    //关注标识存入COOIE
    setcookie("openidStr","null",time()+3600*24,"/");
    $_COOKIE["openidStr"] = "null";
    
    //外部标识存入Cooies
    setcookie("urlmark","null",time()+3600*24,"/");
    $_COOKIE["urlmark"] = "null"; 
  } 
}else{
  $insert_data = array(
        'openid' => $openid,
        'nickname' => $nickname,
        'cancel' => $output_info['subscribe'],
        'timedate' => $output_info['subscribe_time']
      );
    file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "insert_data=" . print_r($insert_data,true) , FILE_APPEND);

  $rs = C::t("#smart_vote#smart_info")->insert($insert_data);
//   //关注标识存入COOIE
  setcookie("openidStr",$output_info['subscribe'],time()+3600*24,"/");
  $_COOKIE["openidStr"] = $output_info['subscribe'];
  
  //外部标识存入Cooies
  setcookie("urlmark","null",time()+3600*24,"/");
  $_COOKIE["urlmark"] = "null";
}
//     file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "success" , FILE_APPEND);
header("Location: http://test.geetest.com/discuz/discuz_31_UTF8/upload/plugin.php?id=smart_vote&model=index");

?>