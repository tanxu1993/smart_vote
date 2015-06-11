<?php 
 //文件上传
class helper{
     //获取当前的时间戳（精确到毫秒）
    public static function float_time() {
        list($usec, $sec) = explode(' ', microtime());
        return (float) $usec + (float) $sec;
    }

     // 获取IP
    public static function getIp() {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && !empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_CLIENT_IP"]) && !empty($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
        return $realip;
    }
}


 ?>