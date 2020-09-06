<?php
require_once('./conn.php');
require_once('./utilis.php');
session_start();

$role;
$current_user;
if (!empty($_SESSION['role']) && !empty($_SESSION['username'])) {
  $role = $_SESSION['role'];
  $current_user = $_SESSION['username'];
  if ($role !== "admin") {
    header("Location: ./index.php?page=1");
    die("身份錯誤");
  }
} else {
  header("Location: ./index.php?page=1");
  die("Error");
}

$update_role;
$username;

if (!empty($_POST['username']) && !empty($_POST['role'])) {
  $update_role = $_POST['role'];
  $username = $_POST['username'];
}

if ($username === $current_user) {
  header("Location: ./admin.php?page=1");
  die("不能改自己");
}

// $sql = "UPDATE users SET role = ? WHERE username = ?";
$sql = "UPDATE John_users SET role = ? WHERE username = ?";


if ($stmt = $conn->prepare($sql)) {
  $stmt->bind_param("ss",$update_role,$username);

  if ($stmt->execute()) {
    $stmt->close();
    header("Location: ./admin.php?page=1");
  } else {
    header("Location: ./admin.php?page=1");
    die("錯誤");
  }
} else {
  header("Location: ./admin.php?page=1");
  die("錯誤");
}


?>