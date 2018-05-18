<?php
   //-----0. 检查函数
function check(){

   session_start();
   include '../config.php';
   //-----数据库操作

   $conn = new mysqli(DB_HOST,DB_USER,DB_PSWD);
      if ($conn->connect_error){
      // die("数据库访问失败:".$conn->connect_error);
      echo $_GLOBALS['message'] = "数据库链接失败";
      return;
      }
      $selectDb = 'use baixiu';
      // echo $selectDb;
      if ($conn->query($selectDb) === false){
         // die("数据库选择失败").$conn->error_log();
       echo $conn->error;
       echo   $_GLOBALS['message'] = "数据库出现位置错误";
         return;
      }
      $setName = "set names utf8";
      if ($conn->query($setName) === false){
        echo  $_GLOBALS['message'] = "数据库字符设置错误";
         return;
      }

      //-----------客户端操作
     // echo 1;
     if (empty($_POST['email'])){
         $GLOBALS['message'] = "您的用户名为空";
         return;
     }

     if (empty($_POST['password'])){
         $GLOBALS['message'] = "您的密码为空";
         return;
     }

      //-----获取邮箱和密码
     $email = trim($_POST['email']);
     $password = trim($_POST['password']);


      $sql = "select * from users where email='{$email}'";
      if ($conn->query($sql) === false){
         echo $conn->error;
         $GLOBALS['message'] = "数据库查詢錯誤";
         return;
      }
      $result = $conn->query($sql);
      if (empty($result)){
         $GLOBALS['message'] = "用户不存在";
         return;
      }


      // var_dump($result);
      $result = $result->fetch_assoc();
      // var_dump($result);

      if ($result['email'] !== $email){
        $GLOBALS['message'] = "用户名错误";
         return;
      }
      // $result['password'] = md5($result['password']);
      if ($result['password'] !== md5($password)){
         $GLOBALS['message'] = "密码错误";
         return;
      }

      $_SESSION['CURRENT_USER_INFO'] = $result;
      header('Location:/admin/index.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  //----1. 校验
  check();
  // echo $GLOBALS['message'];
  //----2. 持久化
}

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="stylesheet" href="/static/assets/vendors/animate/animate.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap <?php echo isset($message) ? "shake animated" : "";?>" autocomplete="off" novalidate method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
      <img class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if(isset($GLOBALS['message'])):?>
        <div class="alert alert-danger">
          <strong>错误！</strong> <?php echo $GLOBALS['message'];?>
        </div>
      <?php endif;?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" type="email" class="form-control" name="email" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" type="password" class="form-control" name="password" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block">登 录</button>
    </form>
  </div>
</body>

<script src="/static/assets/vendors/jquery/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
     $(function($){
        $('#email').blur(function(){
          var val = $('#email').val()
          
          if (val == '') return;
            $.post('/admin/api/avatar.php',{email : val},function(data){
               if (data!=''){
                  $('.avatar').fadeOut(function(){
                    $(this).on("load",function(){
                      $(this).fadeIn();
                    }).attr('src',data);
                  })
               }
            })
        })
        
     })
</script>
</html>
