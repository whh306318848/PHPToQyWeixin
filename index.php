<?php
header("Content-type: text/html; charset=utf-8");

require_once (dirname( __FILE__ ).'/Department.php');
require_once (dirname( __FILE__ ).'/CreateConnection.php');
require_once (dirname( __FILE__ ).'/User.php');

$corpid = "wx43a11212ca72cdf9";
$corpsecret = "S3EapOPJqlJJCSBzpUkYDXlG5gch59YyCYu70PvRp1yPx9RUxp6zEW6CeJSv4brK";

$createConnection = new CreateConnection();
$token = $createConnection->getAccessToken($corpid, $corpsecret);

if ($token) {
	echo "Token:".$token."<br/>";
	
	//$department = new Department($token);
	//var_dump($department->getDepartmentList());
	//var_dump($department->createDepartment("测试部门"));
	//var_dump($department->updateDepartment(13, "测试部门3"));
	//var_dump($department->deleteDepartment(13));
	//var_dump($department->getDepartmentByID(3));
	//var_dump($department->getDepartmentsByName('生'));
	
	$user = new User($token);
	// var_dump($user->getUserList(1, 1));
	//var_dump($user->createUser('ewewew', '测试人员1', 1, FALSE, 'whh306318848@126.com'));
	var_dump($user->inviteConcern('ewewew'));
	
}else {
	echo "Token 获取失败";
}

?>