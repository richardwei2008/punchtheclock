<?php

//需要执行的SQL语句
//单条
$openId = "openid1";
$openId = $_GET['openId'];
$sql="SELECT id, openId, nickname FROM user_info LIMIT 6";
$sql_me="SELECT info.id, info.openId, nickname, info.headimgurl, info.score FROM user_info info where info.openId = '$openId'";
// $sql_rank="SELECT rank, id, openid, nickname, headimgurl, score FROM (SELECT (@rowNum:=@rowNum+1) rank, id, openid, nickname, headimgurl, score FROM user_info,(SELECT (@rowNum :=0) ) b order by score desc)t WHERE t.openid='oxqECj7JTrpVG7BfJnNCUpQap0Xc'";
$sql_rank="SELECT rank, id, openid, nickname, headimgurl, score FROM (SELECT (@rowNum:=@rowNum+1) rank, id, openid, nickname, headimgurl, score FROM user_info,(SELECT (@rowNum :=0) ) b order by score desc)t WHERE t.openid='$openId'";
$sql_global="SELECT info.id, info.openId, nickname, info.headimgurl, info.score FROM user_info info ORDER BY info.score desc LIMIT 6";
$sql_total="SELECT count(info.id) as value FROM user_info info ";
//多条数据
//$sql="select id,name from tbl_user";

//调用conn.php文件进行数据库操作 
require('conn.php'); 



//执行SQL语句(查询) 
$result_me = mysql_query($sql_rank) or die('数据库查询失败！</br>错误原因：'.mysql_error()); 
$result_global = mysql_query($sql_global) or die('数据库查询失败！</br>错误原因：'.mysql_error()); 
$result_total= mysql_query($sql_total) or die('数据库查询失败！</br>错误原因：'.mysql_error()); 
//提示操作成功信息，注意：$result存在于conn.php文件中，被调用出来 
if($result_global) 
{ 
//	$array=mysql_fetch_array($result,MYSQL_ASSOC);
	/*数据集*/
	$me = mysql_fetch_object($result_me);
	$totalObj = mysql_fetch_object($result_total);
	// echo json_encode($me);
	
	$users=array();
	$i=0;	
	while($row=mysql_fetch_array($result_global, MYSQL_ASSOC)){
		// echo $row['openId'].'-----------'.$row['nickname'].'</br>';
		$users[$i]=$row;
		$i++;
	}
	echo json_encode(array('dataList'=>$users, 'me'=>$me, 'total'=>$totalObj, 'openId'=>$openId)); //
	/*单条数据*/

	// $row=mysql_fetch_row($result,MYSQL_ASSOC);
	// 
	// echo json_encode(array('jsonObj'=>$row));
} 
mysql_free_result($result_me);
mysql_free_result($result_global);
mysql_free_result($result_total);
//释放结果
mysql_close();
//关闭连接
unset($_SESSION['openId']);
session_destroy();
?>