<?php
	session_start();
	if(!$_SESSION[userID]){
		echo "<script>window.location.href='./'</script>";	
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=dievice-width, initial-scale=1" />
<link rel="stylesheet" type="text/css" href="albumCSS" />
<title>电子相册 - 主页</title>
<script type="text/javascript" src="jquery-2.2.2.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.commentSubmit').click(function(){
		var fileID = $(this).next().text();
		var text = $(this).prev().val();
		var username = $(this).next().next().text();
		if (text == '') {
			alert("亲，评论是空的啊！");
		}else{
			var txt = $.post('jsPostFunction.php', {functionID: 'commentSubmit', fileID: fileID, text: text}, function(data, textStatus, xhr) {
				var obj = eval("(" + data + ")");
				var txt = "<div><p class='name'>" + username + "</p><p class='time'>" + obj.commentTime + "</p><p class='content'>" + text + "</p><p hidden='hidden'>" + obj.commentID + "</p><div class='deleteCommentButton'><div>删除</div></div></div>";
				alert(obj.hint);
				return txt;
				
			});
			$(this).parent().prev().append('');
			$(this).prev().val('');
		}
	});
	$('.deleteCommentButton').click(function(){
		if (confirm('确认删除此条评论？')) {
			var commentID = $(this).prev().text();
			$.post('jsPostFunction.php', {functionID: 'deleteComment', commentID: commentID});
			$(this).parent().hide('slow');
		}
	});
})

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
		<li><a class="tag pink" href="./main" title="相册首页" style="background-color:pink;">相册首页</a></li>
		<li><a class="tag green" href="./upload" title="上传照片">上传照片</a></li>
		<li><a class="tag blue" href="./view" title="浏览照片">浏览照片</a></li>
		</ul>
	</div>
	
	<div class="mainContent entry">
		
		<div class="news">
			<h2>站内新闻</h2>
			<hr />
			<?
				require_once("pageModel.php");
				
				if($con = mysql_connect("localhost", "root", "root")){
					mysql_query("set names utf8");
					mysql_select_db("db_Album", $con);
				}else{
					die("MySQL connect failed".mysql_error());
				}
				
				$view = new M_Page($con, "./main", $_SESSION[userID]);		//第二个参数为目标页面
				$view->showPage();
				
				mysql_close($con);
			?>
		</div>
		
	</div>
	
	<div class="pageBar entry">
	<?
		$view->showPageTag();
	?>
	</div>

	<div class="footer entry">
	<?
		require_once("footer.php");
	?>
	</div>
	
</div>
</body>
</html>
