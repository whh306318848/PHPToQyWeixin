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
	//echo "Token:".$token."<br/>";
	//$department = new Department($token);
	//var_dump($department->getDepartmentList());
	$user = new User($token);
	var_dump($user->getUserList(1, 1));
}else {
	echo "Token 获取失败";
}

?>