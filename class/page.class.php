<?php
@$page = ceil($_GET['page']);

if(!function_exists('pageft')){
  function pageft($totle,$displaypg=20,$shownum=0,$showtext=0,$showselect=0,$showlvtao=5,$showselects=20,$showjump=0,$url=''){
    global $page,$firstcount,$pagenav;
    $GLOBALS["displaypg"]=$displaypg;

    if(!$page||$page<0) $page=1;
    
    if(!$url){
      $url=$_SERVER["REQUEST_URI"];
    }
    
    $parse_url=parse_url($url);
    $url_query=$parse_url["query"]; 
    
    if($url_query){
      $url_query=ereg_replace("(^|&)page=$page","",$url_query);
      $url=str_replace($parse_url["query"],$url_query,$url);
      if($url_query) $url.="&page"; else $url.="page";
    }else {
      $url.="?page";
    }

    $lastpg=ceil($totle/$displaypg); 
    $page=min($lastpg,$page);
    $prepg=$page-1;
    $nextpg=($page==$lastpg ? 0 : $page+1);
    $firstcount=($page-1)*$displaypg;
    
    if($firstcount<0){
      $firstcount = 0;
    }else{
      $firstcount=($page-1)*$displaypg;
    }
    
    if($page>$lastpg) $page=$lastpg;

    if($totle >= 1){
      $pagenav = '';
    }else{
      $pagenav='';
    }

    if($lastpg<=1) return false;

    if($prepg) $pagenav.='<dd class="home"><a href="'.$url.'=1">首页</a></dd>'; else $pagenav.='<dd class="home_no">首页</dd>';
    
    if($shownum==1){
    
      $o=$showlvtao;
      $u=ceil($o/2);
      $f=$page-$u;
      
      if($f<0){
        $f=0;
      }
    
      $n=$lastpg;
      
      if($n<1){
        $n=1;
      }
      
      if($page<=2){
        if($page==1){
          $pagenav.='<dd class="num_out">1</dd>';
        }else{
          $pagenav.='<dd class="num"><a href="'.$url.'=1">1</a></dd>';
        }
      }
      
      for($i=1;$i<=$o;$i++){
      
        if($n<=1){
          break;
        }
        
        $c=$f+$i;
        
        if($c==1){
          continue;
        }
        
        if($c==$n){
          break;
        }
        
        if($c==$page){
          $pagenav.='<dd class="num_out">'.$page.'</dd>';
        }else{
          $pagenav.='<dd class="num"><a href="'.$url."=".$c.'">'.$c.'</a></dd>';
        }
        
        if($i>$n){
          break;
        }   
      }
      
      if($page>$n-2){
        if($page==$n){
          $pagenav.='<dd class="num_out">'.$n.'</dd>';
        }else{
          $pagenav.='<dd class="num"><a href="'.$url."=".$n.'">'.$n.'</a></dd>';
        }
      }
      
    }
    if($nextpg) $pagenav.='<dd class="last"><a href="'.$url."=".$lastpg.'">末页</a></dd>'; else $pagenav.='<dd class="last_no">末页</dd>';
  }
}
?>