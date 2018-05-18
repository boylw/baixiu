<?php

/**
* 根据用户邮箱，获取用户头像
* 输入是用户的邮箱，输出是用户的头像
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		//=======获取客户输入，即传过来的邮箱
	header ("Content-type:text/html;charset=utf-8");

	$email = $_POST['email'];

	//======数据库链接操作
	include "../../config.php";

	$conn = new mysqli(DB_HOST,DB_USER,DB_PSWD);

	if (!$conn){
		echo "数据库链接错误".$conn->error;
		return;
	}

	$selectDb = 'use '.DB_NAME;
	if (!$conn->query($selectDb)){
		echo "数据库选择错误".$conn->error;
		return;
	}

	$setName = "set names utf8";
	if (!$conn->query($setName)){
		echo "数据库字符选择错误".$conn->error;
	}

	$sql = "select avatar from users where email = '$email';";

	if (!$conn->query($sql)){
		echo "数据查询错误".$conn->error;
		return;
	}

	$result = $conn->query($sql);
	if ($result->num_rows>0){
		while($col = $result->fetch_assoc()){
			foreach ($col as $key => $value) {
				echo $value;
			}
		}
	}
}

