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
    // $sql = "SELECT * from John_users WHERE username = '$username'";
    $sql = "SELECT * from users WHERE username = '$username'";
    $user_result = $conn->query($sql);
    $row = $user_result->fetch_assoc();
    return $row;
  }
?>