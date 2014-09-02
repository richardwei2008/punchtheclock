<?php
//设置数据库变量 
$db_host   = 'w.rdc.sae.sina.com.cn:3307';  //数据库主机名称，一般都为localhost 
$db_user   = 'o3wo4o4j0y';        //数据库用户帐号，根据个人情况而定 
$db_passw = '1wx1j52k4y5lixiw20iz4hm5yy3y2x23y43wi2i4';   //数据库用户密码，根据个人情况而定 
$db_name  = 'app_beyondwechattest';         //数据库具体名称，以刚才创建的数据库为准


//连接数据库 
$conn = mysql_connect($db_host,$db_user,$db_passw) or die ('数据库连接失败！</br>错误原因：'.mysql_error()); 
$mysqli_con=mysqli_connect($db_host,$db_user,$db_passw,$db_name);

//设置字符集，如utf8和gbk等，根据数据库的字符集而定 
mysql_query("set names 'utf8'"); 


//选定数据库 
mysql_select_db($db_name,$conn) or die('数据库选定失败！</br>错误原因：'.mysql_error()); 


//执行SQL语句(查询) 
// $result = mysql_query($sql) or die('数据库查询失败！</br>错误原因：'.mysql_error()); 


//说明：这段代码本身并没有什么作用，因为它要经常用到，所以只是为了减少工作量不必重复写它，所以把它专门放在一个文件里，这样就可以随时调用了。你只需要根据个人情况改一下“设置数据库变量”和“设置字符集”的部分就可以了。
?>