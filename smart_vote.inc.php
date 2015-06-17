<?php 
// echo "1111";
// print "1111";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'common.php';
// require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'class/geetest.class.php';
// require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'class/page.class.php';
if ("sign" == $_GET['model']) {








    include template("smart_vote:sign");
}else if("index" == $_GET['model']){

    $openid = $_COOKIE["openid"]; //openid
$unionid = $_COOKIE["unionid"]; //unionid
$urlmark = $_COOKIE["urlmark"]; //判断外部链接标识
$openidStr = $_COOKIE["openidStr"]; //关注标识
$redirect_uri = urlencode("http://test.geetest.com/discuz/discuz_31_UTF8/upload/source/plugin/smart_vote/oauth2.php");
if($openid == "" or $unionid == ""){
  if($urlmark == ""){
    header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3abef1b51f2838a1&redirect_uri=" . $redirect_uri . "&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
  exit();
  }
}else{
$rs = C::t("#smart_vote#info_atten")->fetch_by_openid($openid);
  if($rs>0){
    if($rs["cancel"] == 0){
      $openid = $_COOKIE["openid"]; //openid
      $unionid = $_COOKIE["unionid"]; //unionid
    $openidStr = ""; //已关注
    }else{
      $openidStr = "null"; //未关注
    }
  }else{
    $openidStr = "null"; //未关注
  }
}

    $pageSize = "5" ;
    $page = $_GET['page'];
    $count = C::t("#smart_vote#smart_vote")->count_all();
    if ($page == "" || !isset($page)) {
        $query = C::t("#smart_vote#smart_vote")->fetch_all('0','5');
        pageft($count,$pageSize,1,0,0,3);
    }else{

        $firstcount = ($page-1) * $pageSize;
        // $lastcount = $page * $pageSize;
        $query = C::t("#smart_vote#smart_vote")->fetch_all($firstcount,$pageSize);
        pageft($count,$pageSize,1,0,0,3);
    }
    // pageft($count,10);
    include template("smart_vote:index");
}else if ("value" == $_GET['model']) {
    $geetest = new GeetestLib();
    $geetest->set_privatekey("465719ad89db5cbe489cc051ff81a38e");
    $voteid = $_POST['voteid'];
    if (isset($_POST['geetest_challenge']) && isset($_POST['geetest_validate']) && isset($_POST['geetest_seccode'])) {
        $result = $geetest->validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode']);
        if ($result == TRUE) {

            $data = C::t("#smart_vote#smart_vote")->fetch_by_id($voteid);
            $where = array('votenum' => $data['votenum']+1);
            C::t("#smart_vote#smart_vote")->update_by_id($where,$voteid);
            echo "a";
            exit();
        } else if ($result == FALSE) {
            echo "b";
            exit();
        } else {
            echo "c";
            exit();
        }

    }
}else if ("post" == $_GET['model']) {

 // $filename = $_FILES['file']['type'];

$up=new upphoto;  
  $up->get_ph_tmpname($_FILES['file']['tmp_name']);  
  $up->get_ph_type($_FILES['file']['type']);  
  $up->get_ph_size($_FILES['file']['size']);  
  $up->get_ph_name($_FILES['file']['name']);  
  $up->save(); 
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $text = $_POST['text'];
    C::app()->init();
    // $time = $helper
    $data = array(
        'name' => $name,
        'mobile' => $mobile,
        'filepath' => $up->ph_name,
        'note' => $text,
    );
    C::t("#smart_vote#smart_vote")->insert($data);
    // include template("smart_vote:post");
}else if ("detail" == $_GET['model']) {

    $gid = $_GET['gid'];
    $detail = C::t("#smart_vote#smart_vote")->fetch_by_id($gid);

    include template("smart_vote:detail");
}else if ("seach" == $_GET['model']) {
    $seach = $_POST['seachid'];
    $by_id = C::t("#smart_vote#smart_vote")->fetch_by_id($seach);
    if ($by_id['name'] != "") {
        echo "true";
    }else{
        echo "flase";
    }
}else if ("math" == $_GET['model']) {
    $gname = $_GET['gname'];
    // file_put_contents("/home/tanxu/www/post.txt", $gname , FILE_APPEND);
    $where = "`name` = '$gname' ";
    $by_name = C::t("#smart_vote#smart_vote")->fetch_by_name($where);

    // file_put_contents("/home/tanxu/www/post.txt", print_r($by_name,true) , FILE_APPEND);
    if (!is_array($by_name) || empty($by_name)) {
        echo "error";
    }
        include template("smart_vote:seach");
}
 ?>