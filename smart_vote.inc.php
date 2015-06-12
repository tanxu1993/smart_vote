<?php 
// echo "1111";
// print "1111";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'common.php';
// require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'class/geetest.class.php';
// require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'class/page.class.php';
if ("sign" == $_GET['model']) {








    include template("smart_vote:sign");
}else if("index" == $_GET['model']){
    $page = $_GET['page'];
    $pageSize = "5" ;
    $firstcount = ($page-1) * $pageSize;
    $lastcount = $page * $pageSize;
    $query = C::t("#smart_vote#smart_vote")->fetch_all($firstcount,$lastcount);
    $count = C::t("#smart_vote#smart_vote")->count_all();
    // pageft($count,10);
    pageft($count,$pageSize,1,0,0,3);
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
}
 ?>