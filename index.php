<?php
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=dievice-width, initial-scale=1" />
<link rel="stylesheet" type="text/css" href="albumCSS" />
<title>电子相册</title>
</head>

<body>
<div class="wrapper box">
	
	<div class="header entry">
	<?
		require_once("header.php");
	?>
	</div>

	<div class="nav entry">
		<ul class="menu">
		<li><a class="tag pink" href="./index" title="登陆" style="background-color:pink;">登陆</a></li>
		<li><a class="tag blue" href="./enroll" title="注册">注册</a></li>
		</ul>
	</div>

	<div class="entry">
		<h2>登录信息</h2><hr />
	</div>
	
	<div class="mainContent entry box center">

		<form class="form" method="post" action="<? echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
			
			<label class='pink' for="username">账号</label>
			<input name="username" type="text" />

			<label class='pink' for="password">密码</label>
			<input name="password" type="password" />

			<b id="hint"></b>
				
			<input name="submit" type="submit" value="登陆" />

		</form>
		
	</div>
	
	<div class="entry footer">
	<?
		require_once("footer.php");
	?>
	</div>
	
</div>
<?php
	require_once("loginModel.php");
	if($_POST['submit']){
		$loginRequest = new M_LoginRequest($_POST['username'], $_POST['password']);
		$loginRequest->login();
	}
?>
</body>
</html>
