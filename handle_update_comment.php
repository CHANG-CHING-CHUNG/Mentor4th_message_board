<?php
require_once('./conn.php');
require_once('./utilis.php');
session_start();

$id;
$content;
$username = $_SESSION['username'];

if (!empty($_POST['id']) && !empty($_POST['comment'])) {
  $id = (int)$_POST['id'];
  $content = $_POST['comment'];

} else {
  header("Location: update_comment.php?id=". $_POST['id'] . "&errCode=1");
  die("請檢查資料");
}

$result_row = getUserRole($username);

$role_id =  $result_row['id'];
$role_name = $result_row['role_name'];
$role_add_post = $result_row['add_post'];
$role_delete_self_post =  $result_row['delete_self_post'];
$role_delete_any_post = $result_row['delete_any_post'];
$role_edit_self_post = $result_row['edit_self_post'];
$role_edit_any_post = $result_row['edit_any_post'];

// $sql = "UPDATE John_comments SET content = ? WHERE id = ? and username = ?";

if ($role_edit_any_post === 1) {
  // $sql = "UPDATE comments SET content = ? WHERE id = ?";
  $sql = "UPDATE John_comments SET content = ? WHERE id = ?";

  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("si",$content,$id);
  
    if ($stmt->execute()) {
      $stmt->close();
      header("Location: ./index.php?page=1");
    } else {
      header("Location: ./index.php?page=1");
      die("Failed" . $stmt->error);
    }
  } else {
    header("Location: ./index.php?page=1&errCode=3");
    die("Failed" . $stmt->error);
  }
} else if ($role_edit_self_post === 1) {
  // $sql = "UPDATE comments SET content = ? WHERE id = ? and username = ?";
  $sql = "UPDATE John_comments SET content = ? WHERE id = ? and username = ?";
  
  
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("sis",$content,$id,$username);
  
    if ($stmt->execute()) {
      $stmt->close();
      header("Location: ./index.php?page=1");
    } else {
      header("Location: ./index.php?page=1");
      die("Failed" . $stmt->error);
    }
  } else {
    header("Location: ./index.php?page=1&errCode=3");
    die("Failed" . $stmt->error);
  }
} else {
  header("Location: ./index.php?page=1");
  die("身份錯誤");
}




 ?>