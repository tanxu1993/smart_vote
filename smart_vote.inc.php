<?php 
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'common.php';
// require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'class/geetest.class.php';
// require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'class/page.class.php';
if ("sign" == $_GET['model']) {








    include template("smart_vote:sign");
}else if("index" == $_GET['model']){

    $openid = $_COOKIE["openid"]; //openid
    // $unionid = $_COOKIE["unionid"]; //unionid
    $urlmark = $_COOKIE["urlmark"]; //判断外部链接标识
    $openidStr = $_COOKIE["openidStr"]; //关注标识
        file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "openid=" . $openid , FILE_APPEND);
    // setcookie("openid",$openid,time()-3600,"/");
    if($openid == ""){
      // if($urlmark == ""){
        header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3abef1b51f2838a1&redirect_uri=http://test.geetest.com/discuz/discuz_31_UTF8/upload/source/plugin/smart_vote/oauth2.php&response_type=code&scope=snsapi_base&state=123#wechat_redirect");
      exit();
      // }
    }else{
    $rs = C::t("#smart_vote#smart_info")->fetch_by_openid($openid);
      if(!empty($rs)){
        if($rs["cancel"] == 1){
          $openid = $_COOKIE["openid"]; //openid
          // $unionid = $_COOKIE["unionid"]; //unionid
        $openidStr = "1"; //已关注
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
    $openid = $_COOKIE["openid"]; //openid
    $today = time();
    $exitdate = strtotime(date('Y-m-d 23:59:59'));

    $geetest = new GeetestLib();
    $geetest->set_privatekey("465719ad89db5cbe489cc051ff81a38e");
    $voteid = $_POST['voteid'];
    if (isset($_POST['geetest_challenge']) && isset($_POST['geetest_validate']) && isset($_POST['geetest_seccode'])) {
        $result = $geetest->validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode']);
        if ($result == TRUE) {
            //投票限制
            if($openid == ""){
              echo "feifa"; //非法提交
              exit();
            }

//----------------------
//             $urlsub = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$subtoken&openid=$openid";
// $chsub = curl_init();
// curl_setopt($chsub, CURLOPT_URL, $urlsub);
// curl_setopt($chsub, CURLOPT_SSL_VERIFYPEER, FALSE); 
// curl_setopt($chsub, CURLOPT_SSL_VERIFYHOST, FALSE); 
// curl_setopt($chsub, CURLOPT_RETURNTRANSFER, 1);
// $outputsub = curl_exec($chsub);
// curl_close($chsub);
// $jsoninfosub = json_decode($outputsub, true);


//------------------------

            //得到id，根据id查询用户是否存在
            $vote_user = C::t("#smart_vote#smart_vote")->fetch_by_id($voteid);
            file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "vote_user=" . print_r($vote_user,true) . "\r\n", FILE_APPEND);

            if (!empty($vote_user)) {
                //查找投票统计表中是否存在投票者
                $voter = C::t("#smart_vote#smart_count")->fetch_by_openid($openid);
            file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "voter=" . print_r($voter,true) . "\r\n", FILE_APPEND);


                $extime = $voter["timedate"]; //到期时间
                if (!empty($voter)) { //存在
                    if ($today>$extime) { //时间过期，新的一天重新记录
                        //预留  一分钟内只能一票，否则判断为重复刷票
                                    file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "extime=" . $extime . "\r\n", FILE_APPEND);
                                    file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "today=" . $today . "\r\n", FILE_APPEND);
                        $data = array(
                                "openid" => $openid,
                                "uid" => $voteid,
                                // "ip" => ,
                                "startime" => $today,
                                "timedate" => $exitdate
                            );
                          //给投票表插入新纪录
                        C::t("#smart_vote#smart_contact")->insert($data);
                          //修改用户表中票数
                        $vote_data = C::t("#smart_vote#smart_vote")->fetch_by_id($voteid);
                        $where1 = array('votenum' => $vote_data['votenum']+1);
                        C::t("#smart_vote#smart_vote")->update_by_id($where1,$voteid);
                          //修改统计表纪录
                        $count_data= array('count' => '1');
                        $count_res = C::t("#smart_vote#smart_count")->fetch_by_openid($openid);
                        C::t("#smart_vote#smart_count")->update_by_id($count_data,$count_res['cid']);
                        file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "q1=" . "\r\n", FILE_APPEND);
                        echo "success";
                        exit();
                    }else{
                         //判断统计数是否达到上限和时间是否到24小时
                        if ($voter['count'] == 5) { //时间未过期,达到上限
                            echo "shangxian";//24小时内已经达到上限
                            exit();
                        }
                        $rsc = C::t("#smart_vote#smart_contact")->fetch_by_uid($voteid,$openid,$today);
                        file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "rsc=" . print_r($rsc,true) . "\r\n", FILE_APPEND);
                        if (!empty($rsc)) { 
                             echo "24xiangtong";//24小时内不能给同一选手投票
                            exit();
                        }
                        //给投票表插入新纪录
                        $insert_data = array(
                                "openid" => $openid,
                                "uid" => $voteid,
                                // "ip" => ,
                                "startime" => $today,
                                "timedate" => $exitdate
                            );
                        C::t("#smart_vote#smart_contact")->insert($insert_data);
                        //修改用户表中票数
                        $vote_data = C::t("#smart_vote#smart_vote")->fetch_by_id($voteid);
                        $where1 = array('votenum' => $vote_data['votenum']+1);
                        C::t("#smart_vote#smart_vote")->update_by_id($where1,$voteid);
                          //修改统计表纪录
                      
            file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "votercount=" .$voter['count'] . "\r\n", FILE_APPEND);
                        $data_count = array('count' => $voter['count']+1);
                        $count_res = C::t("#smart_vote#smart_count")->fetch_by_openid($openid);
                        C::t("#smart_vote#smart_count")->update_by_id($data_count,$count_res['cid']);
            file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "q2=" . "\r\n", FILE_APPEND);
                        echo "success";
                        exit();
                    }
                }else{
                    //投票统计表中不存在投票者
                        //给投票表插入新纪录
                        $insert_data = array(
                                "openid" => $openid,
                                "uid" => $voteid,
                                // "ip" => ,
                                "startime" => $today,
                                "timedate" => $exitdate
                            );
                    file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "insert_data=".$insert_data . "\r\n", FILE_APPEND);

                        C::t("#smart_vote#smart_contact")->insert($insert_data);
                        //修改用户表中票数
                        $vote_data = C::t("#smart_vote#smart_vote")->fetch_by_id($voteid);
                    file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "vote_data=".$vote_data . "\r\n", FILE_APPEND);
                        $where1 = array('votenum' => $vote_data['votenum']+1);
                        C::t("#smart_vote#smart_vote")->update_by_id($where1,$voteid);
                        $count_data = array(
                                'openid'=>$openid,
                                'count'=>'1',
                                'timedate'=>$exitdate
                            );
                        C::t("#smart_vote#smart_count")->insert($count_data);
            file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "q3=" . "\r\n", FILE_APPEND);
                        echo "success";
                        exit();

                }
                
            }else{
                echo "yonghubucunzai";
                        exit();
            }

            // $rs = C::t("#smart_vote#smart_info")->fetch_by_openid($openid);
            // file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "cancel=" . print_r($rs,true) , FILE_APPEND);
            // if(!empty($rs)){
            //     if($rs["cancel"] == 0){
            //         echo "weiguanzhu"; //未关注
            //         file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "cancel=0" , FILE_APPEND);
            //         exit();
            //     }else if ($rs["cancel"] == 1) {
            //         $data = C::t("#smart_vote#smart_vote")->fetch_by_id($voteid);
            //         $where = array('votenum' => $data['votenum']+1);
            //         C::t("#smart_vote#smart_vote")->update_by_id($where,$voteid);
            //         echo "success";
            //         file_put_contents("/www/discuz/discuz_31_UTF8/upload/source/plugin/post.txt", "cancel=1" , FILE_APPEND);
            //         exit();
            //     }
            //  }else{
            //     echo "weiguanzhu";
            //     exit();
            //  }
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