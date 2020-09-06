<?php

require_once('./conn.php');
require_once('./utilis.php');
session_start();

$username;
if (!empty($_SESSION['username'])) {
  $username = $_SESSION['username'];
} else {
  header("Location: index.php?page=1");
  die("資料不齊全");
}

// $sql = "SELECT username,role from users WHERE username = ?";
$sql = "SELECT username,role from John_users WHERE username = ?";

if ($stmt = $conn->prepare($sql)) {
  $stmt->bind_param("s",$username);
  $stmt->execute();
  $stmt->bind_result($user,$role);
  if ($stmt->fetch()) {
    if ($role != "admin") {
      header("Location: ./index.php?page=1");
      die("身份驗證錯誤");
    } else {
      $stmt->free_result();
    }
  }
} else {
  header("Location: ./index.php?page=1");
    die($conn->error);
}

// $sql = "SELECT * from roles";
$sql = "SELECT * from John_roles";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$num_of_rows = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&family=Poppins:wght@500&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" />
    <title>留言板</title>
  </head>
  <body>
    <header >
      <h3 class="warning">練習用，請勿使用真實的帳號密碼!</h3>
      <h3 class="light">本留言板支援 Markdown 格式</h3>
    </header>
    <main class="board">
      <section class="update_role">
            <h1 class="update_role_text">編輯身份權限</h1>
            <div class="from-login-btn-container">
              <a class="admin-btn2" href="./admin.php?page=1">
                回到管理員頁面
              </a>
              <a class="admin-btn2" href="./index.php?page=1">
               回到首頁
              </a>
            </div>
            <div class="table-box">
            <table class="users">
            <?php 
            $roles_arr = array();
            while ($row = $result->fetch_assoc()) {
              array_push($roles_arr,$row['id'],$row['chinese_role_name']);
            ?>
              <tr>
                <th>ID</th>
                <th>身份別</th>
                <th>新增貼文</th>
                <th>刪除自己貼文</th>
                <th>刪除任意貼文</th>
                <th>編輯自己貼文</th>
                <th>編輯任意貼文</th>
              </tr>
              <tr>
                <?php
                $role_name = $row['chinese_role_name'];
                $add;
                $delete_self;
                $delete_any;
                $edit_self;
                $edit_any;
                if ($row['add_post'] == 1) {
                  $add = "是";
                } else {
                  $add = "否";
                }
                if ($row['delete_self_post'] == 1) {
                  $delete_self = "是";
                } else {
                  $$delete_self = "否";
                }
                if ($row['delete_any_post'] == 1) {
                  $delete_any = "是";
                } else {
                  $delete_any = "否";
                }
                if ($row['edit_self_post'] == 1) {
                  $edit_self = "是";
                } else {
                  $edit_self = "否";
                }
                if ($row['edit_any_post'] == 1) {
                  $edit_any = "是";
                } else {
                  $edit_any = "否";
                }



                ?>
                <td><?php echo $row['id']?></td>
                <td><?php echo $role_name?></td>
                <td><?php echo $add?></td>
                <td><?php echo $delete_self?></td>
                <td><?php echo $delete_any?></td>
                <td><?php echo $edit_self?></td>
                <td><?php echo $edit_any?></td>
            
              </tr>
            <?php }?>
            </table>
            <div>
            <table class="authority">
              <tr>
                <th>身份別</th>
                <th>新增貼文</th>
                <th>刪除自己貼文</th>
                <th>刪除任意貼文</th>
                <th>編輯自己貼文</th>
                <th>編輯任意貼文</th>
              </tr>

              <form id="update_form" action="handle_update_authority.php" method="POST"></form>
              <h1 class="update_role_text2">編輯權限</h1>
              <tr>
                  <td>
                    <select name="id" class="select-container" form="update_form">
                     <?php for($i = 0; $i < count($roles_arr); $i += 2) {;?>
                      <option value=<?php echo $roles_arr[$i]?>><?php echo $roles_arr[$i+1]?></option>
                      <!-- <option value="2">一般使用者</option>
                      <option value="3">遭停權使用者</option> -->
                     <?php }?>
                    </select>
                  </td>
                  <td>
                    <select name="add_post" class="select-container" form="update_form">
                      <option value="1">是</option>
                      <option value="0">否</option>
                    </select>
                  </td>
                  <td>
                    <select name="delete_self_post" class="select-container" form="update_form">
                      <option value="1">是</option>
                      <option value="0">否</option>
                    </select>
                  </td>
                  <td>
                    <select name="delete_any_post" class="select-container" form="update_form">
                      <option value="1">是</option>
                      <option value="0">否</option>
                    </select>
                  </td>
                  <td>
                    <select name="edit_self_post" class="select-container" form="update_form">
                      <option value="1">是</option>
                      <option value="0">否</option>
                    </select>
                  </td>
                  <td>
                  <select name="edit_any_post" class="select-container" form="update_form">
                    <option value="1">是</option>
                    <option value="0">否</option>
                  </select>
                  <button class="admin-btn" form="update_form">送出</button>
                  </td>
              </tr>
            </table> 

            <table class="authority">
              <tr>
                <th>身份別(英文)</th>
                <th>身份別(中文)</th>
                <th>新增貼文</th>
                <th>刪除自己貼文</th>
                <th>刪除任意貼文</th>
                <th>編輯自己貼文</th>
                <th>編輯任意貼文</th>
              </tr>

              <form id="add_role_form" action="handle_add_role.php" method="POST"></form>
              <h1 class="update_role_text2">新增身份</h1>
              <tr>
                  <td>
                    <input required class="input-role" name="role_name" type="text" form="add_role_form">
                  </td>
                  <td>
                    <input required class="input-role" name="chinese_role_name" type="text" form="add_role_form">
                  </td>
                  <td>
                    <select name="add_post" class="select-container" form="add_role_form">
                      <option value="1">是</option>
                      <option value="0">否</option>
                    </select>
                  </td>
                  <td>
                    <select name="delete_self_post" class="select-container" form="add_role_form">
                      <option value="1">是</option>
                      <option value="0">否</option>
                    </select>
                  </td>
                  <td>
                    <select name="delete_any_post" class="select-container" form="add_role_form">
                      <option value="1">是</option>
                      <option value="0">否</option>
                    </select>
                  </td>
                  <td>
                    <select name="edit_self_post" class="select-container" form="add_role_form">
                      <option value="1">是</option>
                      <option value="0">否</option>
                    </select>
                  </td>
                  <td>
                  <select name="edit_any_post" class="select-container" form="add_role_form">
                    <option value="1">是</option>
                    <option value="0">否</option>
                  </select>
                  <button class="admin-btn add_role_btn" form="add_role_form">送出</button>
                  </td>
              </tr>
            </table> 
            </div>
        </div>
      </section>
      <hr class="hr" />
    </main>
    <script src="checkspace.js"></script>
  </body>
</html>
