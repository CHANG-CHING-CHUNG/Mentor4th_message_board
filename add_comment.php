<?php
require_once('./conn.php');
require_once('./utilis.php');
session_start();

$nickname;
$username;
$comment;

if (!empty($_SESSION['nickname']) && !empty($_POST['comment']) && !empty($_SESSION['nickname']) ) {
  $nickname = $_SESSION['nickname'];
  $username = $_SESSION['username'];
  $comment = $_POST['comment'];
} else {
  header("Location: index.php?page=1&errCode=1");
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

if ($role_add_post === 0) {
  header("Location: index.php?page=1");
  die("身份錯誤");
}

$sql = "INSERT INTO John_comments (nickname, username, content) VALUE(?, ?, ?)";
// $sql = "INSERT INTO comments (nickname, username, content) VALUE(?, ?, ?)";


if ($stmt = $conn->prepare($sql)) {
  $stmt->bind_param("sss",$nickname, $username,$comment);
  if ($stmt->execute()) {
    $stmt->close();
    header("Location: ./index.php?page=1");
  } else {
    header("Location: index.php?page=1&errCode=1");
    die("Failed" . $conn->error);
  }
} else {
  die("Failed" . $conn->error);
}

?>