<?php
  require_once('./conn.php');
  function generateToken() {

    $s = '';
    for($i = 1; $i <= 16; $i++) {
      $s .= chr(rand(65, 90));
    }
    return $s;
  }

  function getUserFromUsername($username) {
    global $conn;
    $sql = "SELECT * from John_users WHERE username = '$username'";
    // $sql = "SELECT * from users WHERE username = '$username'";
    $user_result = $conn->query($sql);
    $row = $user_result->fetch_assoc();
    return $row;
  }

  function getUserRole($username) {
    global $conn;
    // $getRole = "SELECT * FROM roles WHERE role_name = (SELECT role FROM users WHERE username = ?)";
    $getRole = "SELECT * FROM John_roles WHERE role_name = (SELECT role FROM John_users WHERE username = ?)";
    $stmt = $conn->prepare($getRole);
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $role_result = $stmt->get_result();
    $result_row = $role_result->fetch_assoc();
    return $result_row;
  }
  function getAllRole() {
    global $conn;
    // $getRole = "SELECT * FROM roles WHERE role_name = (SELECT role FROM users WHERE username = ?)";
    $getRole = "SELECT * FROM John_roles";
    $stmt = $conn->prepare($getRole);
    $stmt->execute();
    $role_result = $stmt->get_result();
    return $role_result;
  }
?>