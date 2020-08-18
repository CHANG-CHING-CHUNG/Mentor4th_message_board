<?php
require_once('./conn.php');
session_start();

$id;
$username = $_SESSION['username'];

if (!empty($_GET['id'])) {
  $id = (int)$_GET['id'];
} else {
  header("Location: ./index.php?page=1&errCode=1");
  die("請檢查資料");
}

$sql = "UPDATE John_comments SET is_deleted = 1 WHERE id = ? and username = ?";
// $sql = "UPDATE comments SET is_deleted = 1 WHERE id = ? and username = ?";


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



?>