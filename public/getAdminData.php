<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
  require('db.php');
  $data = json_decode(file_get_contents('php://input'));
  $userName = mysqli_real_escape_string($link, $data->{'userName'});
  $passhash = mysqli_real_escape_string($link, $data->{'passhash'});

  $success = false;
  $continue = false;
  if($userName && $passhash){
    $sql = "SELECT * FROM imjurUsers WHERE name LIKE \"$userName\" AND passhash LIKE BINARY \"$passhash\" AND admin = 1;";
    $res = mysqli_query($link, $sql);
    if(mysqli_num_rows($res)) $continue = true;
  }
  if($continue){
    $uploadDir     = 'uploads';
    $resourceDir   = 'resources';
    $ct            = 0;
    $fileSlugs     = [];
    $fileSizes     = [];
    $users         = [];
    $footprint     = 0;
    forEach(glob("$resourceDir/*") as $file){
      if(strpos($file, 'index.php') === false){
        $slugs[] = explode('.', explode('/', $file)[1])[0];
        $fs =  filesize($file);
        $footprint += $fs;
        $fileSizes[] = $fs;
        $ct++;
      }
    }
    $sql = "SELECT * FROM imjurUsers";
    $res = mysqli_query($link, $sql);
    for($i=0; $i<mysqli_num_rows($res); ++$i){
      $row = mysqli_fetch_assoc($res);
      $users[] = $row;
    }
    $adminData = mysqli_real_escape_string($link, json_encode([
      "slugs"           => $slugs,
      "fileSizes"       => $fileSizes,
      "users"           => $users,
      "number assets"   => $ct,
      "footprint"       => $footprint,
    ]));
    $success = true;
    echo json_encode([$success, $adminData]);
  }else{
    echo json_encode([$success]);
  }
?>