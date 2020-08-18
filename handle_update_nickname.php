<?php
require_once('./conn.php');
session_start();

$nickname;
$username;
if (!empty($_POST['nickname']) && !empty($_SESSION['username'])) {
  $nickname = $_POST['nickname'];
  $username = $_SESSION['username'];

} else {
  header("Location: index.php?page=1&errCode=1");
  die("請檢查資料");
}

$sql = "UPDATE John_users SET nickname = ? WHERE username = ?";
// $sql = "UPDATE users SET nickname = ? WHERE username = ?";


if ($stmt = $conn->prepare($sql)) {
  $stmt->bind_param("ss",$nickname,$username);

  if ($stmt->execute()) {
    $_SESSION['nickname'] = $nickname;
    $stmt->close();
    header("Location: ./index.php?page=1");
  } else {
    header("Location: ./index.php?page=1&errCode=3");
    die("請檢查資料");
  }
} else {
  header("Location: ./index.php?page=1&errCode=3");
  die("請檢查資料");
}



?>