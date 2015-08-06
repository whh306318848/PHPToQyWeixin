<?php
require_once (dirname(__FILE__) . '/Tools.php');
/**
 * 微信企业号
 * 应用管理
 * @author faith whh306318848@126.com
 * @createtime 2015-08-06 15:43:39
 */
class Agent {
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
	 * 根据应用ID获取应用详情
	 * @param $agent_id 授权方应用id
	 */
	public function getAgentByID($agent_id) {
		if (intval($agent_id) < 0) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Agent_id must be greater than zero!', 'errcode' => -2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/agent/get";
		$data = array('access_token' => $this -> token, 'agentid' => $agent_id);

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
	 * 根据应用ID修改应用信息
	 * @param $agent_id 企业应用的id
	 * @param $name 企业应用名称
	 * @param $description 企业应用详情
	 * @param $logo_mediaid 企业应用头像的mediaid，通过多媒体接口上传图片获得mediaid，上传后会自动裁剪成方形和圆形两个头像
	 * @param $redirect_domain 企业应用可信域名
	 * @param $is_reportuser 是否接收用户变更通知。0：不接收；1：接收
	 * @param $is_reportenter 是否上报用户进入应用事件。0：不接收；1：接收
	 * @param $report_location_flag 企业应用是否打开地理位置上报 0：不上报；1：进入会话上报；2：持续上报
	 */
	public function setAgentByID($agent_id, $name = FALSE, $description = FALSE, $logo_mediaid = FALSE, $redirect_domain = FALSE, $is_reportuser = -1, $is_reportenter = -1, $report_location_flag = -1) {
		if (intval($agent_id) < 0) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Agent_id must be greater than zero!', 'errcode' => -2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/agent/set?access_token={$this->token}";
		$data = array('agentid' => $agent_id);
		if (!empty($name)) {
			$data['name'] = $name;
		}
		if (!empty($description)) {
			$data['description'] = $description;
		}
		if (!empty($logo_mediaid)) {
			$data['logo_mediaid'] = $logo_mediaid;
		}
		if (!empty($redirect_domain)) {
			$data['redirect_domain'] = $redirect_domain;
		}
		if (intval($is_reportuser) >= 0 && intval($is_reportuser) <= 1) {
			$data['isreportuser'] = $is_reportuser;
		}
		if (intval($is_reportenter) >= 0 && intval($is_reportenter) <= 1) {
			$data['isreportenter'] = $is_reportenter;
		}
		if (intval($report_location_flag) >= 0 && intval($report_location_flag) <= 2) {
			$data['report_location_flag'] = $report_location_flag;
		}

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
			return json_encode(array('success' => FALSE, 'errmsg' => 'Change agent fails!', 'errcode' => -2));
		}
	}
	
	/**
	 * 获取企业应用列表
	 */
	public function getAgentList() {
		$url = "https://qyapi.weixin.qq.com/cgi-bin/agent/list?access_token={$this->token}";
		
		$result = $this -> tools -> httpRequest($url);
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				return json_encode($result);
			} else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		} else {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Query fails!', 'errcode' => -2, 'agentlist'=>array()));
		}
	}
	
	/**
	 * 根据应用名称，获得应用详情
	 * @param $name 标签名字
	 * @param $fuzzy 是否为模糊查询，默认为模糊查询
	 */
	public function getAgentByName($name, $fuzzy = TRUE) {
		if (empty($name)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Name is empty!', 'errcode' => -2, 'agentlist' => array()));
		}
		
		$result = array('success' => FALSE, 'errmsg' => 'Query fails!', 'errcode' => -2, 'agentlist' => array());
		$agent_list = $this->getAgentList();
		$agent_list = json_decode($agent_list, TRUE);
		if (isset($agent_list['agentlist']) && is_array($agent_list['agentlist']) && count($agent_list['agentlist'])) {
			foreach ($agent_list['agentlist'] as $item) {
				if ($fuzzy) {
					if (stristr($item['name'], $name)) {
						$agent = json_decode($this->getAgentByID($item['agentid']), TRUE);
						if ($agent && isset($agent['errcode']) && intval($agent['errcode']) == 0) {
							unset($agent['errcode']);
							unset($agent['errmsg']);
							unset($agent['success']);
							$result['success'] = TRUE;
							$result['errmsg'] = $agent_list['errmsg'];
							$result['errcode'] = $agent_list['errcode'];
							$result['agentlist'][] = $agent;
						}
					}
				} else {
					if ($item['name'] == $name) {
						$agent = json_decode($this->getAgentByID($item['agentid']), TRUE);
						if ($agent && isset($agent['errcode']) && intval($agent['errcode']) == 0) {
							unset($agent['errcode']);
							unset($agent['errmsg']);
							unset($agent['success']);
							$result['success'] = TRUE;
							$result['errmsg'] = $agent_list['errmsg'];
							$result['errcode'] = $agent_list['errcode'];
							$result['agentlist'][] = $agent;
						}
					}
				}
			}
		}
		
		return json_encode($result);
	}

}

/* End of file  */
