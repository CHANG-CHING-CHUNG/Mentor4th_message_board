<?php
require_once('./conn.php');
require_once('./Parsedown.php');
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(true);
session_start();

$nickname;
$username;
if (!empty($_SESSION['nickname']) && !empty($_SESSION['username'])) {
  $nickname = $_SESSION['nickname'];
  $username = $_SESSION['username'];
}

$sql = "SELECT nickname, content, created_at from John_comments ORDER by id desc";
// $sql = "SELECT nickname, content, created_at from comments ORDER by id desc";

$result = $conn->query($sql);

if (!$result) {
  die("Error:" . $conn->error);
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
      <?php if (empty($nickname)) { ?>
        <form class="board__login-div" action="login.php" method="POST">
          <span class="board__login-form-title">
            會員登入
          </span>
          <?php
           if (!empty($_GET['errCode'])) {
             $code = $_GET['errCode'];
             if ($code === '2') {
               echo '<span class="warning">帳號或密碼錯誤!</span>';
             }
           }
           ?>
          <div class="input-wrapper">
            <i class="fa fa-user input-icon"></i>
            <input
              type="text"
              name="username"
              placeholder="User name"
              class="input user"
            />
          </div>
          <div class="input-wrapper">
            <i class="fa fa-lock input-icon"></i>
            <input
              type="password"
              name="password"
              placeholder="Password"
              class="input password"
            />
          </div>
          <div class="from-login-btn-container">
            <button class="login-btn">
              登入
            </button>
            <a href="register.php" class="login-btn">
              註冊
            </a>
          </div>
        </form>
      <?php } else { ?>
        <form class="board__type-comment" action="add_comment.php" method="POST">
          <span class="board__type-comment-title">
            Hi~! <span class="username"><?php echo htmlspecialchars($nickname)?></span> 留下你想說的話
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
          ></textarea>
          <button class="login-btn">
            送出
          </button>
          <a href="logout.php" class="login-btn">
            登出
          </a>
        </form>
      <?php } ?>
      </section>
      <hr class="hr" />
      <section id="main" class="board__comments">
      <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="board__user-comment">
          <div class="board__user-info">
            <span class="nickname"><?php echo htmlspecialchars($row["nickname"]) ?></span>
            <span class="time"><?php echo htmlspecialchars($row["created_at"]) ?></span>
          </div>
          <div class="avatar">
            <img
              class="avatar-img"
              src=""
              alt="avatar"
            />
          </div>
          <div class="p">
          <?php echo  $Parsedown->text($row["content"]) ?>
          </div>
        </div>
      <?php }?>
      </section>
    </main>
    <script src="app.js"></script>
  </body>
</html>
