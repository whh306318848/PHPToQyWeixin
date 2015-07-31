<?php
header("Content-type: text/html; charset=utf-8");

require_once (dirname( __FILE__ ).'/Department.php');
require_once (dirname( __FILE__ ).'/CreateConnection.php');
require_once (dirname( __FILE__ ).'/User.php');
require_once (dirname( __FILE__ ).'/Tag.php');

$corpid = "wx43a11212ca72cdf9";
$corpsecret = "S3EapOPJqlJJCSBzpUkYDXlG5gch59YyCYu70PvRp1yPx9RUxp6zEW6CeJSv4brK";

$createConnection = new CreateConnection();
$token = $createConnection->getAccessToken($corpid, $corpsecret);

if ($token) {
	echo "Token:".$token."<br/>";
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
	
	$tag = new Tag($token);
	//var_dump($tag->getTagList());
	//var_dump($tag->getTagByID(1));
	//var_dump($tag->getTagByName("测试", FALSE));
	//var_dump($tag->getTagUsers(1));
	//var_dump($tag->addUserToTag(1, array('ewewew', '111'), array(1, 8, 9)));
	//var_dump($tag->deleteUserFromTag(1, array('ewewew', 'chenguanxu'), array(1, 9, 999)));
	//var_dump($tag->createTag('测试3', 3));
	//var_dump($tag->updateTag(3, '测试5'));
	//var_dump($tag->deleteTag(3));
	
	echo "</pre>";
}else {
	echo "Token 获取失败";
}

?>