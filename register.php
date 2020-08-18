<?php
require_once('./conn.php');
session_start();
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
    <header>
      <h3 class="warning">練習用，請勿使用真實的帳號密碼!</h3>
      <h3 class="light">本留言板支援 Markdown 格式</h3>
    </header>
    <main class="board">
     <section class="board__login">
      <form class="board__login-div" action="handle_register.php" method="POST">
        <span class="board__login-form-title">
          會員註冊
        </span>
        <?php
           if (!empty($_GET['errCode'])) {
             $code = $_GET['errCode'];
             if ($code === '1') {
               echo '<span class="warning">資料不齊全!</span>';
             } else if ($code === '2') {
               echo '<span class="warning">帳號已被註冊!</span>';
             } else if ($code === '3') {
               echo '<span class="warning">請檢查資料!</span>';
             }
           }
           ?>
        <div class="input-wrapper">
          <i class="fa fa-user input-icon"></i>
          <input type="text" name="nickname" placeholder="Nickname" class="input nick">
        </div>
        <div class="input-wrapper">
          <i class="fa fa-user input-icon"></i>
          <input type="text" name="username" placeholder="User name" class="input user">
        </div>
        <div class="input-wrapper">
          <i class="fa fa-lock input-icon"></i>
          <input type="password" name="password" placeholder="Password" class="input password">
        </div>
        <div class="from-login-btn-container">
          <button class="login-btn">
            送出
          </button>
          <a class="login-btn" href="./index.php?page=1">
            回到首頁
          </a>
        </div>
        </form>
      </section>
    </main>
    <script src="app.js"></script>
  </body>
</html>
