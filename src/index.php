<?php
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{
				
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
	
	private function get($url) {		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
	
	private function getAccessToken() {
		$APPID = "wxc63c757bdae5dd41";
		$APPSECRET = "fa68568880b31435badf3070cdd27a54";
		$urlToGetAccessToken = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$APPSECRET;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $urlToGetAccessToken);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		// $output = get($urlToGetAccessToken);
		$jsoninfo = json_decode($output, true);
		$access_token = $jsoninfo["access_token"];
		return $access_token;
	}
	
	private function getUserName($accessToken, $openId) {
		$urlToGetUserProfile = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$accessToken."&openid=".$openId."&lang=zh_CN";
		$output = get($urlToGetUserProfile);
		$jsoninfo = json_decode($output, true);
		$user_name = $jsoninfo["nickname"];
		return $user_name;
	}
	
	 private function transmitText($object, $content) {
        $textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					</xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
			$RX_TYPE = trim($postObj->MsgType);

            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    if($keyword == "?" || $keyword == "？")
					{
						$msgType = "text";
						$contentStr = date("Y-m-d H:i:s",time());
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
					}
					if($keyword == "u" || $keyword == "U")
					{
						$msgType = "text";				
						// $accessToken = getAccessToken();				
						$APPID = "wxc63c757bdae5dd41";
						$APPSECRET = "fa68568880b31435badf3070cdd27a54";
						$urlToGetAccessToken = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$APPSECRET;
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $urlToGetAccessToken);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$output = curl_exec($ch);
						curl_close($ch);
						// $output = get($urlToGetAccessToken);
						$jsoninfo = json_decode($output, true);
						$access_token = $jsoninfo["access_token"];
						$contentStr = "Access Token: ".$access_token;
						// $contentStr = getUserName($accessToken, $openId);
						$openid = $fromUsername;
						// $urlToGetUserProfile = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$accessToken."&openid=".$openId."&lang=zh_CN";
						$urlToGetUserProfile = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $urlToGetUserProfile);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$output = curl_exec($ch);
						curl_close($ch);
						$jsoninfo = json_decode($output, true);
						$nickname = $jsoninfo["nickname"];
						$contentStr = "Nickname: ".$nickname;
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
					}
					if($keyword == "上班" || $keyword == "下班")
					{
						$msgType = "text";				
						// $accessToken = getAccessToken();				
						$APPID = "wxc63c757bdae5dd41";
						$APPSECRET = "fa68568880b31435badf3070cdd27a54";
						$urlToGetAccessToken = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$APPSECRET;
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $urlToGetAccessToken);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$output = curl_exec($ch);
						curl_close($ch);
						// $output = get($urlToGetAccessToken);
						$jsoninfo = json_decode($output, true);
						$access_token = $jsoninfo["access_token"];
						$contentStr = "Access Token: ".$access_token;
						// $contentStr = getUserName($accessToken, $openId);
						$openid = $fromUsername;
						$urlToGetUserProfile = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $urlToGetUserProfile);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$output = curl_exec($ch);
						curl_close($ch);
						$jsoninfo = json_decode($output, true);
						$nickname = $jsoninfo["nickname"];
						$contentStr = $nickname." 于 [".date("Y-m-d H:i:s",time())."] ".$keyword;
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
					}
                    break;
                case "image":
                    
                    break;
                case "location":
                    $content = "你发送的是位置，纬度为：".$postObj->Location_X."；经度为：".$postObj->Location_Y."；缩放级别为：".$postObj->Scale."；位置为：".$postObj->Label;
					$resultStr = $this->transmitText($postObj, $content);
					echo $resultStr;
                    break;
                case "voice":
                    $result = $this->receiveVoice($postObj);
                    break;
                case "video":
                    $result = $this->receiveVideo($postObj);
                    break;
                case "link":
                    $result = $this->receiveLink($postObj);
                    break;
                default:
                    $result = "unknown msg type: ".$RX_TYPE;
                    break;
            }
		
            
        }else{
            echo "";
            exit;
        }
    }
}
?>