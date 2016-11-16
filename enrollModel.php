<?php
class M_EnrollRequest{
	private $username;
	private $password;
	
	public function __construct($c_username, $c_password){
		$this->username = $c_username;
		$this->hash = md5($c_password);
	}
	
	public function enroll(){
		$con = mysql_connect('localhost', 'root', 'root');
		mysql_query("set names utf8");
		mysql_select_db('db_album',$con);

		// 注册信息录入用户信息库
		$sqlStr = "INSERT INTO table_UserInfo (username, password) VALUE ('$this->username', '$this->hash')";
		$result = mysql_query($sqlStr, $con);
		mysql_free_result($result);

		// 取出userID为创建用户默认相册做准备
		$sqlStr = "SELECT userID FROM table_userinfo WHERE username = '$this->username'";
		$result = mysql_query($sqlStr, $con);
		$row = mysql_fetch_row($result);
		$userID = $row[0];
		mysql_free_result($result);

		// 创建用户默认相册
		$sqlStr = "INSERT INTO table_personalalbum (userID, albumname) VALUES ('$userID', '默认相册')";
		$result = mysql_query($sqlStr, $con);
		mysql_free_result($result);
		mysql_close($con);
		$path = iconv('UTF-8', 'GBK', "./photo/".$this->username);
		if(!is_dir($path)){
			mkdir($path);
			mkdir(iconv('UTF-8', 'GBK', $path."/默认相册"));
		}
		echo "<script>alert('注册成功！');</script>";
		echo "<script>window.location.href='./';</script>";
	}
}
?>