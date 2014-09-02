<?php 
require('conn.php'); 
$jsonPOST = json_decode(file_get_contents("php://input"));
$openid=$jsonPOST->openid;
$userid = null;

$sql_query_user = "SELECT info.id, info.openid, info.nickname, info.name, info.headimgurl FROM attendance_user_info info WHERE openid = '$openid'";
if (null != $openid && "" != $openid) {
    $result = mysqli_query($mysqli_con, $sql_query_user);
    $me = mysqli_fetch_object($result);
	if ($me == null) {
        echo json_encode(array('user'=>$jsonPOST, 'me'=>$me, 'message'=>"Invalid Open Id [".$openid."]"));
        return;
	}
	echo json_encode(array('user'=>$jsonPOST, 'me'=>$me, 'sql'=>$sql_query_user));
} else {
	echo json_encode(array('user'=>$jsonPOST, 'me'=>$me, 'message'=>"Invalid Open Id [".$openid."]"));
}
mysqli_close($mysqli_con);
?>