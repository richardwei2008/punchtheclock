<?php 
require('conn.php'); 
$jsonPOST = json_decode(file_get_contents("php://input"));

$openid=$jsonPOST->openid;
$days=$jsonPOST->data->days;
$reason = $jsonPOST->data->reason;
$fromDate = $jsonPOST->data->fromDate;
$fromTime = $jsonPOST->data->fromTime;
$toDate = $jsonPOST->data->toDate;
$toTime = $jsonPOST->data->toTime;
$supplement = $jsonPOST->data->supplement;

$sql_query_user = "SELECT info.id, info.openid, info.nickname, info.headimgurl FROM attendance_user_info info WHERE openid = '$openid'";
// echo json_encode(array('me'=>$me, 'openid' => $openid, 'sql'=>$sql_query_user));
if (null != $openid && "" != $openid) {
    $result = mysqli_query($mysqli_con, $sql_query_user);
    $me = mysqli_fetch_object($result);
	if ($me == null) {
        echo json_encode(array('success'=>false, 'user'=>$jsonPOST, 'me'=>$me, 'message'=>"Invalid Open Id [".$openid."]"));
        return;
	}
	$sql_insert_leave = "INSERT INTO attendance_user_leave (openid, days, reason, fromDate, fromTime, toDate, toTime, supplement)"
                    ." VALUES ('$openid', $days, '$reason', '$fromDate', '$fromTime', '$toDate', '$toTime', '$supplement')";
	mysqli_query($mysqli_con, $sql_insert_leave);
	echo json_encode(array('success'=>true, 'user'=>$jsonPOST, 'me'=>$me, 'message'=>$message. mysqli_affected_rows($mysqli_con)."row", 'sql'=>$sql_insert_leave));
} else {
	echo json_encode(array('success'=>false, 'user'=>$jsonPOST, 'me'=>$me, 'message'=>"Invalid Open Id [".$openid."]"));
}
mysqli_close($mysqli_con);
?>