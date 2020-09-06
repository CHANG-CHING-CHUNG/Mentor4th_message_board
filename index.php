<?php
if (empty($_GET['page'])) {
  header('Location: ./index.php?page=1');
}
require_once('./conn.php');
require_once('./Parsedown.php');
require_once('./utilis.php');
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(true);
session_start();

$nickname;
$username;
$role;
if (!empty($_SESSION['nickname']) && !empty($_SESSION['username']) && !empty($_SESSION['role'])) {
  $nickname = $_SESSION['nickname'];
  $username = $_SESSION['username'];
  $role = $_SESSION['role'];
}



// $sql = "SELECT C.id as id, U.nickname as nickname, C.username as username, C.content as content, C.created_at as created_at, U.role as role from comments as C  left join users as U on C.username = U.username WHERE C.is_deleted IS null ORDER by C.id desc";
$sql = "SELECT C.id as id, U.nickname as nickname, C.username as username, C.content as content, C.created_at as created_at, U.role as role from John_comments as C  left join John_users as U on C.username = U.username WHERE C.is_deleted IS null ORDER by C.id desc";

$result = $conn->query($sql);

if (!$result) {
  die("Error:" . $conn->error);
}

$role_id =  0;
$role_name = 0;
$role_add_post = 0;
$role_delete_self_post =  0;
$role_delete_any_post = 0;
$role_edit_self_post = 0;
$role_edit_any_post = 0;
$result_row = getUserRole($username);

if (!empty($result_row)) {
  $role_id =  $result_row['id'];
  $role_name = $result_row['role_name'];
  $role_chinese_name = $result_row['chinese_role_name'];
  $role_add_post = $result_row['add_post'];
  $role_delete_self_post =  $result_row['delete_self_post'];
  $role_delete_any_post = $result_row['delete_any_post'];
  $role_edit_self_post = $result_row['edit_self_post'];
  $role_edit_any_post = $result_row['edit_any_post'];
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
        <a class="change-btn wd-150 update-nickname">更改暱稱</a>
        <form class="update__nickname-form hide" method="POST" action="handle_update_nickname.php">
          <input class="update__nickname-input" type="text" name="nickname"/>
          <button class="update-btn">送出</button>
        </form>
        <form class="board__type-comment" action="add_comment.php" method="POST">
          <span class="board__type-comment-title">
            Hi~! <span class="nickname"><?php echo htmlspecialchars($nickname)?></span>
            <span class="user_role"><?php echo htmlspecialchars("#".$role_chinese_name)?></span>
            <div class="text">留下你想說的話</div>
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
          <?php if ($result_row['add_post'] !== 0 ) {?>
          <button class="login-btn">
            送出
          </button>
          <?php }?>
          <a href="logout.php" class="logout-btn">
            登出
          </a>
          <?php if ( !empty($role) && $role == "admin") { ?>
              <a href="admin.php?page=1" class="login-btn">
                管理員頁面
              </a>
            <?php }?>
        </form>
      <?php } ?>
      </section>
      <hr class="hr" />
      <section id="main" class="board__comments">
      <?php 
          $allRows = $result->fetch_all(MYSQLI_ASSOC);
          $length = ceil(count($allRows) / 20);
          if ($length == 0) {
            $length = 1;
          }
          $base = 19;
          $start = 0;
          $offset = $base;
          
          if (!empty($_GET['page'])) {
            if ( ((int)$_GET['page'] < 1) || ((int)$_GET['page'] > $length)) {
              header('Location: ./index.php?page=1');
            } else {
              $start = ($_GET['page'] * $base) - 19;
              $offset = ($_GET['page'] * $base);
            }
          }
          if ((int)$_GET['page'] > 1) {
            $start += 1;
          }
          for ($i=$start; $i <= $offset; $i+=1) {
            if ($i === count($allRows)) {
            break;
            }
              echo '<div class="board__user-comment">';
              echo  '<div class="board__user-info">';
              echo    '<span class="nickname">'  . htmlspecialchars($allRows[$i]["nickname"]). '</span>';
              echo    '<span class="username">' .'@('.htmlspecialchars($allRows[$i]["username"]).')' .'</span>';
              if ($allRows[$i]["role"] === "admin"){
                echo    '<span class="role">#管理員</span>';
              }
              echo    '<span class="time">' . htmlspecialchars($allRows[$i]["created_at"]) . '</span>';
              echo  '</div>';
              echo  '<div class="avatar">';
              echo    '<img class="avatar-img" src="" alt="avatar"/>';
              echo  '</div>';
              echo  '<div class="p">';
              echo    $Parsedown->text($allRows[$i]["content"]);
              echo  '</div>';
              if ($allRows[$i]["username"] === $username ||  $role_edit_any_post === 1 || $role_delete_any_post === 1) {
                echo '<div class="edit_btn-section">';
                if (($role_edit_self_post === 1 && $allRows[$i]["username"] === $username ) || $role_edit_any_post === 1) {
                  echo  '<a href=update_comment.php?id='. htmlspecialchars($allRows[$i]["id"]).' ' . 'class="update_comment-btn"><i class="fas fa-edit"></i></a>';
                }
                if (($role_delete_self_post === 1 && $allRows[$i]["username"] === $username ) || $role_delete_any_post === 1) {
                  echo  '<a href=handle_delete_comment.php?id='. htmlspecialchars($allRows[$i]["id"]).' ' . 'class="update_comment-btn"><i class="fas fa-trash-alt"></i></i></a>';
                }
                echo '</div>';
                
              }
              echo '</div>';
          }
      ?>
      </section>
      <div class="pages">
        <div class="prev">上一頁</div>
        <?php for($i=0; $i<=$length -1; $i+=1) { ?>
          <div class="page" data-page=<?php echo $i+1?>><?php echo $i + 1;?></div>
        <?php } ?>
        <div class="next">下一頁</div>
      </div>
    </main>
    <script src="app.js"></script>
  </body>
</html>
