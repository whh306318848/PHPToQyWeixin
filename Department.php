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
	 * 根据部门ID获取该部门下的所有子部门
	 * @param $id 部门ID，如果是获取顶级部门，则不需要
	 */
	public function getDepartmentList($id = FALSE) {
		$url = "https://qyapi.weixin.qq.com/cgi-bin/department/list";
		$data = array('access_token'=>$this->token);
		if (!empty($id)) {
			$data['id'] = $id;
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
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Query fails!', 'errcode'=>-2, 'department'=>array()));
		}
	}
	
	/**
	 * 根据部门ID获得部门信息 
	 * @param $id 部门ID
	 */
	public function getDepartmentByID($id) {
		$list = json_decode($this->getDepartmentList(), TRUE);
		$result = array('success'=>FALSE, 'errmsg'=>'Query fails!', 'errcode'=>-2, 'department'=>array());
		foreach ($list['department'] as $item) {
			if ($item['id'] == $id) {
				$result['success'] = TRUE;
				$result['errmsg'] = 'ok';
				$result['errcode'] = 0;
				$result['department'] = $item;
				break;
			}
		}
		
		return json_encode($result);
	}
	
	/**
	 * 根据部门名称查询部门信息
	 * @param $name 部门名称
	 * @param $fuzzy 是否为模糊查询，默认为模糊查询
	 */
	public function getDepartmentsByName($name, $fuzzy = TRUE) {
		$list = json_decode($this->getDepartmentList(), TRUE);
		$result = array('success'=>FALSE, 'errmsg'=>'Query fails!', 'errcode'=>-2, 'department'=>array());
		foreach ($list['department'] as $item) {
			if ($fuzzy) {
				if (stristr($item['name'], $name)) {
					$result['success'] = TRUE;
					$result['errmsg'] = $list['errmsg'];
					$result['errcode'] = $list['errcode'];
					$result['department'][] = $item;
				}
			}else {
				if ($item['name'] == $name) {
					$result['success'] = TRUE;
					$result['errmsg'] = $list['errmsg'];
					$result['errcode'] = $list['errcode'];
					$result['department'][] = $item;
				}
			}
		}
		
		return json_encode($result);
	}
	
	/**
	 * 创建部门
	 * @param $name 部门名称
	 * @param $parentid 父部门ID，默认为1
	 * @param $order 排序，数字越小越靠前，如不指定，则依次排序
	 * @param $id 指定部门ID，如不指定，则依次排序
	 */
	public function createDepartment($name, $parentid = 1, $order = FALSE, $id = FALSE) {
		if (empty($name) && empty($parentid)) {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Department Name or Parentid is empty!', 'errcode'=>-2));
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/department/create?access_token={$this->token}";
		$data = array('name'=>$name, 'parentid'=>$parentid);
		if (!empty($order)) {
			$data['order'] = $order;
		}
		if (!empty($id)) {
			$data['id'] = $id;
		}
		
		$result = $this->tools->httpRequest($url, $data, 'post');
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				$result['name'] = $name;
				return json_encode($result);
			}else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		}else {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Department creates failure!', 'errcode'=>-2));
		}
	}
	
	/**
	 * 更新部门
	 * @param $id 部门ID
	 * @param $name 部门名称，如不指定，名字不变
	 * @param $parentid 父部门ID，如不指定，父部门不变
	 * @param $order 排序，数字越小越靠前，如不指定，排序不变
	 */
	public function updateDepartment($id, $name = FALSE, $parentid = FALSE, $order = FALSE) {
		if(empty($id)) {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Department ID is empty!', 'errcode'=>-2));
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/department/update?access_token={$this->token}";
		$data = array('id'=>$id);
		if (!empty($name)) {
			$data['name'] = $name;
		}
		if (!empty($order)) {
			$data['order'] = $order;
		}
		if (!empty($parentid)) {
			$data['parentid'] = $parentid;
		}
		
		$result = $this->tools->httpRequest($url, $data, 'post');
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				$result['name'] = $name;
				return json_encode($result);
			}else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		}else {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Department creates failure!', 'errcode'=>-2));
		}
	}
	
	/**
	 * 删除部门
	 * @param $id 部门ID
	 */
	public function deleteDepartment($id) {
		if(empty($id)) {
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Department delete failed!', 'errcode'=>-2));
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/department/delete";
		$data = array('access_token'=>$this->token, 'id'=>$id);
		
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
			return json_encode(array('success'=>FALSE, 'errmsg'=>'Department delete failed!', 'errcode'=>-2));
		}
	}

}
?>