<?php
require_once (dirname(__FILE__) . '/CreateConnection.php');
require_once (dirname(__FILE__) . '/Tools.php');
/**
 * 微信企业号
 * 成员管理
 * @author faith whh306318848@126.com
 * @createtime 2015-07-23 03:11:41
 */
class User {
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
	 * 根据部门ID获取用户列表
	 * @param $department_id 部门ID
	 * @param $fetch_child 1/0：是否递归获取子部门下面的成员
	 * @param $status 0获取全部成员，1获取已关注成员列表，2获取禁用成员列表，4获取未关注成员列表。status可叠加
	 */
	public function getUserList($department_id = 1, $fetch_child = 0, $status = 0) {
		if (intval($department_id) < 1) {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Department_id must be greater than zero!', 'errcode'=>-2, 'userlist'=>array()));
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/user/simplelist";
		$data = array('access_token'=>$this->token, 'department_id'=>$department_id);
		if (intval($fetch_child) > -1) {
			$data['fetch_child'] = $fetch_child;
		}
		if (intval($status) > -1) {
			$data['status'] = $status;
		}
		
		$result = $this->tools->httpRequest($url, $data);
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				return json_encode($result);
			}else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		}else {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Query fails!', 'errcode'=>-2, 'userlist'=>array()));
		}
	}
	
	/**
	 * 根据部门ID获取用户列表（详情）
	 * @param $department_id 部门ID
	 * @param $fetch_child 1/0：是否递归获取子部门下面的成员
	 * @param $status 0获取全部成员，1获取已关注成员列表，2获取禁用成员列表，4获取未关注成员列表。status可叠加
	 */
	public function getUserListDetails($department_id = 1, $fetch_child = 0, $status = 0) {
		if (intval($department_id) < 1) {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Department_id must be greater than zero!', 'errcode'=>-2, 'userlist'=>array()));
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/user/list";
		$data = array('access_token'=>$this->token, 'department_id'=>$department_id);
		if (intval($fetch_child) > -1) {
			$data['fetch_child'] = $fetch_child;
		}
		if (intval($status) > -1) {
			$data['status'] = $status;
		}
		
		$result = $this->tools->httpRequest($url, $data);
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				return json_encode($result);
			}else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		}else {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Query fails!', 'errcode'=>-2, 'userlist'=>array()));
		}
	}
	
	/**
	 * 根据成员ID获取用户详细信息
	 * @param $userid 用户ID
	 */
	public function getUserByID($userid) {
		if (empty($userid)) {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Userid is empty!', 'errcode'=>-2));
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/user/get";
		$data = array('access_token'=>$this->token, 'userid'=>$userid);
		
		$result = $this->tools->httpRequest($url, $data);
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				$result['userid'] = $userid;
				return json_encode($result);
			}else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		}else {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Query fails!', 'errcode'=>-2));
		}
	}
	
	/**
	 * 根据成员名称获得成员详细信息
	 * @param $name 成员名称
	 * @param $fuzzy 是否为模糊查询，默认为模糊查询
	 */
	public function getUserByName($name, $fuzzy = TRUE) {
		if (empty($name)) {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Name is empty!', 'errcode'=>-2));
		}
		$result = array('success'=>FALSE, 'errmsg'=>'Query fails!', 'errcode'=>-2, 'userlist'=>array());
		$userlist = $this->getUserListDetails(1, 1);
		$userlist = json_decode($userlist, TRUE);
		foreach ($userlist['userlist'] as $item) {
			if ($fuzzy) {
				if (stristr($item['name'], $name)) {
					$result['success'] = TRUE;
					$result['errmsg'] = $userlist['errmsg'];
					$result['errcode'] = $userlist['errcode'];
					$result['userlist'][] = $item;
				}
			}else {
				if ($item['name'] == $name) {
					$result['success'] = TRUE;
					$result['errmsg'] = $userlist['errmsg'];
					$result['errcode'] = $userlist['errcode'];
					$result['userlist'][] = $item;
				}
			}
		}

		return json_encode($result);
	}
	
	
	/**
	 * 创建成员
	 * @param $userid 成员UserID。对应管理端的帐号，企业内必须唯一。长度为1~64个字节，必填
	 * @param $name 成员名称。长度为1~64个字节，必填
	 * @param $departmentid 成员所属部门id列表。注意，每个部门的直属成员上限为1000个
	 * @param $mobile 手机号码。企业内必须唯一，mobile/weixinid/email三者不能同时为空
	 * @param $email 邮箱。长度为0~64个字节。企业内必须唯一，mobile/weixinid/email三者不能同时为空
	 * @param $weixinid 微信号。企业内必须唯一。（注意：是微信号，不是微信的名字）
	 * @param $position 职位信息。长度为0~64个字节
	 * @param $gender 性别。1表示男性，2表示女性
	 * @param $avatar_mediaid 成员头像的mediaid，通过多媒体接口上传图片获得的mediaid
	 * @param $extattr 扩展属性。扩展属性需要在WEB管理端创建后才生效，否则忽略未知属性的赋值，以数组形式传入
	 */
	public function createUser($userid, $name, $departmentid, $mobile = FALSE, $email = FALSE, $weixinid = FALSE, $position = FALSE, $gender = FALSE, $avatar_mediaid = FALSE, $extattr = array()) {
		if (empty($userid) || empty($name) || empty($departmentid)) {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Userid or name or departmentid is empty!', 'errcode'=>-2));
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/user/create?access_token={$this->token}";
		$data = array('userid'=>$userid, 'name'=>$name, 'department'=>$departmentid);
		if (!empty($mobile)) {
			$data['mobile'] = $mobile;
		}
		if (!empty($email)) {
			$data['email'] = $email;
		}
		if (!empty($weixinid)) {
			$data['weixinid'] = $weixinid;
		}
		if (!empty($position)) {
			$data['position'] = $position;
		}
		if (!empty($gender)) {
			$data['gender'] = $gender;
		}
		if (!empty($avatar_mediaid)) {
			$data['avatar_mediaid'] = $avatar_mediaid;
		}
		if (!empty($extattr) && is_array($extattr)) {
			$temp =  array();
			foreach ($extattr as $key => $value) {
				$temp[] = array('name'=>$key, 'value'=>$value);
			}
			$data['extattr'] = array('attrs'=>$temp);
		}
		
		$result = $this->tools->httpRequest($url, $data, 'post');
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				$result['userid'] = $userid;
				$result['name'] = $name;
				$this->inviteConcern($userid);
				return json_encode($result);
			}else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		}else {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Create a user fails!', 'errcode'=>-2));
		}
	}
	
	/**
	 * 更新成员
	 * @param $userid 成员UserID。对应管理端的帐号，企业内必须唯一。长度为1~64个字节，必填
	 * @param $name 成员名称。长度为1~64个字节，必填
	 * @param $departmentid 成员所属部门id列表。注意，每个部门的直属成员上限为1000个
	 * @param $position 职位信息。长度为0~64个字节
	 * @param $mobile 手机号码。企业内必须唯一，mobile/weixinid/email三者不能同时为空
	 * @param $gender 性别。1表示男性，2表示女性
	 * @param $email 邮箱。长度为0~64个字节。企业内必须唯一，mobile/weixinid/email三者不能同时为空
	 * @param $weixinid 微信号。企业内必须唯一。（注意：是微信号，不是微信的名字）
	 * @param $enable 启用/禁用成员。1表示启用成员，0表示禁用成员，默认为1
	 * @param $avatar_mediaid 成员头像的mediaid，通过多媒体接口上传图片获得的mediaid
	 * @param $extattr 扩展属性。扩展属性需要在WEB管理端创建后才生效，否则忽略未知属性的赋值，以数组形式传入
	 */
	public function updateUser($userid, $name = FALSE, $departmentid = FALSE, $position = FALSE, $mobile= FALSE, $gender = FALSE, $email = FALSE, $weixinid = FALSE, $enable = 1, $avatar_mediaid = FALSE, $extattr = array()) {
		if (empty($userid)) {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Userid is empty!', 'errcode'=>-2));
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/user/update?access_token={$this->token}";
		$data = array('userid'=>$userid, 'enable'=>$enable);
		if (!empty($name)) {
			$data['name'] = $name;
		}
		if (!empty($departmentid)) {
			$data['department'] = $departmentid;
		}
		if (!empty($position)) {
			$data['position'] = $position;
		}
		if (!empty($mobile)) {
			$data['mobile'] = $mobile;
		}
		if (!empty($gender)) {
			$data['gender'] = $gender;
		}
		if (!empty($email)) {
			$data['email'] = $email;
		}
		if (!empty($weixinid)) {
			$data['weixinid'] = $weixinid;
		}
		if (!empty($avatar_mediaid)) {
			$data['avatar_mediaid'] = $avatar_mediaid;
		}
		if (!empty($extattr) && is_array($extattr)) {
			$temp =  array();
			foreach ($extattr as $key => $value) {
				$temp[] = array('name'=>$key, 'value'=>$value);
			}
			$data['extattr'] = array('attrs'=>$temp);
		}
		
		$result = $this->tools->httpRequest($url, $data, 'post');
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				$result['userid'] = $userid;
				$this->inviteConcern($userid);
				return json_encode($result);
			}else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		}else {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Update a user fails!', 'errcode'=>-2));
		}
	}
	
	/**
	 * 删除成员
	 * @param $userid 成员UserID。对应管理端的帐号，企业内必须唯一。长度为1~64个字节，必填
	 */
	public function deleteUser($userid) {
		if (empty($userid)) {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Userid is empty!', 'errcode'=>-2));
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/user/delete";
		$data = array('access_token'=>$this->token, 'userid'=>$userid);
		
		$result = $this->tools->httpRequest($url, $data);
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				$result['userid'] = $userid;
				return json_encode($result);
			}else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		}else {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Delete a user fails!', 'errcode'=>-2));
		}
	}
	
	/**
	 * 批量删除成员
	 * @param $useridlist 成员UserID列表。  形如["zhangsan", "lisi"]的一维数组
	 */
	public function batchDeleteUsers($useridlist) {
		if (empty($useridlist)) {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Useridlist is empty!', 'errcode'=>-2));
		}else if (!is_array($useridlist)) {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Useridlist not an array!', 'errcode'=>-2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/user/batchdelete?access_token={$this->token}";
		$data = array('useridlist'=>$useridlist);
		
		$result = $this->tools->httpRequest($url, $data, 'post');
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				return json_encode($result);
			}else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		}else {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Batch delete users fails!', 'errcode'=>-2));
		}
	}

	/**
	 * 邀请成员关注企业号
	 * 认证号优先使用微信推送邀请关注，如果没有weixinid字段则依次对手机号，邮箱绑定的微信进行推送，全部没有匹配则通过邮件邀请关注。 邮箱字段无效则邀请失败。 非认证号只通过邮件邀请关注。邮箱字段无效则邀请失败。 已关注以及被禁用成员不允许发起邀请关注请求。
	 * 为避免骚扰成员，企业应遵守以下邀请规则：
	 * 每月邀请的总人次不超过成员上限的2倍；每7天对同一个成员只能邀请一次。
	 * @param $userid 成员UserID。对应管理端的帐号
	 */
	public function inviteConcern($userid) {
		if (empty($userid)) {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Invite concern failure!', 'errcode'=>-2));
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/invite/send?access_token={$this->token}";
		$data = array('userid'=>$userid);
		
		$result = $this->tools->httpRequest($url, $data, 'post');
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				$result['userid'] = $userid;
				if ($result['type'] == 1) {
					$result['result'] = '已发出微信邀请';
				}else if ($result['type'] == 2) {
					$result['result'] = '已发出邮件邀请';
				}else {
					$result['result'] = '已发出邀请';
				}
				return json_encode($result);
			}else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		}else {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Invite concern failure!', 'errcode'=>-2));
		}
	}
}
/* End of file  */
