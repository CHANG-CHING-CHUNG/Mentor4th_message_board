<?php
require_once('./conn.php');
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

$sql = "UPDATE John_comments SET content = ? WHERE id = ? and username = ?";
// $sql = "UPDATE comments SET content = ? WHERE id = ? and username = ?";


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



 ?>