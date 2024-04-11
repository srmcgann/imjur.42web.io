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
    $orphans       = [];
    $footprint     = 0;
    forEach(glob("$resourceDir/*") as $file){
      if(strpos($file, 'index.php') === false){
        $slug = explode('.', explode('/', $file)[1])[0];
        $slugs[] = $slug;
        $fs =  filesize($file);
        $footprint += $fs;
        $fileSizes[] = $fs;
        $ct++;
        $sql = "SELECT * FROM imjurUploads WHERE originalSlug LIKE BINARY \"$slug\"";
        $res = mysqli_query($link, $sql);
        if(!mysqli_num_rows($res)){
          $orphans[] = $slug;
        }
      }
    }
    $sql = "SELECT * FROM imjurUsers";
    $res = mysqli_query($link, $sql);
    for($i=0; $i<mysqli_num_rows($res); ++$i){
      $row = mysqli_fetch_assoc($res);
      $userID = $row['id'];
      $sql = "SELECT * FROM imjurUploads WHERE userID = $userID";
      $res2 = mysqli_query($link, $sql);
      $row['slugs']         = [];
      $row['originalSlugs'] = [];
      $row['sizes']         = [];
      for($j=0; $j<mysqli_num_rows($res2); ++$j){
        $row2 = mysqli_fetch_assoc($res2);
        $row['sizes'][]         = $row2['size'];
        $row['slugs'][]         = $row2['slug'];
        $row['originalSlugs'][] = $row2['originalSlug'];
      }
      
      $users[] = $row;
    }
    $adminData = json_encode([
      "slugs"           => $slugs,
      "fileSizes"       => $fileSizes,
      "users"           => $users,
      "number assets"   => $ct,
      "footprint"       => $footprint,
      "orphaned assets" => $orphans,
    ]);
    $success = true;
    echo json_encode([$success, $adminData]);
  }else{
    echo json_encode([$success]);
  }
?>