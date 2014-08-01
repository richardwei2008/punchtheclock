<?php session_start();
	$code = $_GET['code']; //前端传来的code值
	$appid = "wxc63c757bdae5dd41";
	$appsecret = "05fc96a2887de802e694252b040fc53f";//获取openid
	$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";	
	$result = https_request($url);
	$jsoninfo = json_decode($result, true);
	$openid = $jsoninfo["openid"]; //从返回json结果中读出openid
	$callback=$_GET['callback'];  // 
	// echo $callback."({openid:'".$openid."'})"; 
	
	/** richard add read profile */
	$urlToGetAccessToken = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
	$token_response = https_request($urlToGetAccessToken);
	$jsoninfo = json_decode($token_response, true);
	$access_token = $jsoninfo["access_token"];
	$urlToGetUserProfile = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
	$user_response = https_request($urlToGetUserProfile);
	/** end read profile */
	
	$callback=$_GET['callback'];  // 
	echo $callback."({result:'".$user_response."'})"; 
	/// echo $openid; //把openid 送回前端
	function https_request($url, $data = null) {    
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)){        
			curl_setopt($curl, CURLOPT_POST, 1);        
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);    
		}    
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);    
		$output = curl_exec($curl);    
		curl_close($curl);    
		return $output;
	}
?> 
