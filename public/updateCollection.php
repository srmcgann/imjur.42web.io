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
    $id = mysqli_real_escape_string($link, $colData->{'id'});
    $sql = "SELECT * FROM imjurCollections WHERE id = $id";
    $res = mysqli_query($link, $sql);
    if(mysqli_num_rows($res)){
      $row = mysqli_fetch_assoc($res);
      $description = $colData->{'description'}; //escaped later
      $slugs = $colData->{'slugs'};
      $originalSlugs = $colData->{'originalSlugs'};
      $private = $colData->{'private'};
      $meta = [];
      $meta['description'] = $description;
      $meta['slugs'] = $slugs;
      $meta['private'] = $private;
      $meta['originalSlugs'] = [];

      $meta['date'] = $row['date'];
      $meta['upvotes'] = $row['upvotes'];
      $meta['downvotes'] = $row['downvotes'];
      $meta['views'] = $row['views'];
      $meta['serverTZO'] = $row['serverTZO'];

      $skip = false;
      forEach($slugs as $slug){
        $sql = "SELECT originalSlug FROM imjurUploads WHERE slug LIKE BINARY \"$slug\"";
        $res = mysqli_query($link, $sql);
        if(mysqli_num_rows($res)){
          $meta['originalSlugs'][] = mysqli_fetch_assoc($res)['originalSlug'];
        }else{
          $skip = true;
        }
      }
      if(!$skip){
        $meta_ = $meta;
        $meta = mysqli_real_escape_string($link, json_encode($meta));
        $sql = "UPDATE imjurCollections SET name = \"$name\", meta = \"$meta\" WHERE userID = $userID AND id = $id";
        if(mysqli_query($link, $sql)){
          $success = true;
          echo json_encode([$success]);
        }else{
          echo json_encode([$success, 1, $sql]);
        }
      }else{
        echo json_encode([$success, 2, $sql]);
      }
    }else{
      echo json_encode([$success, 3, $sql]);
    }
  }else{
    echo json_encode([$success, 4, $sql]);
  }
?>