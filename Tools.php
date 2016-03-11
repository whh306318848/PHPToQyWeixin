<?php
/**
 * 企业微信号
 * 工具类
 * @author faith whh306318848@126.com
 * @createtime 2015-07-23 02:34:12
 */
class Tools {

	function __construct($argument = FALSE) {

	}

	/**
	 * 发起http请求
	 * @param $url 请求的URL
	 * @param $parameters 请求的参数
	 * @param $method 请求的方法，只能是get或post
	 */
	public function httpRequest($url, $parameters = NULL, $method = 'get') {
		$method = strtolower($method);
		switch ($method) {
			case 'get' :
				return $this -> httpGetRequest($url, $parameters);
				break;
			case 'post' :
				return $this -> httpPostRequest($url, $parameters);
				break;
			default :
				return FALSE;
				break;
		}
	}

	/**
	 * 发起httpGET请求
	 * @param $url 请求的URL
	 * @param $parameters 请求的参数，以数组形式传递
	 */
	private function httpGetRequest($url, $parameters = NULL) {
		if (empty($url)) {
			return FALSE;
		}
		// 将请求参数追加在url后面
		if (!empty($parameters) && is_array($parameters) && count($parameters)) {
			$is_first = TRUE;
			foreach ($parameters as $key => $value) {
				if ($is_first) {
					$url .= "?" . $key . "=" . urlencode($value);
					$is_first = FALSE;
				} else {
					$url .= "&" . $key . "=" . urlencode($value);
				}
			}
		}

		//初始化CURL
		$ch = curl_init();
		// 设置要请求的URL
		curl_setopt($ch, CURLOPT_URL, $url);
		// 设置不显示头部信息
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		// 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// 设置本地不检测SSL证书
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		// 执行请求动作，并获取结果
		$result = curl_exec($ch);
		if ($error = curl_error($ch)) {
			die($error);
		}
		// 关闭CURL
		curl_close($ch);

		return json_decode($result, TRUE);
	}

	/**
	 * 发起httpPOST请求
	 * @param $url 请求的URL
	 * @param $parameters 请求的参数，以数组形式传递
	 */
	private function httpPostRequest($url, $parameters = array()) {
		if (empty($url)) {
			return FALSE;
		}

		// 初始化CURL
		$ch = curl_init();
		// 设置要请求的URL
		curl_setopt($ch, CURLOPT_URL, $url);
		// 设置不显示头部信息
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		// 设置不将请求结果直接输出在标准输出里，而是返回
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// 设置本地不检测SSL证书
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		//设置post方式提交
		curl_setopt($ch, CURLOPT_POST, TRUE);
		// 设置请求参数
		if (!empty($parameters)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->json_encode_ex($parameters));
		}
		// 执行请求动作，并获取结果
		$result = curl_exec($ch);
		if ($error = curl_error($ch)) {
			die($error);
		}
		// 关闭CURL
		curl_close($ch);

		return json_decode($result, TRUE);
	}

	/**
	 * 使用POST请求上传文件
	 */
	public function uploadFileByPost($url, $data) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SAFE_UPLOAD, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		if ($error = curl_error($ch)) {
			die($error);
		}
		curl_close($ch);

		return json_decode($result, TRUE);
	}

	/**
	 * 用CURL发起一个HTTP请求
	 * @param $url 访问的URL
	 * @param $post post数据(不填则为GET)
	 * @param $cookie 提交的$cookies
	 * @param $returnCookie 是否返回$cookies
	 */
	public function curlRequest($url, $post = '', $cookie = '', $returnCookie = 0) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
		if ($post) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		if ($cookie) {
			curl_setopt($curl, CURLOPT_COOKIE, $cookie);
		}
		curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$data = curl_exec($curl);
		if (curl_errno($curl)) {
			return curl_error($curl);
		}
		curl_close($curl);
		if ($returnCookie) {
			list($header, $body) = explode("\r\n\r\n", $data, 2);
			preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
			$info['cookie'] = substr($matches[1][0], 1);
			$info['content'] = $body;
			return $info;
		} else {
			return $data;
		}
	}

	/**
	 * 保存从网络上获取到的AccessToken
	 * @param $corpid 企业ID
	 * @param $corpsecret 管理组的凭证密钥
	 * @param $token 从网络上获取到的AccessToken
	 */
	public function saveAccessToken($corpid, $corpsecret, $token) {
		if (empty($corpid) || empty($corpsecret) || empty($token)) {
			return FALSE;
		}
		if (!file_exists(dirname(__FILE__) . '/token.bin')) {
			file_put_contents(dirname(__FILE__) . '/token.bin', "");
		}

		$result = file_get_contents(dirname(__FILE__) . '/token.bin');

		$result = json_decode($result, TRUE);
		$key = $corpid . $corpsecret;
		if (empty($result)) {
			$result = array($key => array($token, time()));
		} else {
			$result[] = array($key => array($token, time()));
		}

		if (file_put_contents(dirname(__FILE__) . '/token.bin', json_encode($result))) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * 保存从网络上获取到的AccessToken
	 * @param $corpid 企业ID
	 * @param $corpsecret 管理组的凭证密钥
	 * @return 当前企业ID和管理组的凭证密钥对应的AccessToken，没有则返回false
	 */
	public function getAccessToken($corpid, $corpsecret) {
		if (empty($corpid) || empty($corpsecret)) {
			return FALSE;
		}

		if (!file_exists(dirname(__FILE__) . '/token.bin')) {
			return FALSE;
		}

		$result = file_get_contents(dirname(__FILE__) . '/token.bin');
		if (empty($result)) {
			return FALSE;
		}

		$result = json_decode($result, TRUE);
		$key = $corpid . $corpsecret;
		if (isset($result[$key])) {
			if (time() - 7200 > $result[$key][1]) {
				// token已超时
				return FALSE;
			} else {
				// token未超时
				return $result[$key][0];
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * 对内容进行json编码，并且保持汉字不会被编码
	 * @param $value 被编码的对象
	 * @return 编码结果字符串
	 */
	public function json_encode_ex($value) {
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
			$str = json_encode($value);
			$str = preg_replace_callback("#\\\u([0-9a-f]{4})#i", function($matchs) {
				return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
			}, $str);
			return $str;
		} else {
			return json_encode($value, JSON_UNESCAPED_UNICODE);
		}
	}

}
?>