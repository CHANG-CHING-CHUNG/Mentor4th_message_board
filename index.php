<?php
if (empty($_GET['page'])) {
  header('Location: ./index.php?page=1');
}
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
      <?php 
          $allRows = $result->fetch_all(MYSQLI_ASSOC);
          $length = ceil(count($allRows) / 20);
          $base = 19;
          $start = 0;
          $offset = $base;
          if (!empty($_GET['page'])) {
            if ( ((int)$_GET['page'] < 0) || ((int)$_GET['page'] > $length + 1)) {
              header('Location: ./index.php?page=1');
            } else {
              $start = ($_GET['page'] * $base) - 19;
              $offset = ($_GET['page'] * $base);
              
            }
          }
          $counter = 0;
          for ($i=$start; $i <= $offset; $i+=1) {
            if ($i === count($allRows)) {
            break;
            }
              echo '<div class="board__user-comment">';
              echo  '<div class="board__user-info">';
              echo    '<span class="nickname">'  . htmlspecialchars($allRows[$i]["nickname"]) . '</span>';
              echo    '<span class="time">' . htmlspecialchars($allRows[$i]["created_at"]) . '</span>';
              echo  '</div>';
              echo  '<div class="avatar">';
              echo    '<img class="avatar-img" src="" alt="avatar"/>';
              echo  '</div>';
              echo  '<div class="p">';
              echo    $Parsedown->text($allRows[$i]["content"]);
              echo  '</div>';
              echo '</div>';
          }
      ?>
      </section>
      <div class="pages">
        <div class="prev">上一頁</div>
        <?php for($i=0; $i<=$length; $i+=1) { ?>
          <div class="page" data-page=<?php echo $i+1?>><?php echo $i + 1;?></div>
        <?php } ?>
        <div class="next">下一頁</div>
      </div>
    </main>
    <script src="app.js"></script>
  </body>
</html>
