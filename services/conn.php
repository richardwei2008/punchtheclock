<?php
//�������ݿ���� 
$db_host   = 'w.rdc.sae.sina.com.cn:3307';  //���ݿ��������ƣ�һ�㶼Ϊlocalhost 
$db_user   = 'o3wo4o4j0y';        //���ݿ��û��ʺţ����ݸ���������� 
$db_passw = '1wx1j52k4y5lixiw20iz4hm5yy3y2x23y43wi2i4';   //���ݿ��û����룬���ݸ���������� 
$db_name  = 'app_beyondwechattest';         //���ݿ�������ƣ��ԸղŴ��������ݿ�Ϊ׼


//�������ݿ� 
$conn = mysql_connect($db_host,$db_user,$db_passw) or die ('���ݿ�����ʧ�ܣ�</br>����ԭ��'.mysql_error()); 
$mysqli_con=mysqli_connect($db_host,$db_user,$db_passw,$db_name);

//�����ַ�������utf8��gbk�ȣ��������ݿ���ַ������� 
mysql_query("set names 'utf8'"); 


//ѡ�����ݿ� 
mysql_select_db($db_name,$conn) or die('���ݿ�ѡ��ʧ�ܣ�</br>����ԭ��'.mysql_error()); 


//ִ��SQL���(��ѯ) 
// $result = mysql_query($sql) or die('���ݿ��ѯʧ�ܣ�</br>����ԭ��'.mysql_error()); 


//˵������δ��뱾��û��ʲô���ã���Ϊ��Ҫ�����õ�������ֻ��Ϊ�˼��ٹ����������ظ�д�������԰���ר�ŷ���һ���ļ�������Ϳ�����ʱ�����ˡ���ֻ��Ҫ���ݸ��������һ�¡��������ݿ�������͡������ַ������Ĳ��־Ϳ����ˡ�
?>