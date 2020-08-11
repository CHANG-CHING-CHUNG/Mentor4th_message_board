<?php
require_once('./conn.php');
session_start();

$nickname;
$comment;
if (!empty($_SESSION['nickname']) && !empty($_POST['comment'])) {
  $nickname = $_SESSION['nickname'];
  $comment = $_POST['comment'];
} else {
  header("Location: index.php?errCode=1");
  die("請檢查資料");
}

$sql = "INSERT INTO John_comments (nickname, content) VALUE(?, ?)";
// $sql = "INSERT INTO comments (nickname, content) VALUE(?, ?)";


if ($stmt = $conn->prepare($sql)) {
  $stmt->bind_param("ss",$nickname,$comment);
  $stmt->execute();
  $stmt->close();
  header("Location: ./index.php");
} else {
  die("Failed" . $conn->error);
}

?>