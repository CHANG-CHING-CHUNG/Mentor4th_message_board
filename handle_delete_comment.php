<?php
require_once('./conn.php');
require_once('./utilis.php');
session_start();

$id;
$username = $_SESSION['username'];

if (!empty($_GET['id'])) {
  $id = (int)$_GET['id'];
} else {
  header("Location: ./index.php?page=1&errCode=1");
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


// $sql = "UPDATE John_comments SET is_deleted = 1 WHERE id = ? and username = ?";

if ($role_delete_any_post === 1) {
  // $sql = "UPDATE comments SET is_deleted = 1 WHERE id = ?";
  $sql = "UPDATE John_comments SET is_deleted = 1 WHERE id = ?";

  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i",$id);
  
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
} else if ($role_delete_self_post === 1) {
  // $sql = "UPDATE comments SET is_deleted = 1 WHERE id = ? and username = ?";
  $sql = "UPDATE John_comments SET is_deleted = 1 WHERE id = ? and username = ?";
  
  
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("is",$id,$username);
  
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