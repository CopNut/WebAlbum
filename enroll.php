<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=dievice-width, initial-scale=1" />

<link rel="stylesheet" type="text/css" href="albumCSS" />
<title>电子相册 - 注册</title>
<script type="text/javascript" src="jquery-2.2.2.js"></script>
<script type="text/javascript">

function checkUsername(){
	var username = $("#username").val();
	if(username.length > 30 || username.length < 3){
		$("#usernameHint").text("*用户名长度应在3位到30位之间");
		return false;
	}else{
		if($.post("jsPostFunction.php",{functionID:"checkUsername",username:$("#username").val()},function(result){
			if(result == 1){
				$("#usernameHint").text("*该用户名已被注册");
				return false;
			}else{
				$("#usernameHint").text("*该用户名可用");
				return true;
			}
		})){
			return true;
		}else{
			return false;
		}
	}
}

function checkPassword(){
	var password = $("#password").val();
	var passwordConfirm = $("#confirm").val();
	if(password.length > 20 || password.length < 6){
		$("#passwordHint").text("*密码长度应在6位到20位之间");
		return false;
	}else{
		$("#passwordHint").text("*");
		if(passwordConfirm != password){
			$("#confirmHint").text("*两次输入的密码不一致");
			return false;
		}else{
			$("#confirmHint").text("*");
			return true;
		}
	}
}

function check(){
	if(checkUsername() && checkPassword()){
		$("#submit").removeAttr("disabled");
	}else{
		$("#submit").attr("disabled","disabled");
	}
}
</script>
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
		<li><a class="tag pink" href="./index" title="登陆">登陆</a></li>
		<li><a class="tag blue" href="./enroll" title="注册" style="background-color:lightblue">注册</a></li>
		</ul>
	</div>

	<div class="entry">
		<h2>注册信息</h2><hr />
	</div>

	<div class="mainContent entry box center">
	
		<form method="post" action="<? echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="form">
		
				<label class='blue' for="username">账号</label>
				<input type="text" name="username" id="username" onchange="checkUsername();check()"/>
				<b id="usernameHint">*</b>
			
				<label class='blue' for="password">密码</label>
				<input type="password" name="password" id="password" onchange="checkPassword();check()"/>
				<b id="passwordHint">*</b>
			
				<label class='blue' for="confirm">再次输入密码</label>
				<input type="password" name="confirm" id="confirm" onchange="checkPassword();check()"/>
				<b id="confirmHint">*</b>
			
				<input type="submit" disabled="disabled" value="注册" name="submit" id="submit"/>
			
			
		</form>
	
	</div>
	
	<div class="footer entry">
	<?
		require_once("footer.php");
	?>
	</div>
	
</div>
<?php
	require_once("enrollModel.php");
	if($_POST[submit]){
		$enrollRequest = new M_EnrollRequest($_POST[username], $_POST[password]);
		$enrollRequest->enroll();
	}else{
	
	}
?>
</body>
</html>
