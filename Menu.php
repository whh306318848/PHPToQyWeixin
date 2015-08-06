<?php
require_once (dirname(__FILE__) . '/Tools.php');
/**
 * 微信企业号
 * 自定义菜单管理
 * @author faith whh306318848@126.com
 * @createtime 2015-08-06 17:27:29
 */
class Menu {
	private $token;
	private $tools;
	public function __construct($token) {
		$this -> token = $token;
		$this -> tools = new Tools();
	}

	/**
	 * 设置token
	 */
	public function setToken($token = NULL) {
		$this -> token = $token;
	}

	/**
	 * 获取token
	 */
	public function getToken() {
		return $this -> token;
	}
	
	/**
	 * 创建应用的自定义菜单
	 * @param $agent_id 企业应用ID
	 * @param $button_list 菜单列表，数组形式，具体格式参考微信文档，地址：http://qydev.weixin.qq.com/wiki/index.php?title=%E5%88%9B%E5%BB%BA%E5%BA%94%E7%94%A8%E8%8F%9C%E5%8D%95
	 */
	public function createMenu($agent_id, $button_list) {
		if (intval($agent_id) < 0) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Agent_id must be greater than zero!', 'errcode' => -2));
		}
		if (empty($button_list) || !is_array($button_list)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Button_list is invalid!', 'errcode' => -2));
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/menu/create?access_token={$this->token}&agentid={$agent_id}";
		$data = array('button'=>$button_list);
		
		$result = $this -> tools -> httpRequest($url, $data, 'post');
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				return json_encode($result);
			} else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		} else {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Query fails!', 'errcode' => -2));
		}
	}
	
	/**
	 * 删除企业应用自定义菜单
	 * @param $agent_id 企业应用ID
	 */
	public function deleteMenu($agent_id) {
		if (intval($agent_id) < 0) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Agent_id must be greater than zero!', 'errcode' => -2));
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/menu/delete";
		$data = array('access_token'=>$this->token, 'agentid'=>$agent_id);
		
		$result = $this -> tools -> httpRequest($url, $data);
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				return json_encode($result);
			} else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		} else {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Query fails!', 'errcode' => -2));
		}
	}
	
	/**
	 * 根据应用ID获取其自定义菜单
	 * @param $agent_id 企业应用ID
	 */
	public function getMenu($agent_id) {
		if (intval($agent_id) < 0) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Agent_id must be greater than zero!', 'errcode' => -2));
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/menu/get";
		$data = array('access_token'=>$this->token, 'agentid'=>$agent_id);
		
		$result = $this -> tools -> httpRequest($url, $data);
		if ($result) {
			if (!isset($result['errcode'])) {
				$result['success'] = TRUE;
				return json_encode($result);
			} else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		} else {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Query fails!', 'errcode' => -2));
		}
	}
}
/* End of file  */
