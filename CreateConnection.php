<?php
require_once(dirname( __FILE__ ).'/Tools.php');
/**
 * 微信企业号
 * 创建连接
 * @author faith whh306318848@126.com
 * @createtime 2015-07-23 02:34:12
 */
class CreateConnection {
	private $tools = NULL;
	
	function __construct($argument = FALSE) {
		$this->tools = new Tools();
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
		
		$result = $this->getLocalAccessToken($corpid, $corpsecret);
		if (empty($result)) {
			$result = $this->getRemoteAccessToken($corpid, $corpsecret);
			if (empty($result)) {
				return FALSE;
			}else {
				return $result;
			}
		}else {
			return $result;
		}
	}
	
	/**
	 * 获取存储在本地的AccessToken
	 * @param $corpid 企业ID
	 * @param $corpsecret 管理组的凭证密钥
	 */
	private function getLocalAccessToken($corpid = FALSE, $corpsecret = FALSE) {
		if (empty($corpid) || empty($corpsecret)) {
			return FALSE;
		}
		
		return $this->tools->getAccessToken($corpid, $corpsecret);
	}
	
	/**
	 * 从服务器上获取AccessToken
	 * @param $corpid 企业ID
	 * @param $corpsecret 管理组的凭证密钥
	 */
	private function getRemoteAccessToken($corpid = FALSE, $corpsecret = FALSE) {
		if (empty($corpid) || empty($corpsecret)) {
			return FALSE;
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken";
		$data = array('corpid'=>$corpid, 'corpsecret'=>$corpsecret);
		
		$result = $this->tools->httpRequest($url, $data);
		if (isset($result['access_token'])) {
			$this->tools->saveAccessToken($corpid, $corpsecret, $result['access_token']);
			return $result['access_token'];
		}else {
			//echo $result['errcode'].":".$result['errmsg'];
			return FALSE;
		}
	}
}


?>