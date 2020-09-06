<?php
require_once('./conn.php');
session_start();

$username;
$password;
if (!empty($_POST['username']) || !empty($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
} else {
  header("Location: index.php?page=1&errCode=1");
  die("資料不齊全");
}

// $sql = "SELECT id,nickname,username,password,role from users WHERE username = ?";
$sql = "SELECT id,nickname,username,password,role from John_users WHERE username = ?";

if ($stmt = $conn->prepare($sql)) {
  $stmt->bind_param("s",$username);
  $stmt->execute();
  $stmt->bind_result($id,$nickname,$name,$pass,$role);

  if ($stmt->fetch()) {
    if (password_verify($password, $pass)) {
      $_SESSION['nickname'] = $nickname;
      $_SESSION['username'] = $name;
      $_SESSION['role'] = $role;
      $stmt->close();
      header("Location: ./index.php?page=1");
    } else {
      header("Location: ./index.php?page=1&errCode=2");
      die($conn->error);
    }
  } else {
    header("Location: ./index.php?page=1&errCode=2");
    die($conn->error);
  }
} else {
  header("Location: ./index.php?page=1&errCode=2");
    die($conn->error);
}

?>