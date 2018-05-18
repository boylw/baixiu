<?php
   session_start();

   function get_user_info(){
	   	if (empty($_SESSION['CURRENT_USER_INFO'])){
		   	header ("Location:/admin/login.php");
		   	exit();
	   }
	   return $_SESSION['CURRENT_USER_INFO'];
   }
   

