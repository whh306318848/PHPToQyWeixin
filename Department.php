<?php
require_once (dirname(__FILE__) . '/CreateConnection.php');
require_once (dirname(__FILE__) . '/Tools.php');
/**
 * 部门管理类
 * @author faith whh306318848@126.com
 * @createtime 2015-07-23 02:34:12
 */
class Department {
	private $token;
	private $tools;

	public function __construct($token = NULL) {
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
	 * 根据部门ID获取该部门下的所有子部门
	 * @param $id 部门ID，如果是获取顶级部门，则不需要
	 */
	public function getDepartmentList($id = FALSE) {
		$url = "https://qyapi.weixin.qq.com/cgi-bin/department/list";
		$data = array('access_token'=>$this->token);
		if (!empty($id)) {
			$data['id'] = $id;
		}
		return $this->tools->httpRequest($url, $data);
	}

}
?>