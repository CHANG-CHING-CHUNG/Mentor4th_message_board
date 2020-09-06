<?php

require_once('./conn.php');
require_once('./utilis.php');
session_start();

$nickname;
$username;
$id;
if (!empty($_SESSION['nickname']) && !empty($_SESSION['username']) && !empty($_GET['id'])) {
  $nickname = $_SESSION['nickname'];
  $username = $_SESSION['username'];
  $id = (int)$_GET['id'];
}

$result_row = getUserRole($username);

$role_id =  $result_row['id'];
$role_name = $result_row['role_name'];
$role_add_post = $result_row['add_post'];
$role_delete_self_post =  $result_row['delete_self_post'];
$role_delete_any_post = $result_row['delete_any_post'];
$role_edit_self_post = $result_row['edit_self_post'];
$role_edit_any_post = $result_row['edit_any_post'];


if ($role_edit_any_post === 1) {
  // $sql = "SELECT content from comments where id = ?";
  $sql = "SELECT content from John_comments where id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $id);
  $result = $stmt->execute();

  if (!$result) {
    die("Error:" . $conn->error);
  }
  $result = $stmt->bind_result($content);
  $stmt->fetch();
} else if ($role_edit_self_post === 1) {
  $sql = "SELECT content from comments where id = ? and username = ?";
  
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("is", $id, $username);
  $result = $stmt->execute();
  
  if (!$result) {
    die("Error:" . $conn->error);
  }
  $result = $stmt->bind_result($content);
  $stmt->fetch();
} else {
  header("Location: ./index.php?page=1");
  die("身份錯誤");
}

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
      <section class="board__login">
        <form class="board__type-comment" action="handle_update_comment.php" method="POST">
          <span class="board__type-comment-title">
            <div class="text">編輯你的留言</div>
          </span>
          <?php
           if (!empty($_GET['errCode'])) {
             $code = $_GET['errCode'];
             if ($code === '1') {
               echo '<span class="warning">內容不得為空!</span>';
             }
           }
           ?>
          <textarea
            name="comment"
            class="type-comment"
            cols="30"
            rows="10"
          ><?php echo $content?></textarea>
          <input type="hidden" name="id" value=<?php echo $id?>>
          <button class="login-btn">
            送出
          </button>
          <a class="login-btn" href="./index.php?page=1">
            回到首頁
          </a>
        </form>
      </section>
      <hr class="hr" />
    </main>
    <script src="app.js"></script>
  </body>
</html>
