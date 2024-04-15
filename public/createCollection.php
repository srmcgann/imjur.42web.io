<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
        userName: this.state.username,
        password: this.state.password,
        colData
*/

  require_once('db.php');
  require_once('functions.php');
  $data = json_decode(file_get_contents('php://input'));
  $userID = mysqli_real_escape_string($link, $data->{'userID'});
  $passhash = mysqli_real_escape_string($link, $data->{'passhash'});
  $colData = $data->{'colData'};

  $success = false;
  if($userID && $passhash){
    $sql = "SELECT * FROM imjurUsers WHERE id = $userID AND passhash LIKE BINARY \"$passhash\";";
    $res = mysqli_query($link, $sql);
  }
  if(mysqli_num_rows($res)){
    
    $name = mysqli_real_escape_string($link, $colData->{'name'});
    $description = mysqli_real_escape_string($link, $colData->{'description'});
    $ar = $colData->{'slugs'};
    $slugs = [];
    forEach($ar as $slug){
      $slugs[] = mysqli_real_escape_string($link, $slug);
    }
    $ar = $colData->{'originalSlugs'};
    $originalSlugs = [];
    forEach($ar as $slug){
      $originalSlugs[] = mysqli_real_escape_string($link, $slug);
    }
    $private = mysqli_real_escape_string($link, $colData->{'private'});
    $serverTZO = getServerTZOffset();
    $meta = [];
    $meta['date'] = date("Y/m/d H:i:s", strtotime('now'));
    $meta['description'] = $description;
    $meta['slugs'] = $slugs;
    $meta['upvotes'] = 0;
    $meta['downvotes'] = 0;
    $meta['private'] = $private;
    $meta['views'] = 0;
    $meta['originalSlugs'] = $originalSlugs;
    $meta['serverTZO'] = $serverTZO;
    
    $meta = json_encode($meta);
    
    $sql = "INSERT INTO imjurCollections (
      userID,
      name,
      meta
    ) VALUES(
      $userID,
      \"$name\",
      \"$meta\"
    )";
    if(mysqli_query($link, $sql)){
      $ret = [];
      $ret['id'] = mysqli_insert_id($link);
      $ret['name'] = $name;
      $ret['userID'] = $userID;
      $ret['meta'] = $meta;
      $success = true;
      echo json_encode([$succes, $ret]);
    }else{
      echo json_encode([$success, 1, $sql]);
    }
  }else{
    echo json_encode([$success, 2, $sql]);
  }
?>