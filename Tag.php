<?php
require_once (dirname(__FILE__) . '/Tools.php');
/**
 * 微信企业号
 * 标签管理
 * @author faith whh306318848@126.com
 * @createtime 2015-07-31 21:34:33
 */
class Tag {
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
	 * 获取标签列表
	 */
	public function getTagList() {
		$url = "https://qyapi.weixin.qq.com/cgi-bin/tag/list";
		$data = array('access_token' => $this -> token);

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
			return json_encode(array('success' => FALSE, 'errmsg' => 'Query fails!', 'errcode' => -2, 'taglist' => array()));
		}
	}

	/**
	 * 根据标签ID获取标签信息
	 * @param $tagid 标签ID
	 */
	public function getTagByID($tag_id = FALSE) {
		if (intval($tag_id) < 1) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Tag_id must be greater than zero!', 'errcode' => -2, 'taglist' => array()));
		}

		$result = array('success' => FALSE, 'errmsg' => 'Query fails!', 'errcode' => -2, 'taglist' => array());
		$taglist = $this -> getTagList();
		$taglist = json_decode($taglist, TRUE);
		if (isset($taglist['taglist']) && is_array($taglist['taglist']) && count($taglist['taglist'])) {
			foreach ($taglist['taglist'] as $item) {
				$result['success'] = TRUE;
				$result['errmsg'] = $taglist['errmsg'];
				$result['errcode'] = $taglist['errcode'];
				$result['taglist'][] = $item;
			}
		}

		return json_encode($result);
	}

	/**
	 * 根据标签名字获取标签信息
	 * @param $name 标签名字
	 * @param $fuzzy 是否为模糊查询，默认为模糊查询
	 */
	public function getTagByName($name, $fuzzy = TRUE) {
		if (empty($name)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Name is empty!', 'errcode' => -2, 'taglist' => array()));
		}

		$result = array('success' => FALSE, 'errmsg' => 'Query fails!', 'errcode' => -2, 'taglist' => array());
		$taglist = $this -> getTagList();
		$taglist = json_decode($taglist, TRUE);
		if (isset($taglist['taglist']) && is_array($taglist['taglist']) && count($taglist['taglist'])) {
			foreach ($taglist['taglist'] as $item) {
				if ($fuzzy) {
					if (stristr($item['tagname'], $name)) {
						$result['success'] = TRUE;
						$result['errmsg'] = $taglist['errmsg'];
						$result['errcode'] = $taglist['errcode'];
						$result['taglist'][] = $item;
					}
				} else {
					if ($item['tagname'] == $name) {
						$result['success'] = TRUE;
						$result['errmsg'] = $taglist['errmsg'];
						$result['errcode'] = $taglist['errcode'];
						$result['taglist'][] = $item;
					}
				}
			}
		}

		return json_encode($result);
	}

	/**
	 * 根据标签ID获取标签下的成员
	 * @param $tag_id 标签ID
	 */
	public function getTagUsers($tag_id) {
		if (intval($tag_id) < 1) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Tag_id must be greater than zero!', 'errcode' => -2, 'userlist' => array(), 'partylist' => array()));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/tag/get";
		$data = array('access_token' => $this -> token, 'tagid' => $tag_id);

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
			return json_encode(array('success' => FALSE, 'errmsg' => 'Query fails!', 'errcode' => -2, 'taglist' => array(), 'partylist' => array()));
		}
	}

	/**
	 * 向指定标签内添加成员或部门
	 * @param $tag_id 标签ID
	 * @param $userlist 企业成员ID列表，一维数组，注意：userlist、partylist不能同时为空
	 * @param $partylist 企业部门ID列表，一维数组，注意：userlist、partylist不能同时为空
	 */
	public function addUserToTag($tag_id, $userlist = array(), $partylist = array()) {
		if (intval($tag_id) < 1) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Tag_id must be greater than zero!', 'errcode' => -2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/tag/addtagusers?access_token={$this->token}";
		$data = array('tagid' => $tag_id);
		if (!empty($userlist) && is_array($userlist) && count($userlist)) {
			$data['userlist'] = $userlist;
		}
		if (!empty($partylist) && is_array($partylist) && count($partylist)) {
			$data['partylist'] = $partylist;
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
			return json_encode(array('success' => FALSE, 'errmsg' => 'Add user to tag fails!', 'errcode' => -2));
		}
	}

	/**
	 * 从指定标签中移除成员或部门
	 * @param $tag_id 标签ID
	 * @param $userlist 企业成员ID列表，一维数组，注意：userlist、partylist不能同时为空
	 * @param $partylist 企业部门ID列表，一维数组，注意：userlist、partylist不能同时为空
	 */
	public function deleteUserFromTag($tag_id, $userlist = array(), $partylist = array()) {
		if (intval($tag_id) < 1) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Tag_id must be greater than zero!', 'errcode' => -2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/tag/deltagusers?access_token={$this->token}";
		$data = array('tagid' => $tag_id);
		if (!empty($userlist) && is_array($userlist) && count($userlist)) {
			$data['userlist'] = $userlist;
		}
		if (!empty($partylist) && is_array($partylist) && count($partylist)) {
			$data['partylist'] = $partylist;
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
			return json_encode(array('success' => FALSE, 'errmsg' => 'Delete user from tag fails!', 'errcode' => -2));
		}
	}

	/**
	 * 创建标签
	 * @param $tag_name 标签名称，长度为1~64个字节，标签名不可与其他标签重名。
	 * @param $tag_id 标签id，整型，指定此参数时新增的标签会生成对应的标签id，不指定时则以目前最大的id自增。
	 */
	public function createTag($tag_name, $tag_id = 0) {
		if (empty($tag_name)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Tag_name is empty!', 'errcode' => -2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/tag/create?access_token={$this->token}";
		$data = array('tagname' => $tag_name);
		if (intval($tag_id) > 0) {
			$data['tagid'] = $tag_id;
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
			return json_encode(array('success' => FALSE, 'errmsg' => 'Create tag fails!', 'errcode' => -2));
		}
	}

	/**
	 * 更新标签名字
	 * @param $tag_id 标签ID
	 * @param $tag_name 标签名称，长度为1~64个字节，标签不可与其他标签重名。
	 */
	public function updateTag($tag_id, $tag_name) {
		if (intval($tag_id) < 1 || empty($tag_name)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Tag_name or tag_id is invalid!', 'errcode' => -2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/tag/update?access_token={$this->token}";
		$data = array('tagid' => $tag_id, 'tagname' => $tag_name);

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
			return json_encode(array('success' => FALSE, 'errmsg' => 'Update tag fails!', 'errcode' => -2));
		}
	}
	
	/**
	 * 根据标签ID删除标签
	 * @param $tag_id 标签ID
	 */
	public function deleteTag($tag_id) {
		if (intval($tag_id) < 1) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Tag_id must be greater than zero!', 'errcode' => -2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/tag/delete";
		$data = array('access_token' => $this->token, 'tagid' => $tag_id);

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
			return json_encode(array('success' => FALSE, 'errmsg' => 'Delete tag fails!', 'errcode' => -2));
		}
	}

}

/* End of file  */
