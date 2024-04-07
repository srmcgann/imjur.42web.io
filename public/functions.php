<?php
  require_once('db.php');
  
  function getServerTZOffset () {
    $tz = date_default_timezone_get();
    $t = new DateTimeZone("$tz");
    return $t->getOffset(new DateTime('now'));
  }
  
  function alphaToDec($val){
    $pow=0;
    $res=0;
    while($val!=""){
      $cur=$val[strlen($val)-1];
      $val=substr($val,0,strlen($val)-1);
      $mul=ord($cur)<58?$cur:ord($cur)-(ord($cur)>96?87:29);
      $res+=$mul*pow(62,$pow);
      $pow++;
    }
    return $res;
  }

  function decToAlpha($val){
    $alphabet="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $ret="";
    while($val){
      $r=floor($val/62);
      $frac=$val/62-$r;
      $ind=(int)round($frac*62);
      $ret=$alphabet[$ind].$ret;
      $val=$r;
    }
    return $ret==""?"0":$ret;
  }
 
  function genSlug(){
    global $link;
    $rndmax = getrandmax();
    do{
      $newid = floor($rndmax/2+rand());
      $sql = "SELECT id FROM imjurUploads WHERE id = $newid";
      $res = mysqli_query($link, $sql);
    }while(mysqli_num_rows($res));
    return decToAlpha($newid);
  }
  
  function checkUserNameAvailability($userName){
    global $link;
    $sql='SELECT * FROM imjurUsers WHERE name LIKE "'.$userName.'"';
    $res = mysqli_query($link, $sql);
    return mysqli_num_rows($res) === 0;
  }
?>