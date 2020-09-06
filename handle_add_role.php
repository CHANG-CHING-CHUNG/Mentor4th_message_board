<?php
require_once('./conn.php');
require_once('./utilis.php');
session_start();

$currentRole;
if (!empty($_SESSION['role']) && $_SESSION['role'] == "admin") {
  $currentRole = $_SESSION['role'];
} else {
  header("Location: ./update_authority.php");
  die("身份錯誤");
}

echo $currentRole;
$role_name;
$chinese_role_name;
$add_post;
$delete_self_post;
$delete_any_post;
$edit_self_post;
$edit_any_post;
print_r($_POST);
if (!empty($_POST['role_name']) &&
    !empty($_POST['chinese_role_name']) &&
    $_POST['add_post'] != null &&
    $_POST['delete_self_post'] != null &&
    $_POST['delete_any_post'] != null &&
    $_POST['edit_self_post'] != null &&
    $_POST['edit_any_post'] != null) {
  $role_name = $_POST['role_name'];
  $chinese_role_name = $_POST['chinese_role_name'];
  $add_post = (int)$_POST['add_post'];
  $delete_self_post = (int)$_POST['delete_self_post'];
  $delete_any_post = (int)$_POST['delete_any_post'];
  $edit_self_post = (int)$_POST['edit_self_post'];
  $edit_any_post = (int)$_POST['edit_any_post'];
} else {
  header("Location: ./update_authority.php");
  die("資料不齊全");
}

$sql = "INSERT INTO John_roles (role_name, chinese_role_name, add_post, delete_self_post, delete_any_post,edit_self_post, edit_any_post) VALUES (?,?,?,?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssiiiii",$role_name,$chinese_role_name,$add_post,$delete_self_post,$delete_any_post,$edit_self_post,$edit_any_post);

if ($stmt->execute()) {
  $stmt->close();
  header("Location: ./update_authority.php");
} else {
  header("Location: ./update_authority.php");
  die("錯誤".$conn->error);
}



?>