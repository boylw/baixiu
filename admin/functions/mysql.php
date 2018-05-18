<?php
//这里是数据库封装文件

/**
*  数据库链接封装
*/

require_once "../config.php";
function conn_db(){
	$conn = new mysqli(DB_HOST,DB_USER,DB_PSWD);
	if (!$conn){
		exit("数据库链接错误").$conn->error;
	}

	$selectDb = "use ".DB_NAME;

	if (!$conn->query($selectDb)){
		return "数据库选择错误".$conn->error;
	}

	$setName = "set names utf8";
	if (!$conn->query($setName)){
		return "字符集设置错误".$conn->error;
	}
	return $conn;
}


/*
获取全部查询结果
return 索引数组  data[0]['num']....
**/
function get_all($sql){
	//获取数据库链接对象
	$conn = conn_db();
	if (!isset($sql)){
		return null;
	}

	$result = $conn->query($sql);
	if (empty($result)){
		return null;
	}

	if (!$result){
		return "数据库查询错误".$conn->error;
	}
	while ($row = $result->fetch_assoc()) {
		$data[] = $row;
	}
	return $data;
}
/*
获取全部查询结果
return string
**/

function get_one($sql,$attr){
	return get_all($sql)[0][$attr];
}

/*
单条数据插入
return boolean
**/

function add_one($sql){
   $conn = conn_db();

   $result =$conn->query($sql);
   if (!$result){
   	  return "添加失败".$conn->error;
   }
   return $result;
}

/*
数据删除
return boolean
**/

function delete_one($sql){
    $conn = conn_db();

    $result = $conn->query($sql);
    if (!$result){
   	  return "删除失败".$conn->error;
    }
    return $result;
}

/*
文章添加
return boolean
**/

function add_posts($sql){
	$conn = conn_db();

	$result = $conn->query($sql);
	if (!$result){
   	  return "添加失败".$conn->error;
    }
    return $result;
}
