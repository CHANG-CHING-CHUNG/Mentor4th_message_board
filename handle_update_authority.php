<?php
require_once('./conn.php');
require_once('./utilis.php');
session_start();

$role;
if (!empty($_SESSION['role'])) {
  echo $_SESSION['role'];
  $role = $_SESSION['role'];
  if ($role !== "admin") {
    header("Location: ./index.php?page=1");
    die("身份錯誤");
  }
} else {
  header("Location: ./index.php?page=1");
  die("Error");
}

$id;
$add;
$delete_self;
$delete_any;
$edit_self;
$edit_any;

if ($_POST['id'] != null &&
    $_POST['add_post'] != null &&
    $_POST['delete_self_post'] != null &&
    $_POST['delete_any_post'] != null &&
    $_POST['edit_self_post'] != null &&
    $_POST['edit_any_post'] != null) {
      $id = (int)$_POST['id'];
      $add = (int)$_POST['add_post'];
      $delete_self = (int)$_POST['delete_self_post'];
      $delete_any = (int)$_POST['delete_any_post'];
      $edit_self = (int)$_POST['edit_self_post'];
      $edit_any = (int)$_POST['edit_any_post'];
} else {
  header("Location: ./update_authority.php");
  die("資料不齊全");
}


// $sql = "UPDATE roles SET add_post = ?, delete_self_post = ?, delete_any_post = ?, edit_self_post = ?, edit_any_post = ? WHERE id = ?";
$sql = "UPDATE John_roles SET add_post = ?, delete_self_post = ?, delete_any_post = ?, edit_self_post = ?, edit_any_post = ? WHERE id = ?";


if ($stmt = $conn->prepare($sql)) {
  $stmt->bind_param("iiiiii",$add,$delete_self,$delete_any,$edit_self,$edit_any,$id);

  if ($stmt->execute()) {
    echo "成功";
    $stmt->close();
    header("Location: ./update_authority.php");
  } else {
    header("Location: ./update_authority.php");
    die("錯誤".$conn->error);
  }
} else {
  header("Location: ./update_authority.php");
  die("錯誤".$conn->error);
}



?>