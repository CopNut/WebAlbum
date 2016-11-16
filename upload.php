<?php
	session_start();
	if(!$_SESSION[userID]){
		echo "<script>window.location.href='./'</script>";	
	}
	/*连接数据库*/
	if($con = mysql_connect("localhost", "root", "root")){
		mysql_query("set names utf8");
		mysql_select_db("db_Album", $con);
	}else{
		die("MySQL connect failed".mysql_error());
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta name="viewport" content="width=dievice-width, initial-scale=1" />
<link rel="stylesheet" type="text/css" href="albumCSS" />
<title>电子相册 - 上传</title>
<script type="text/javascript" src="jquery-2.2.2.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#creatAlbumButton").click(function() {
			var albumname = prompt("相册名称","");
			$.post("jsPostFunction.php",{functionID:"createAlbum",albumname:albumname},function(data){
				var obj = eval("(" + data + ")");
				if (obj.success == 1) {
					var txt = "<label><input type='radio' name='selectedAlbum' value='" + obj.albumID + "'/><div class='deleteAlbumButton'><div>删除</div></div><div class='album'><div class='albumCover block'></div><div class='albumName block'>" + obj.albumname + "</div></div></label>";
					$("#creatAlbumButton").before(txt);
				}
				alert(obj.hint);
			});
		});

		$(".deleteAlbumButton").click(function(){
			if (confirm('确认删除？')) {
				var albumID = $(this).prev().val();
				$.post('jsPostFunction.php', {functionID:"deleteAlbum",albumID:albumID});
				$(this).parent().hide('slow');
				$('#default').attr({
					checked: 'checked'
				});
			}
		});
	});

	function showButton() {
		if($('#files').val() != null){
			$('#uploadButton').show('slow');
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
		<li><a class="tag pink" href="./main" title="相册首页">相册首页</a></li>
		<li><a class="tag green"href="./upload" title="上传照片" style="background-color:lightgreen;">上传照片</a></li>
		<li><a class="tag blue" href="./view" title="浏览照片">浏览照片</a></li>
		</ul>
	</div>
	
	<div class="mainContent entry">
		<form method="post" action="<? echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data" class="uploadForm">
		<?php
			require_once("personalAlbumModel.php");
			$albumList = new M_Album($_SESSION[userID], $con, "./upload");
			$albumList->showAlbum();
		?>
			<div class='imageSelect box'>
				<div class="fullEntry">
					<label for="files"><h2>选择图片</h2></label><hr />
				</div>
				<label><input id='files' class="files" name="files[]" type="file" multiple="multiple" onchange="showButton();" />浏览图片</label>
				<label id='uploadButton'><input class="submit" type="submit" value="上传"  />上传图片</label>
			</div>
		</form>
			
		<div class="uploadResult box">

			<div class="fullEntry">
				<h2>上传结果</h2><hr />
			</div>
			<?
			require_once("uploadModel.php");//引入照片上传类
			$counter = 0;//用于取出多文件上传的计数器

			/*上传照片*/
			while($_FILES['files']['name'][$counter]){
				$upload = new M_UploadRequest($_SESSION[userID], $_POST[selectedAlbum], $$_FILES['files']['name'][$counter], $_FILES['files']['type'][$counter], $_FILES['files']['size'][$counter], $_FILES['files']['tmp_name'][$counter], $con);
				$upload->upload();
				$counter++;
			}
			
			/*关闭数据库连接*/
			mysql_close($con);
			?>
		</div>
		
	</div>
	
	<div class="footer entry">
	<?
		require_once("footer.php");
	?>
	</div>
	
</div>

</body>
</html>
