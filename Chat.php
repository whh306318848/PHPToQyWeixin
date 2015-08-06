<?php
require_once (dirname(__FILE__) . '/Tools.php');
/**
 * 微信企业号
 * 会话管理
 * @author faith whh306318848@126.com
 * @createtime 2015-08-06 20:22:00
 */
class Chat {
	private $token;
	private $tools;
	/**
	 * 接收人类型，定义为类常量，方便使用
	 */
	 /**
	  * 单聊
	  */
	const RECEIVER_TYPE_SINGLE = "single";
	/**
	 * 群聊
	 */
	const RECEIVER_TYPE_GROUP = "group";
	
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
	 * 创建会话
	 * @param $chat_id 会话id。字符串类型，最长32个字符。只允许字符0-9及字母a-zA-Z, 如果值内容为64bit无符号整型：要求值范围在[1, 2^63)之间，[2^63, 2^64)为系统分配会话id区间
	 * @param $name 会话标题
	 * @param $owner 管理员userid，必须是该会话userlist的成员之一
	 * @param $user_list 会话成员列表，成员用userid来标识。一维数组
	 */
	public function createChat($chat_id, $name, $owner, $user_list) {
		if (empty($chat_id) || empty($name) || empty($owner) || empty($user_list)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Chat_id or name or owner or user_list is empty!', 'errcode' => -2));
		}
		if (!is_array($user_list)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'User_list must be array!', 'errcode' => -2));
		}
		if (!array_search($owner, $user_list)) {
			$user_list[] = $owner;
		}
		if (count($user_list) < 3 || count($user_list) > 1000) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'The number of users must be between 3 and 1000!', 'errcode' => -2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/chat/create?access_token={$this->token}";

		$data = array('chatid' => $chat_id, 'name' => $name, 'owner' => $owner, 'userlist' => $user_list);

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
			return json_encode(array('success' => FALSE, 'errmsg' => 'Create chat fails!', 'errcode' => -2));
		}
	}

	/**
	 * 根据会话ID获取会话信息
	 * @param $chat_id 会话ID
	 */
	public function getChat($chat_id) {
		if (empty($chat_id)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Chat_id is empty!', 'errcode' => -2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/chat/get";
		$data = array('access_token' => $this -> token, 'chatid' => $chat_id);

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
	 * 修改会话信息
	 * @param $chat_id 会话id
	 * @param $op_user 操作人userid
	 * @param $name 会话标题
	 * @param $owner 管理员userid，必须是该会话userlist的成员之一
	 * @param $add_user_list 会话新增成员列表，成员用userid来标识，一维数组
	 * @param $del_user_list 会话退出成员列表，成员用userid来标识，一维数组
	 */
	public function changeChat($chat_id, $op_user, $name = FALSE, $owner = FALSE, $add_user_list = array(), $del_user_list = array()) {
		if (empty($chat_id) || empty($op_user)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Chat_id or op_user is empty!', 'errcode' => -2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/chat/update?access_token={$this->token}";
		$data = array('chatid' => $chat_id, 'op_user' => $op_user);
		if (!empty($name)) {
			$data['name'] = $name;
		}
		if (!empty($owner)) {
			$data['owner'] = $owner;
		}
		if (!empty($add_user_list)) {
			$data['add_user_list'] = $add_user_list;
		}
		if (!empty($del_user_list)) {
			$data['del_user_list'] = $del_user_list;
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
			return json_encode(array('success' => FALSE, 'errmsg' => 'Change chat fails!', 'errcode' => -2));
		}
	}
	
	/**
	 * 退出会话
	 * @param $chat_id 会话id
	 * @param $op_user 操作人userid
	 */
	public function quitChat($chat_id, $op_user) {
		if (empty($chat_id) || empty($op_user)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Chat_id or op_user is empty!', 'errcode' => -2));
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/chat/quit?access_token={$this->token}";
		$data = array('chatid' => $chat_id, 'op_user' => $op_user);
		
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
			return json_encode(array('success' => FALSE, 'errmsg' => 'Quit chat fails!', 'errcode' => -2));
		}
	}
	
	/**
	 * 清楚会话未读状态
	 * @param $op_user 会话所有者的userid
	 * @param $type 会话类型：single|group，分别表示：单聊|群聊
	 * @param $id 会话值，为userid|chatid，分别表示：成员id|会话id
	 */
	public function clearNotify($op_user, $type, $id) {
		if (empty($op_user) || empty($id)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Op_user or id is empty!', 'errcode' => -2));
		}
		if ($type != Chat::RECEIVER_TYPE_SINGLE && $type != Chat::RECEIVER_TYPE_GROUP) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Type is invalid!', 'errcode' => -2));
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/chat/clearnotify?access_token={$this->token}";
		$data = array('op_user' => $op_user, 'chat'=>array('type'=>$type, 'id'=>$id));
		
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
			return json_encode(array('success' => FALSE, 'errmsg' => 'Quit chat fails!', 'errcode' => -2));
		}
	}
	
	/**
	 * 发送文字及表情消息
	 * @param $type 接收人类型：single|group，分别表示：单聊|群聊
	 * @param $id 接收人的值，为userid|chatid，分别表示：成员id|会话id
	 * @param $sender 发送人的userid
	 * @param $content 消息内容，表情内容可参看微信提供的对照表，下载地址：http://qydev.weixin.qq.com/download/wx-emoticon.xlsx
	 */
	public function sendText($type, $id, $sender, $content) {
		if (empty($id) || empty($content) || empty($sender)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Id or sender or content is empty!', 'errcode' => -2));
		}
		if ($type != Chat::RECEIVER_TYPE_SINGLE && $type != Chat::RECEIVER_TYPE_GROUP) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Type is invalid!', 'errcode' => -2));
		}

		
		$result = $this->sendMessage($type, $id, $sender, "text", $content);
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				return json_encode($result);
			} else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		} else {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Send text fails!', 'errcode' => -2));
		}
	}
	
	/**
	 * 发送图片消息
	 * @param $type 接收人类型：single|group，分别表示：单聊|群聊
	 * @param $id 接收人的值，为userid|chatid，分别表示：成员id|会话id
	 * @param $sender 发送人的userid
	 * @param $media_id 图片媒体文件id，可以调用上传素材文件接口获取 
	 */
	public function sendImage($type, $id, $sender, $media_id) {
		if (empty($id) || empty($media_id) || empty($sender)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Id or sender or content is empty!', 'errcode' => -2));
		}
		if ($type != Chat::RECEIVER_TYPE_SINGLE && $type != Chat::RECEIVER_TYPE_GROUP) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Type is invalid!', 'errcode' => -2));
		}

		
		$result = $this->sendMessage($type, $id, $sender, "image", $media_id);
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				return json_encode($result);
			} else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		} else {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Send image fails!', 'errcode' => -2));
		}
	}
	
	/**
	 * 发送文件消息
	 * @param $type 接收人类型：single|group，分别表示：单聊|群聊
	 * @param $id 接收人的值，为userid|chatid，分别表示：成员id|会话id
	 * @param $sender 发送人的userid
	 * @param $media_id 图片媒体文件id，可以调用上传素材文件接口获取 
	 */
	public function sendFile($type, $id, $sender, $media_id) {
		if (empty($id) || empty($media_id) || empty($sender)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Id or sender or content is empty!', 'errcode' => -2));
		}
		if ($type != Chat::RECEIVER_TYPE_SINGLE && $type != Chat::RECEIVER_TYPE_GROUP) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Type is invalid!', 'errcode' => -2));
		}

		
		$result = $this->sendMessage($type, $id, $sender, "file", $media_id);
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				return json_encode($result);
			} else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		} else {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Send file fails!', 'errcode' => -2));
		}
	}
	
	/**
	 * 设置成员新消息免打扰
	 * @param $user_mute_list 成员新消息免打扰参数，二维数组，最大支持10000个成员，具体格式参考微信API，地址：http://qydev.weixin.qq.com/wiki/index.php?title=%E4%BC%81%E4%B8%9A%E5%8F%B7%E6%B6%88%E6%81%AF%E6%8E%A5%E5%8F%A3%E8%AF%B4%E6%98%8E#.E5.8F.91.E6.B6.88.E6.81.AF
	 */
	public function setMute($user_mute_list) {
		if(empty($user_mute_list)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'User_mute_list is empty!', 'errcode' => -2));
		}
		if (!is_array($user_mute_list)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'User_mute_list must be array!', 'errcode' => -2));
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/chat/setmute?access_token={$this->token}";
		$data = array('user_mute_list'=>$user_mute_list);
		
		$result = $this->tools->httpRequest($url, $data, 'post');
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				return json_encode($result);
			} else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		} else {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Set mute fails!', 'errcode' => -2));
		}
	}
	
	/**
	 * 发送消息
	 * @param $type 接收人类型：single|group，分别表示：单聊|群聊
	 * @param $id 接收人的值，为userid|chatid，分别表示：成员id|会话id
	 * @param $sender 发送人的userid
	 * @param $msgtype 消息类型 ，为text|image|file
	 * @param $content_or_media_id 消息内容，消息类型为text时，该值为文本，消息类型为image或file时，为素材ID
	 */
	private function sendMessage($type, $id, $sender, $msgtype, $content_or_media_id) {
		if (empty($id) || empty($sender) || empty($content_or_media_id) || empty($msgtype)) {
			return FALSE;
		}
		if ($type != Chat::RECEIVER_TYPE_SINGLE && $type != Chat::RECEIVER_TYPE_GROUP) {
			return FALSE;
		}
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/chat/send?access_token={$this->token}";
		$data = array('receiver'=>array('type'=>$type, 'id'=>$id), 'sender'=>$sender, 'msgtype'=>$msgtype);
		
		switch ($msgtype) {
			case 'text':
				$data['text'] = array('content'=>$content_or_media_id);
				break;
			case 'image':
				$data['image'] = array('media_id'=>$content_or_media_id);
				break;
			case 'file':
				$data['file'] = array('media_id'=>$content_or_media_id);
				break;
			default:
				return FALSE;
				break;
		}
		
		return $this -> tools -> httpRequest($url, $data, 'post');
	}

}

/* End of file  */
