
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Document</title>
</head>
<body>
   <?php
class Mysql{

   function __construct($local,$uer,$pswd){
      $this->local = $local;
      $this->uer = $uer;
      $this->pswd = $pswd;
      // $this->name = $name;
   }
   function conn(){
      $conn = new mysqli($this->local,$this->uer,$this->pswd);
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
      $setName = "set names gbk";
      if ($conn->query($setName) === false){
        echo  $_GLOBALS['message'] = "数据库字符设置错误";
         return;
      }
      return $conn;
   }

   function show (){
      echo $this->local;
   }

}
?>
</body>
</html>