<?php
class M_LoginRequest{
	private $username;
	private $password;
	
	public function __construct($c_Username, $c_Password){
		$this->username = $c_Username;
		$this->password = $c_Password;
	}
	
	public function login(){
		$con = mysql_connect('localhost', 'root', 'root');
		mysql_query("set names utf8");
		mysql_select_db('db_album',$con);
		$sqlStr = "SELECT password, userID FROM table_UserInfo WHERE username = '".$this->username."'";
		$result = mysql_query($sqlStr, $con);
		if($row = mysql_fetch_row($result)){
			if($row[0] == md5($this->password)){
				$_SESSION[username] = $this->username;
				$_SESSION[userID] = $row[1];
				echo "<script>window.location.href='./main'</script>";
			}else{
				echo "<script>document.getElementById('hint').innerHTML = '".iconv("gbk", "utf-8", "账号或密码信息错误")."'</script>";
			}
		}else{
			echo "<script>document.getElementById('hint').innerHTML = '".iconv("gbk", "utf-8", "账号或密码信息错误")."'</script>";
		}
		mysql_free_result($result);
		mysql_close($con);
	}
}
?>