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
 
	function exists($url){
    if(strpos($url, '.') !== false){
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_NOBODY, true);
      curl_exec($ch);
      $r = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      return $r==200?1:0;
    }else{
      return false;
    }
	}

	function retrieve_remote_file_size($url){

		/*
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_NOBODY, TRUE);
		curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		$data = curl_exec($ch);
		$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		curl_close($ch);
		return $size;
		*/
		$head = array_change_key_case(get_headers($url, TRUE));
		return $head['content-length'];
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