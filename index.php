<?php
header("Content-type: text/html; charset=utf-8");

require_once (dirname(__FILE__) . '/Department.php');
require_once (dirname(__FILE__) . '/CreateConnection.php');
require_once (dirname(__FILE__) . '/User.php');
require_once (dirname(__FILE__) . '/Tag.php');
require_once (dirname(__FILE__) . '/Material.php');
require_once (dirname(__FILE__) . '/SendMessage.php');

$corpid = "your corpid";
$corpsecret = "your corpsecret";

$createConnection = new CreateConnection();
$token = $createConnection -> getAccessToken($corpid, $corpsecret);

if ($token) {
	echo "Token:" . $token . "<br/>";
	echo "<pre>";
	//$department = new Department($token);
	//var_dump($department->getDepartmentList());
	//var_dump($department->createDepartment("测试部门"));
	//var_dump($department->updateDepartment(13, "测试部门3"));
	//var_dump($department->deleteDepartment(13));
	//var_dump($department->getDepartmentByID(3));
	//var_dump($department->getDepartmentsByName('生'));

	//$user = new User($token);
	//var_dump($user->getUserList(1, 1));
	//var_dump($user->getUserListDetails(1, 1));
	//var_dump($user->createUser('ewewew', '测试人员4', 1, FALSE, '44444@126.com'));
	//var_dump($user->inviteConcern('ewewew'));
	//var_dump($user->updateUser('ewewew', '测试人员', 1, '程序媛', '19009090808', 2, 'dadadas@112.com', FALSE, 2, FALSE, array('外号'=>'二货')));
	//var_dump($user->deleteUser('ewewew'));
	//var_dump($user->batchDeleteUsers(array('ewewew3', 'ewewew4')));
	//var_dump($user->getUserByID('xuyuanping'));
	//var_dump($user->getUserByName('吴昊骅'));

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
	//var_dump($material->uploadTemporaryMaterial(Material::MEDIA_TYPE_IMAGE, dirname( __FILE__ )."/b.png"));
	//var_dump($material->getTemporaryMaterial("1I_FViSA9hArOZWzKJ0idhTaGwjbYARNscDwfsESs-wfpeDgOCMAj7Av2VuUvTnt7T5aSf-XoaSlOVDi-_s2lFw"));
	//var_dump($material->uploadPermanentMaterial(4, Material::MEDIA_TYPE_IMAGE, dirname( __FILE__ )."/b.png"));
	//var_dump($material->getPermanentMaterial("2_AL2HYxmlBDgDV9UT8uoMWJOC-7hzIKOUZQ8Thcof-Eg8wxT6TkOpnxs1VXOJmqJw_G8xI4QZrJs1L-EB0khsQ", 4));
	//var_dump($material->deletePermanentMaterial("2_AL2HYxmlBDgDV9UT8uoMWJOC-7hzIKOUZQ8Thcof-Eg8wxT6TkOpnxs1VXOJmqJw_G8xI4QZrJs1L-EB0khsQ", 4));
	//var_dump($material->getMaterialCount(4));
	//var_dump($material->getMaterialList(Material::MEDIA_TYPE_VOICE, 4, 0, 50));
	

	//$sendMessage = new SendMessage($token);
	//var_dump($sendMessage->sendFile(4, "1KQuH3LMcYGIj3t0nM-i1wyZMo-TpULxFnkEoYpB2r15Th6P86R5EUnzATFSK_Uq0rB1yPX8IhwOglcqSbhUSgA"));
	//var_dump($sendMessage->sendText(4, "Holiday Request For Pony(\"http://xxxxx\")"));
	//var_dump($sendMessage->sendImage(4, "271UhK8-pLZ1gy1G5z4ccTUJKPrFn7iFD2GsPk8mt6d5vJTYVANmxHSzF1wkM_qB_C3ggzMKcZhZl-Nmpx0e8QQ"));
	//var_dump($sendMessage->sendVoice(4, "2pAg95-1xk2v-GK7veGVhz6Zoe8qZj20bu8ixRV6M9j_mdgrYMF16p0DTdSCOuASo1gjmOcgafj3Gd4LAEfzciw"));

	echo "</pre>";
} else {
	echo "Token 获取失败";
}
?>