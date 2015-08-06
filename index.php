<?php
header("Content-type: text/html; charset=utf-8");

require_once (dirname(__FILE__) . '/Department.php');
require_once (dirname(__FILE__) . '/CreateConnection.php');
require_once (dirname(__FILE__) . '/User.php');
require_once (dirname(__FILE__) . '/Tag.php');
require_once (dirname(__FILE__) . '/Material.php');
require_once (dirname(__FILE__) . '/SendMessage.php');
require_once (dirname(__FILE__) . '/Agent.php');
require_once (dirname(__FILE__) . '/Menu.php');
require_once (dirname(__FILE__) . '/Chat.php');

$corpid = "your corpid";
$corpsecret = "your corpsecret";

$createConnection = new CreateConnection();
$token = $createConnection -> getAccessToken($corpid, $corpsecret);

if ($token) {
	echo "Token:" . $token . "<br/>";
	echo "<pre>";
	//$department = new Department($token);
	//var_dump($department->getDepartmentList());
	//var_dump($department->createDepartment("合作伙伴"));
	//var_dump($department->updateDepartment(13, "测试部门3"));
	//var_dump($department->deleteDepartment(13));
	//var_dump($department->getDepartmentByID(3));
	//var_dump($department->getDepartmentsByName('生'));

	//$user = new User($token);
	//var_dump($user->getUserList(1, 1));
	//var_dump($user->getUserListDetails(1, 1));
	//var_dump($user->createUser('xxxx', 'xxx', 8, FALSE, 'xxx@qq.com', 'xxxx', FALSE, 1));
	//var_dump($user->inviteConcern('ewewew'));
	//var_dump($user->updateUser('ewewew', '测试人员', 1, '程序媛', '19009090808', 2, 'dadadas@112.com', FALSE, 2, FALSE, array('外号'=>'二货')));
	//var_dump($user->deleteUser('ewewew'));
	//var_dump($user->batchDeleteUsers(array('ewewew3', 'ewewew4')));
	//var_dump($user->getUserByID('xxx'));
	//var_dump($user->getUserByName('xxx'));

	//$tag = new Tag($token);
	//var_dump($tag->getTagList());
	//var_dump($tag->getTagByID(1));
	//var_dump($tag->getTagByName("测试", FALSE));
	//var_dump($tag->getTagUsers(1));
	//var_dump($tag->addUserToTag(1, array('ewewew', '111'), array(1, 8, 9)));
	//var_dump($tag->deleteUserFromTag(1, array('ewewew', 'chenguanxu'), array(1, 9, 999)));
	//var_dump($tag->createTag('测试3', 3));
	//var_dump($tag->updateTag(3, '测试5'));
	//var_dump($tag->deleteTag(3));

	//$material = new Material($token);
	//var_dump($material->uploadTemporaryMaterial(Material::MEDIA_TYPE_FILE, dirname( __FILE__ )."/a.txt"));
	//var_dump($material->getTemporaryMaterial("1I_FViSA9hArOZWzKJ0idhTaGwjbYARNscDwfsESs-wfpeDgOCMAj7Av2VuUvTnt7T5aSf-XoaSlOVDi-_s2lFw"));
	//var_dump($material->uploadPermanentMaterial(4, Material::MEDIA_TYPE_IMAGE, dirname( __FILE__ )."/b.png"));
	//var_dump($material->getPermanentMaterial("2_AL2HYxmlBDgDV9UT8uoMWJOC-7hzIKOUZQ8Thcof-Eg8wxT6TkOpnxs1VXOJmqJw_G8xI4QZrJs1L-EB0khsQ", 4));
	//var_dump($material->deletePermanentMaterial("2_AL2HYxmlBDgDV9UT8uoMWJOC-7hzIKOUZQ8Thcof-Eg8wxT6TkOpnxs1VXOJmqJw_G8xI4QZrJs1L-EB0khsQ", 4));
	//var_dump($material->getMaterialCount(4));
	//var_dump($material->getMaterialList(Material::MEDIA_TYPE_MPNEWS, 4, 0, 50));

	//$sendMessage = new SendMessage($token);
	//var_dump($sendMessage->sendFile(4, "1KQuH3LMcYGIj3t0nM-i1wyZMo-TpULxFnkEoYpB2r15Th6P86R5EUnzATFSK_Uq0rB1yPX8IhwOglcqSbhUSgA"));
	//var_dump($sendMessage->sendText(4, "Holiday Request For Pony(\"http://xxxxx\")"));
	//var_dump($sendMessage->sendImage(4, "271UhK8-pLZ1gy1G5z4ccTUJKPrFn7iFD2GsPk8mt6d5vJTYVANmxHSzF1wkM_qB_C3ggzMKcZhZl-Nmpx0e8QQ"));
	//var_dump($sendMessage->sendVoice(4, "2pAg95-1xk2v-GK7veGVhz6Zoe8qZj20bu8ixRV6M9j_mdgrYMF16p0DTdSCOuASo1gjmOcgafj3Gd4LAEfzciw"));
	//var_dump($sendMessage->sendVideo(4, "2hHlbQB5q0fr8z-hF8KyyPYiSZAMizYmeezRkpKxK4uu5fF0endeI81_2dfyRwBW4Y4oPP4fJqZTXPN3KCo9pwg", "测试视频接口", "我就是测试一下视频发送接口"));
	//var_dump($sendMessage->sendNews(4, array(array('title'=>'我用爬虫一天时间“偷了”知乎一百万用户，只为证明PHP是世界上最好的语言', 'description'=>'来源： 爱编程', 'url'=>'http://www.w2bc.com/Article/54923'))));
	//var_dump($sendMessage->sendMpnewsByMediaID(4, "2N53V20kxM_83rjCKTsncW-0WhBLRQsPVxXhtElFyUGXJGSWk0FwKSw-IeV56srH3"));
	//var_dump($sendMessage->sendMpnewsByContent(4, array(array('title'=>'测试消息1', 'thumb_media_id'=>'271UhK8-pLZ1gy1G5z4ccTUJKPrFn7iFD2GsPk8mt6d5vJTYVANmxHSzF1wkM_qB_C3ggzMKcZhZl-Nmpx0e8QQ', 'author'=>'faith', 'content_source_url'=>'http://www.youarebug.com', 'content'=>'测试消息1', 'digest'=>'测试信息，你信吗？', 'show_cover_pic'=>1), array('title'=>'测试消息2', 'thumb_media_id'=>'271UhK8-pLZ1gy1G5z4ccTUJKPrFn7iFD2GsPk8mt6d5vJTYVANmxHSzF1wkM_qB_C3ggzMKcZhZl-Nmpx0e8QQ', 'author'=>'faith', 'content_source_url'=>'http://www.aiurbia.net', 'content'=>'测试消息2', 'digest'=>'测试信息，你信吗？', 'show_cover_pic'=>1))));
	
	//$agent = new Agent($token);
	//var_dump($agent->getAgentByID(4));
	//var_dump($agent->setAgentByID(4, "测试办公", "测试办公", FALSE, FALSE, 1, 1, 1));
	//var_dump($agent->getAgentList());
	//var_dump($agent->getAgentByName("测试办公", FALSE));

	//$menu = new Menu($token);
	/*
	$button_list = array( 
		array(
			'name' => '点击1', 
			'sub_button' => array( 
				array(
					'type' => 'click', 
					'name' => '点击推事件', 
					'key' => 'CLICK1001'
				), 
				array(
					'type' => 'view', 
					'name' => '跳转URL', 
					'url' => 'http://www.aiurbia.net'
				)
			)
		), 
		array(
			'name' => '扫码1', 
			'sub_button' => array(
				array(
					'type'=>'scancode_push',
					'name'=>'扫码推事件',
					'key'=>'rselfmenu_0_1'
				),
				array(
					'type'=>'scancode_waitmsg',
					'name'=>'扫码带提示',
					'key'=>'rselfmenu_0_0'
				)
			)
		),
		array(
			'name' => '弹出1',
			'sub_button' => array(
				array(
					'type'=>'pic_sysphoto',
					'name'=>'系统拍照发图',
					'key'=>'rselfmenu_1_0'
				),
				array(
					'type'=>'pic_photo_or_album',
					'name'=>'拍照或者相册发图',
					'key'=>'rselfmenu_1_1'
				),
				array(
					'type'=>'pic_weixin',
					'name'=>'微信相册发图',
					'key'=>'rselfmenu_1_2'
				),
				array(
					'type'=>'location_select',
					'name'=>'发送位置',
					'key'=>'rselfmenu_2_0'
				)
			)
		)
	);
	var_dump($menu -> createMenu(4, $button_list));
	*/
	//var_dump($menu->deleteMenu(4));
	//var_dump($menu->getMenu(4));
	
	//$chat = new Chat($token);
	//var_dump($chat->createChat("dsdsds", "测试会话1", "xxx", array('ewewew', 'ewewew1')));
	//var_dump($chat->getChat("dsdsds"));
	//var_dump($chat->changeChat("dsdsds", "xxx", "测试会话2", "ewewew", array('fuchuan'), array('ewewew1')));
	//var_dump($chat->changeChat("dsdsds", "ewewew"));
	//var_dump($chat->clearNotify("wuhaohua", Chat::RECEIVER_TYPE_GROUP, "dsdsds"));
	//var_dump($chat->sendText(Chat::RECEIVER_TYPE_GROUP, "dsdsds", "xxx", "Hi, everybody/:8-)"));
	//var_dump($chat->sendImage(Chat::RECEIVER_TYPE_GROUP, "dsdsds", "xxx", "1eqbIpMvqX3FcrROuQJQNqSMlPibmaS0AReH7tySu39Y4g_-mgHXRQQ3-YGf-wWdnRFmGhPoW9HZxBQTltMCHLg"));
	//var_dump($chat->sendFile(Chat::RECEIVER_TYPE_GROUP, "dsdsds", "xxx", "1n3K1KyX9dEkjxmCqzZY2apU2RZXIXG-eDjQWoxRd27hUHhurPrGFSDj3Qn3Dq-q_74JqawiIQDFQ5XhdRzHvJg"));
	//var_dump($chat->setMute(array(array('userid'=>'ewewew1', 'status'=>0), array('userid'=>'ewewew', 'status'=>1))));

	echo "</pre>";
} else {
	echo "Token 获取失败";
}
?>