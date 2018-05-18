<?php
// echo DB_USER;
   $sql = "select * from users where nickname='管理员' limit 0,1;";

   if ($conn->query($sql) === false){
     echo  $conn->error;
      $GLOBALS['message'] = "数据库查询错误";
   }