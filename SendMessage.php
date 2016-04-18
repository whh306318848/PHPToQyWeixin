<?php
require_once (dirname(__FILE__) . '/Tools.php');
require_once (dirname(__FILE__) . '/Material.php');
/**
 * 微信企业号
 * 发送消息
 * @author faith whh306318848@126.com
 * @createtime 2015-08-02 00:57:49
 */
class SendMessage {
	private $token;
	private $tools;
	private $url;
	public function __construct($token) {
		$this -> token = $token;
		$this -> tools = new Tools();
		$this -> url = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token={$this->token}";
	}

	/**
	 * 设置token
	 */
	public function setToken($token = NULL) {
		$this -> token = $token;
		$this -> url = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token={$this->token}";
	}

	/**
	 * 获取token
	 */
	public function getToken() {
		return $this -> token;
	}

	/**
	 * 发送文字消息
	 * @param $agent_id 企业应用的id，整型。可在应用的设置页面查看
	 * @param $content 消息内容
	 * @param $to_user 成员ID列表，一维数组传递['1', '2', ...]，最多1000个，默认发送给全部成员
	 * @param $to_party 部门ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $to_tag 标签ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $safe 表示是否是保密消息，0表示否，1表示是，默认0
	 */
	public function sendText($agent_id, $content, $to_user = "@all", $to_party = array(), $to_tag = array(), $safe = 0) {
		if (intval($agent_id) < 0 || empty($content)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Agent_id or content is empty!', 'errcode' => -2));
		}

		$data = array('agentid' => $agent_id, 'msgtype' => "text", 'text' => array('content' => $content));
		$data['touser'] = $this->getToUserList($to_user);
		if (($tmp = $this->getToList($to_party)) != FALSE) {
			$data['toparty'] = $tmp;
		}
		if (($tmp = $this->getToList($to_tag)) != FALSE) {
			$data['totag'] = $tmp;
		}
		if (intval($safe) == 1) {
			$data['safe'] = 1;
		}

		$result = $this -> tools -> httpRequest($this -> url, $data, 'post');
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
	 * 发送图片
	 * @param $agent_id 企业应用的id，整型。可在应用的设置页面查看
	 * @param $media_id 媒体文件id，可以调用上传临时素材或者永久素材接口获取
	 * @param $to_user 成员ID列表，一维数组传递['1', '2', ...]，最多1000个，默认发送给全部成员
	 * @param $to_party 部门ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $to_tag 标签ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $safe 表示是否是保密消息，0表示否，1表示是，默认0
	 */
	public function sendImage($agent_id, $media_id, $to_user = "@all", $to_party = array(), $to_tag = array(), $safe = 0) {
		if (intval($agent_id) < 0 || empty($media_id)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Agent_id or media_id is empty!', 'errcode' => -2));
		}

		$result = $this -> sendImageVoiceFileMpnews($agent_id, $media_id, Material::MEDIA_TYPE_IMAGE, $to_user, $to_party, $to_tag, $safe);
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
	 * 发送语音
	 * @param $agent_id 企业应用的id，整型。可在应用的设置页面查看
	 * @param $media_id 媒体文件id，可以调用上传临时素材或者永久素材接口获取
	 * @param $to_user 成员ID列表，一维数组传递['1', '2', ...]，最多1000个，默认发送给全部成员
	 * @param $to_party 部门ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $to_tag 标签ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $safe 表示是否是保密消息，0表示否，1表示是，默认0
	 */
	public function sendVoice($agent_id, $media_id, $to_user = "@all", $to_party = array(), $to_tag = array(), $safe = 0) {
		if (intval($agent_id) < 0 || empty($media_id)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Agent_id or media_id is empty!', 'errcode' => -2));
		}

		$result = $this -> sendImageVoiceFileMpnews($agent_id, $media_id, Material::MEDIA_TYPE_VOICE, $to_user, $to_party, $to_tag, $safe);
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				return json_encode($result);
			} else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		} else {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Send voice fails!', 'errcode' => -2));
		}
	}

	/**
	 * 发送视频
	 * @param $agent_id 企业应用的id，整型。可在应用的设置页面查看
	 * @param $media_id 媒体文件id，可以调用上传临时素材或者永久素材接口获取
	 * @param $title 视频消息的标题
	 * @param $description 视频消息的描述
	 * @param $to_user 成员ID列表，一维数组传递['1', '2', ...]，最多1000个，默认发送给全部成员
	 * @param $to_party 部门ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $to_tag 标签ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $safe 表示是否是保密消息，0表示否，1表示是，默认0
	 */
	public function sendVideo($agent_id, $media_id, $title = FALSE, $description = FALSE, $to_user = "@all", $to_party = array(), $to_tag = array(), $safe = 0) {
		if (intval($agent_id) < 0 || empty($media_id)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Agent_id or media_id is empty!', 'errcode' => -2));
		}

		$data = array('agentid' => $agent_id, 'msgtype' => "video", 'video' => array('media_id' => $media_id));
		if (!empty($title)) {
			$data['video']['title'] = $title;
		}
		if (!empty($description)) {
			$data['video']['description'] = $description;
		}
		$data['touser'] = $this->getToUserList($to_user);
		if (($tmp = $this->getToList($to_party)) != FALSE) {
			$data['toparty'] = $tmp;
		}
		if (($tmp = $this->getToList($to_tag)) != FALSE) {
			$data['totag'] = $tmp;
		}
		if (intval($safe) == 1) {
			$data['safe'] = 1;
		}

		$result = $this -> tools -> httpRequest($this -> url, $data, 'post');
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
	 * 发送文件
	 * @param $agent_id 企业应用的id，整型。可在应用的设置页面查看
	 * @param $media_id 媒体文件id，可以调用上传临时素材或者永久素材接口获取
	 * @param $to_user 成员ID列表，一维数组传递['1', '2', ...]，最多1000个，默认发送给全部成员
	 * @param $to_party 部门ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $to_tag 标签ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $safe 表示是否是保密消息，0表示否，1表示是，默认0
	 */
	public function sendFile($agent_id, $media_id, $to_user = "@all", $to_party = array(), $to_tag = array(), $safe = 0) {
		if (intval($agent_id) < 0 || empty($media_id)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Agent_id or media_id is empty!', 'errcode' => -2));
		}

		$result = $this -> sendImageVoiceFileMpnews($agent_id, $media_id, Material::MEDIA_TYPE_FILE, $to_user, $to_party, $to_tag, $safe);
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
	 * 发送news信息
	 * @param $agent_id 企业应用的id，整型。可在应用的设置页面查看
	 * @param $articles 图文消息，一个图文消息支持1到10条图文，格式参考微信官方文档：http://qydev.weixin.qq.com/wiki/index.php?title=%E6%B6%88%E6%81%AF%E7%B1%BB%E5%9E%8B%E5%8F%8A%E6%95%B0%E6%8D%AE%E6%A0%BC%E5%BC%8F
	 * @param $to_user 成员ID列表，一维数组传递['1', '2', ...]，最多1000个，默认发送给全部成员
	 * @param $to_party 部门ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $to_tag 标签ID列表，一维数组传递['1', '2', ...]，最多1000个
	 */
	public function sendNews($agent_id, $articles, $to_user = "@all", $to_party = array(), $to_tag = array()) {
		if (intval($agent_id) < 0) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Agent_id must be greater than zero!', 'errcode' => -2));
		}
		if (empty($articles) || !is_array($articles)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Articles is invalid!', 'errcode' => -2));
		} 

		$data = array('agentid' => $agent_id, 'msgtype' => "news", 'news' => array('articles' => $articles));
		$data['touser'] = $this->getToUserList($to_user);
		if (($tmp = $this->getToList($to_party)) != FALSE) {
			$data['toparty'] = $tmp;
		}
		if (($tmp = $this->getToList($to_tag)) != FALSE) {
			$data['totag'] = $tmp;
		}

		$result = $this -> tools -> httpRequest($this -> url, $data, 'post');
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
	 * 通过media_id发送图文消息
	 * @param $agent_id 企业应用的id，整型。可在应用的设置页面查看
	 * @param $media_id 媒体文件id，可以调用上传临时素材或者永久素材接口获取
	 * @param $to_user 成员ID列表，一维数组传递['1', '2', ...]，最多1000个，默认发送给全部成员
	 * @param $to_party 部门ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $to_tag 标签ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $safe 表示是否是保密消息，0表示否，1表示是，默认0
	 */
	public function sendMpnewsByMediaID($agent_id, $media_id, $to_user = "@all", $to_party = array(), $to_tag = array(), $safe = 0) {
		if (intval($agent_id) < 0 || empty($media_id)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Agent_id or media_id is empty!', 'errcode' => -2));
		}

		$result = $this -> sendImageVoiceFileMpnews($agent_id, $media_id, Material::MEDIA_TYPE_MPNEWS, $to_user, $to_party, $to_tag, $safe);
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				return json_encode($result);
			} else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		} else {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Send mpnews fails!', 'errcode' => -2));
		}
	}
	
	/**
	 * 通过图文消息的内容发送图文消息
	 * @param $agent_id 企业应用的id，整型。可在应用的设置页面查看
	 * @param $articles 图文消息，一个图文消息支持1到10个图文，格式参考微信官方文档：http://qydev.weixin.qq.com/wiki/index.php?title=%E6%B6%88%E6%81%AF%E7%B1%BB%E5%9E%8B%E5%8F%8A%E6%95%B0%E6%8D%AE%E6%A0%BC%E5%BC%8F
	 * @param $to_user 成员ID列表，一维数组传递['1', '2', ...]，最多1000个，默认发送给全部成员
	 * @param $to_party 部门ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $to_tag 标签ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $safe 表示是否是保密消息，0表示否，1表示是，默认0
	 */
	public function sendMpnewsByContent($agent_id, $articles, $to_user = "@all", $to_party = array(), $to_tag = array(), $safe = 0) {
		if (intval($agent_id) < 0) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Agent_id must be greater than zero!', 'errcode' => -2));
		}
		if (empty($articles) || !is_array($articles)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Articles is invalid!', 'errcode' => -2));
		} 

		$data = array('agentid' => $agent_id, 'msgtype' => "mpnews", 'mpnews' => array('articles' => $articles));
		$data['touser'] = $this->getToUserList($to_user);
		if (($tmp = $this->getToList($to_party)) != FALSE) {
			$data['toparty'] = $tmp;
		}
		if (($tmp = $this->getToList($to_tag)) != FALSE) {
			$data['totag'] = $tmp;
		}
		if (intval($safe) == 1) {
			$data['safe'] = 1;
		}

		$result = $this -> tools -> httpRequest($this -> url, $data, 'post');
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
	 * 发送图片、文件、音频、图文消息
	 * @param $agent_id 企业应用的id，整型。可在应用的设置页面查看
	 * @param $media_id 媒体文件id，可以调用上传临时素材或者永久素材接口获取
	 * @param $msg_type 消息类型，取值范围为Material类中的类常量
	 * @param $to_user 成员ID列表，一维数组传递['1', '2', ...]，最多1000个，默认发送给全部成员
	 * @param $to_party 部门ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $to_tag 标签ID列表，一维数组传递['1', '2', ...]，最多1000个
	 * @param $safe 表示是否是保密消息，0表示否，1表示是，默认0
	 */
	private function sendImageVoiceFileMpnews($agent_id, $media_id, $msg_type, $to_user = "@all", $to_party = array(), $to_tag = array(), $safe = 0) {
		if (intval($agent_id) < 0 || empty($media_id) || empty($msg_type)) {
			return FALSE;
		}

		$data = array('agentid' => $agent_id);
		switch ($msg_type) {
			case Material::MEDIA_TYPE_FILE :
				$data['file'] = array('media_id' => $media_id);
				break;
			case Material::MEDIA_TYPE_IMAGE :
				$data['image'] = array('media_id' => $media_id);
				break;
			case Material::MEDIA_TYPE_VOICE :
				$data['voice'] = array('media_id' => $media_id);
				break;
			case Material::MEDIA_TYPE_MPNEWS:
				$data['mpnews'] = array('media_id'=> $media_id);
				break;
			default :
				return FALSE;
				break;
		}
		$data['msgtype'] = $msg_type;
		$data['touser'] = $this->getToUserList($to_user);
		if (($tmp = $this->getToList($to_party)) != FALSE) {
			$data['toparty'] = $tmp;
		}
		if (($tmp = $this->getToList($to_tag)) != FALSE) {
			$data['totag'] = $tmp;
		}
		if (intval($safe) == 1) {
			$data['safe'] = 1;
		}

		$result = $this -> tools -> httpRequest($this -> url, $data, 'post');
		return $result;
	}
	
	/**
	 * 根据成员ID列表返回微信需要的成员列表格式字符串
	 * @param $to_user 成员ID列表，一维数组传递['1', '2', ...]，最多1000个，默认发送给全部成员
	 */
	private function getToUserList($to_user = "@all") {
		$data = "";
		if (is_array($to_user)) {
			$first = TRUE;
			foreach ($to_user as $value) {
				if ($first) {
					$data = $value;
					$first = FALSE;
				} else {
					$data .= "|" . $value;
				}
			}
		} else {
			$data = $to_user;
		}
		return $data;
	}
	
	/**
	 * 根据传入的数组，返回微信需要的列表格式字符串
	 * @param $to_list 一维数组传递['1', '2', ...]，最多1000个
	 */
	private function getToList($to_list = array()) {
		$data = "";
		if (!empty($to_list) && is_array($to_list)) {
			$first = TRUE;
			foreach ($to_list as $value) {
				if ($first) {
					$data = $value;
					$first = FALSE;
				} else {
					$data .= "|" . $value;
				}
			}
		}else {
			$data = FALSE;
		}
		return $data;
	}

}

/* End of file  */
