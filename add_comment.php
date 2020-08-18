<?php
require_once('./conn.php');
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