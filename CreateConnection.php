<?php
require_once(dirname( __FILE__ ).'/Tools.php');
/**
 * 微信企业号
 * 创建连接
 * @author faith whh306318848@126.com
 * @createtime 2015-07-23 02:34:12
 */
class CreateConnection {
	
	
	function __construct($argument = FALSE) {
		
	}
	
	/**
	 * 获取AccessToken
	 * @param $corpid 企业ID
	 * @param $corpsecret 管理组的凭证密钥
	 */
	public function getAccessToken($corpid = FALSE, $corpsecret = FALSE) {
		if (empty($corpid) || empty($corpsecret)) {
			return FALSE;
		}
		
		$tools = new Tools();
		$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken";
		$data = array('corpid'=>$corpid, 'corpsecret'=>$corpsecret);
		$result = $tools->httpRequest($url, $data);
		if (isset($result['access_token'])) {
			return $result['access_token'];
		}else {
			//echo $result['errcode'].":".$result['errmsg'];
			return FALSE;
		}
	}
	
	
}


?>