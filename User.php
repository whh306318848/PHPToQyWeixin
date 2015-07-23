<?php
/**
 * 微信企业号
 * 成员管理
 * @author faith whh306318848@126.com
 * @createtime 2015-07-23 03:11:41
 */
class User {
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
	 * 根据部门ID获取用户列表
	 * @param $department_id 部门ID
	 * @param $fetch_child 1/0：是否递归获取子部门下面的成员
	 * @param $status 0获取全部成员，1获取已关注成员列表，2获取禁用成员列表，4获取未关注成员列表。status可叠加
	 */
	public function getUserList($department_id = 1, $fetch_child = -1, $status = -1) {
		$url = "https://qyapi.weixin.qq.com/cgi-bin/user/simplelist";
		$data = array('access_token'=>$this->token, 'department_id'=>$department_id);
		if ($fetch_child > -1) {
			$data['fetch_child'] = $fetch_child;
		}
		if ($department_id > -1) {
			$data['department_id'] = $department_id;
		}
		
		return $this->tools->httpRequest($url, $data);
	}
}
/* End of file  */
