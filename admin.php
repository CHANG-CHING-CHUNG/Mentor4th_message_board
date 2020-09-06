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

$all_role = getAllRole();
$all_role = $all_role->fetch_all(MYSQLI_ASSOC);
$all_role_arr = array();
foreach($all_role as $role) {
  array_push($all_role_arr,$role);
}

$page = 1;
if (!empty($_GET['page'])) {
  $page = (int)$_GET['page'];
} else {
  header("Location: ./admin.php?page=1");
}
$items_per_page = 10;
$offset = ($page-1) * $items_per_page;

// $sql = "SELECT id, nickname, username, created_at, role FROM users ORDER BY id ASC LIMIT 10 OFFSET ?";
$sql = "SELECT U.id as id, U.nickname as nickname, U.username as username, U.created_at as created_at, U.role as role,R.chinese_role_name as chinese_role_name FROM John_users as U left join John_roles as R ON U.role = R.role_name ORDER BY U.id ASC LIMIT 10 OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $offset);
if (!$stmt->execute()) {
  die('Error'. $conn->error);
}
$stmt->bind_result($id,$nickname,$username,$createdAt,$role, $chinese_role_name);
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
     <section class="board__admin">
        <span class="board__login-form-title">
          管理員頁面
        </span>
        <div class="from-login-btn-container">
          <a class="admin-btn2" href="./index.php?page=1">
            回到首頁
          </a>
          <a class="admin-btn2" href="./update_authority.php">
            編輯權限
          </a>
        </div>
        <div class="table-box">
          <table class="users">
          <?php while ($stmt->fetch()) {?>
            <tr>
              <th>ID</th>
              <th>暱稱</th>
              <th>帳號</th>
              <th>註冊時間</th>
              <th>身份權限</th>
            </tr>
            <tr>
              <td><?php echo htmlspecialchars($id);?></td>
              <td><?php echo htmlspecialchars($nickname);?></td>
              <td><?php echo htmlspecialchars($username);?></td>
              <td><?php echo htmlspecialchars($createdAt);?></td>
              <td class="flex">
                  <div class="current-role">
                  當前身份: <?php echo $chinese_role_name?>
                  </div>
                  <div class="btn-box">
                  <form action="handle_update_role.php" method="POST">
                  <select name="role" class="select-container">
                  <?php for($i = 0; $i <count($all_role_arr); $i+=1) {?>
                    <option value=<?php echo $all_role_arr[$i]['role_name']?>><?php echo $all_role_arr[$i]['chinese_role_name']?></option>
                  <?php }?>
                  </select>
                    <input type="hidden" name="username" value=<?php echo htmlspecialchars($username);?>>
                    <button class="admin-btn">送出</button>
                  </form>
                  </div>
              </td>
            </tr>
          <?php }?>
          </table>
        </div>
      </section>
      <hr class="hr" />
      <div class="pagination-box">
        <?php
          // $sql = "SELECT count(id) as count FROM users";
          $sql = "SELECT count(id) as count FROM John_users";
          $stmt = $conn->prepare($sql);
          if(!$stmt->execute()) {
            die('Error'. $conn->error);
          }
          $stmt->bind_result($count);
          $stmt->fetch();
          $total_page = ceil($count / $items_per_page);
          if ( ((int)$_GET['page'] < 1) || ((int)$_GET['page'] > $total_page)) {
            header("Location: ./admin.php?page=1");
          } 
          ?>
        <div class="total-data">總共有 <?php echo $count?> 筆資料</div>
        <div class="pages">
        <div class="prev">上一頁</div>
        <?php for($i=1; $i <= $total_page; $i+=1) { ?>
          <div class="page" data-page=<?php echo $i?>><?php echo $i;?></div>
        <?php } ?>
        <div class="next">下一頁</div>
      </div>
      </div>
    </main>
    <script src="paginator.js"></script>
  </body>
</html>
