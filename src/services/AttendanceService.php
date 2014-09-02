<?php 
require('conn.php'); 
$jsonPOST = json_decode(file_get_contents("php://input"));
$subscribe=$jsonPOST->subscribe;
$openid=$jsonPOST->openid;
$nickname=$jsonPOST->nickname; 
$sex = $jsonPOST->sex;
$language = $jsonPOST->language;
$city = $jsonPOST->city;
$province = $jsonPOST->province;
$country = $jsonPOST->country;
$headimgurl = $jsonPOST->headimgurl;
$subscribetime = $jsonPOST->subscribe_time;
$userid = null;

$sql_query_user = "SELECT info.id, info.openid, nickname, info.headimgurl FROM attendance_user_info info WHERE openid = '$openid'";
$sql_insert_user = "INSERT INTO attendance_user_info (subscribe, openid, nickname, sex, language, city, province, country, headimgurl, subscribetime) VALUES ('$subscribe', '$openid', '$nickname', $sex, '$language', '$city', '$province', '$country', '$headimgurl', $subscribetime)";
$sql_update_user = "UPDATE attendance_user_info set subscribe = '$subscribe', nickname = '$nickname', sex=$sex, language='$language', city='$city', province='$province', country='$country', headimgurl='$headimgurl', subscribetime=$subscribetime WHERE openid = '$openid'";

$sql_query_punch = "SELECT p.id, p.userid, p.openid, p.createtime FROM attendance_user_punch p WHERE p.openid = '$openid'";

$result = mysqli_query($mysqli_con, $sql_query_user);
$me = mysqli_fetch_object($result);
// echo json_encode(array('me'=>$me, 'openid' => $openid, 'sql'=>$sql_query_user));
if (null != $openid && "" != $openid) {
	if ($me == null) {
		if (mysqli_query($mysqli_con, $sql_insert_user)) {
			$result = mysqli_query($mysqli_con, $sql_query_user);
			$me = mysqli_fetch_object($result);
			$message = "Successfully inserted ";
		} else {
			echo "Error occurred: " . mysqli_error($mysqli_con);
		}
	} else {
		if (mysqli_query($mysqli_con, $sql_update_user)) {
			$message = "Successfully updated ";
		} else {
			echo "Error occurred: " . mysqli_error($mysqli_con);
		}
	}	
	$userid=$me->id;
	$sql_insert_punch = "INSERT INTO attendance_user_punch (userid, openid) VALUES ('$userid', '$openid')";
	mysqli_query($mysqli_con, $sql_insert_punch);
	echo json_encode(array('user'=>$jsonPOST, 'me'=>$me, 'message'=>$message. mysqli_affected_rows($mysqli_con)."row", 'sql'=>$sql_insert_punch));
} else {
	echo json_encode(array('user'=>$jsonPOST, 'me'=>$me, 'message'=>"Invalid Open Id [".$openid."]"));
}
mysqli_close($mysqli_con);
?>