<?php
require_once (dirname(__FILE__) . '/Tools.php');
/**
 * 微信企业号
 * 素材管理
 * @author faith whh306318848@126.com
 * @createtime 2015-08-01 22:24:48
 */
class Material {
	private $token;
	private $tools;
	/**
	 * 素材类型，定义为类常量，方便使用
	 */
	/**
	 * 图片
	 */
	const MEDIA_TYPE_IMAGE = "image";
	/**
	 * 语音
	 */
	const MEDIA_TYPE_VOICE = "voice";
	/**
	 * 视频
	 */
	const MEDIA_TYPE_VIDEO = "video";
	/**
	 * 普通文件
	 */
	const MEDIA_TYPE_FILE = "file";
	/**
	 * 图文
	 */
	const MEDIA_TYPE_MPNEWS = "mpnews";

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
	 * 上传临时素材
	 * @param $type 媒体文件类型，分别有图片（image）、语音（voice）、视频（video），普通文件(file)
	 * @param $file_path 要上传文件的绝对路径
	 */
	public function uploadTemporaryMaterial($type, $file_path) {
		if (empty($type) || empty($file_path)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Type or file_path is empty!', 'errcode' => -2));
		}
		if (!file_exists($file_path)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'File_path is invalid!', 'errcode' => -2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/media/upload?access_token={$this -> token}&type={$type}";
		$data = array('media' => "@" . $file_path);
		$result = $this -> tools -> uploadFileByPost($url, $data);
		if ($result) {
			if (isset($result['errcode']) && intval($result['errcode']) != 0) {
				$result['success'] = FALSE;
				return json_encode($result);
			} else {
				$result['success'] = TRUE;
				return json_encode($result);
			}
		} else {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Upload temporary material fails!', 'errcode' => -2));
		}
	}

	/**
	 * 获取临时素材的下载地址
	 * @param $media_id 素材资源标识ID
	 * @return 返回带有素材下载地址地json字符串
	 */
	public function getTemporaryMaterial($media_id) {
		if (empty($media_id)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Media_id is empty!', 'errcode' => -2));
		}
		$url = "https://qyapi.weixin.qq.com/cgi-bin/media/get?access_token={$this->token}&media_id={$media_id}";

		return json_encode(array('success' => TRUE, 'errorcode' => 0, 'url' => $url));
	}

	/**
	 * 上传永久素材
	 * @param $agent_id 企业应用的id，整型。可在应用的设置页面查看
	 * @param $type 媒体文件类型，分别有图片（image）、语音（voice）、视频（video），普通文件(file)
	 * @param $file_path 要上传文件的绝对路径
	 */
	public function uploadPermanentMaterial($agent_id, $type, $file_path) {
		if (empty($type) || empty($file_path)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Type or file_path is empty!', 'errcode' => -2));
		}
		if (!file_exists($file_path)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'File_path is invalid!', 'errcode' => -2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/material/add_material?agentid={$agent_id}&access_token={$this -> token}&type={$type}";
		$data = array('media' => "@" . $file_path);
		$result = $this -> tools -> uploadFileByPost($url, $data);
		if ($result) {
			if ($result['errcode'] == 0) {
				$result['success'] = TRUE;
				return json_encode($result);
			} else {
				$result['success'] = FALSE;
				return json_encode($result);
			}
		} else {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Upload permanent material fails!', 'errcode' => -2));
		}
	}

	/**
	 * 获取永久素材
	 * @param $media_id 素材资源标识ID
	 * @param $agent_id 企业应用的id，整型。可在应用的设置页面查看
	 * @return 当素材为图文素材时，返回图文消息的json字符串，当素材为其他素材时，返回带有素材下载地址的json字符串
	 */
	public function getPermanentMaterial($media_id, $agent_id) {
		if (empty($media_id) || intval($agent_id) < 0) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Media_id or agentid is empty!', 'errcode' => -2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/material/get?access_token={$this->token}&media_id={$media_id}&agentid={$agent_id}";

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
			return json_encode(array('success' => TRUE, 'errorcode' => 0, 'url' => $url));
		}
	}

	/**
	 * 删除永久素材
	 * @param $media_id 素材资源标识ID
	 * @param $agent_id 企业应用的id，整型。可在应用的设置页面查看
	 */
	public function deletePermanentMaterial($media_id, $agent_id) {
		if (empty($media_id) || intval($agent_id) < 0) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Media_id or agentid is empty!', 'errcode' => -2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/material/del?access_token={$this->token}&agentid={$agent_id}&media_id={$media_id}";

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
			return json_encode(array('success' => FALSE, 'errmsg' => 'Delete permanent material fails!', 'errcode' => -2));
		}
	}

	/**
	 * 根据应用ID获取其下的素材总数以及每种类型素材的数目
	 * @param $agent_id 企业应用的id，整型。可在应用的设置页面查看
	 */
	public function getMaterialCount($agent_id) {
		if (intval($agent_id) < 0) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Agentid is empty!', 'errcode' => -2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/material/get_count?access_token={$this->token}&agentid={$agent_id}";

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
			return json_encode(array('success' => FALSE, 'errmsg' => 'Query fails!', 'errcode' => -2));
		}
	}

	/**
	 * 根据应用ID和素材类型获取素材素材列表
	 * @param $type 素材类型，取值范围为本类的类常量
	 * @param $agent_id 企业应用的id，整型。可在应用的设置页面查看
	 * @param $offset 从该类型素材的该偏移位置开始返回，0表示从第一个素材返回
	 * @param $count 返回素材的数量，取值在1到50之间
	 */
	public function getMaterialList($type, $agent_id, $offset, $count) {
		if (intval($agent_id) < 0 || empty($type)) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Agentid or type is empty!', 'errcode' => -2));
		}
		if (intval($offset) < 0) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Offset must be greater than zero!', 'errcode' => -2));
		}
		if (intval($count) < 1 || intval($count) > 50) {
			return json_encode(array('success' => FALSE, 'errmsg' => 'Count must be between 1 and 50!', 'errcode' => -2));
		}

		$url = "https://qyapi.weixin.qq.com/cgi-bin/material/batchget?access_token={$this->token}";
		$data = array('type'=>$type, 'agentid'=>$agent_id, 'offset'=>$offset, 'count'=>$count);
		
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
	 * 上传永久图文素材
	 * @param $agent_id 企业应用的id，整型。可在应用的设置页面查看
	 * @param $articles 图文消息,以二维数组方式，一个图文消息支持1到10个图文，图文消息有特殊结构，比较复杂，在这里不做赘述，可直接参考微信官方文档，网址：http://qydev.weixin.qq.com/wiki/index.php?title=%E4%B8%8A%E4%BC%A0%E6%B0%B8%E4%B9%85%E7%B4%A0%E6%9D%90
	 */
	/*
	 public function uploadPermanentGraphicMaterial($agent_id, $articles = array()) {
	 if (empty($agent_id) || empty($articles)) {
	 return json_encode(array('success' => FALSE, 'errmsg' => 'Agent_id or articles is empty!', 'errcode' => -2));
	 }

	 $url = "https://qyapi.weixin.qq.com/cgi-bin/material/add_mpnews?access_token={$this->token}";
	 $data = array('agentid' => $agent_id, 'mpnews' => array('articles' => $articles));
	 // echo json_encode($data);exit;
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
	 return json_encode(array('success' => FALSE, 'errmsg' => 'Upload permanent graphic material fails!', 'errcode' => -2));
	 }
	 }*/

}

/* End of file  */
