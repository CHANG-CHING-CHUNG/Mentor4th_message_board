<?php
require_once('./conn.php');
session_start();

$nickname;
$username;
$password;
if (!empty($_POST['nickname']) && !empty($_POST['username'] && !empty($_POST['password']))) {
  $nickname = $_POST['nickname'];
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

} else {
  header("Location: register.php?errCode=3");
  die("請檢查資料");
}

$sql = "INSERT INTO John_users (nickname, username, password) VALUE(?, ?, ?)";
// $sql = "INSERT INTO users (nickname, username, password) VALUE(?, ?, ?)";


if ($stmt = $conn->prepare($sql)) {
  $stmt->bind_param("sss",$nickname,$username,$password);
  $stmt->execute();

  if ($stmt->errno) {
    $code = $stmt->errno;
    if ($code === 1062) {
      header("Location: register.php?errCode=2");
      die("Failed" . $stmt->error);
    }
  }
  $stmt->close();
  header("Location: ./index.php?page=1");
} else {
  header("Location: register.php?errCode=3");
  die("請檢查資料");
}

?>