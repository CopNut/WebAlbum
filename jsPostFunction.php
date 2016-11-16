<?php
	session_start();
	$userID = $_SESSION[userID];
	$username = $_SESSION[username];
	$functionID = $_POST[functionID];
	$con = mysql_connect('localhost', 'root', 'root');
	mysql_query("set names utf8");
	mysql_select_db('db_album',$con);
	
	switch ($functionID) {
		case 'checkUsername':
			$username = $_POST[username];
			
			$sqlStr = "SELECT * FROM table_UserInfo WHERE username = '".$username."'";
			$result = mysql_query($sqlStr, $con);
			if($row = mysql_fetch_row($result)){
				echo 1;//exsit
			}else{
				echo 0;//not exsit
			}
			mysql_free_result($result);
			mysql_close($con);
			break;

		case 'changePrivace':
			$fileID = $_POST[fileID];
			
			$sqlStr = "SELECT privace FROM table_uploadedphoto WHERE fileID = '$fileID'";
			$result = mysql_query($sqlStr, $con);
			$row = mysql_fetch_row($result);
			mysql_free_result($result);
			$privace = ($row[0] + 1) % 2; 
			$sqlStr = "UPDATE table_uploadedphoto SET privace = '$privace' WHERE fileID = '$fileID'"; 
			$result = mysql_query($sqlStr, $con);
			mysql_free_result($result);
			mysql_close($con);
			break;

		case 'deleteImage':
			$fileID = $_POST[fileID];
			
			$sqlStr = "UPDATE table_uploadedphoto SET del = '1' WHERE fileID = '$fileID'";
			$result = mysql_query($sqlStr, $con);
			mysql_free_result($result);

			$sqlStr = "DELETE FROM table_comment WHERE fileID = '$fileID'";
			$result = mysql_query($sqlStr, $con);
			mysql_free_result($result);

			mysql_close($con);
			break;

		case 'createAlbum':
			$albumname = $_POST[albumname];
			if ($albumname == "") {
				return;
			}
			
			$sqlStr = "SELECT * FROM table_personalalbum WHERE userID = '$userID' AND albumname = '$albumname'";
			$result = mysql_query($sqlStr, $con);
			if ($row = mysql_fetch_row($result)) {
				echo '{
					"success":"0",
					"hint":"'.$albumname.'已经存在该相册！"
				}';
				mysql_free_result($result);
				mysql_close($con);
			}else{
				
				$dirPath = iconv('UTF-8', 'GBK', "./photo/$username/$albumname");
				mkdir($dirPath);

				$sqlStr = "INSERT INTO table_personalalbum (userID, albumname) VALUES ('$userID', '$albumname')";
				$result = mysql_query($sqlStr, $con);
				// mysql_free_result($result);

				$sqlStr = "SELECT albumID FROM table_personalalbum WHERE userID = '$userID' AND albumname = '$albumname'";
				$result = mysql_query($sqlStr, $con);
				$row = mysql_fetch_row($result);
				mysql_free_result($result);
				$albumID = $row[0];
				echo 
				'{"success":"1",
				"albumname":"'.$albumname.'",
				"albumID":"'.$albumID.'",
				"hint":"'.$albumname.' 相册创建成功！"}';//多写了一个双引号导致语义出错

				mysql_close($con);
			}
			break;

		case 'deleteAlbum':
			$albumID = $_POST[albumID];
			
			$sqlStr = "UPDATE table_personalalbum SET del = '1' WHERE albumID = '$albumID'";
			$result = mysql_query($sqlStr, $con);
			mysql_free_result($result);

			$sqlStr = "UPDATE table_uploadedphoto SET del = '1' WHERE albumID = '$albumID'";
			$result = mysql_query($sqlStr, $con);
			mysql_free_result($result);
			
			mysql_close($con);
			break;

		case 'commentSubmit':
			$fileID = $_POST[fileID];
			$text = $_POST[text];

			$sqlStr = "INSERT INTO table_comment (userID, fileID, comment) VALUES ('$userID', '$fileID', '$text')";
			$result = mysql_query($sqlStr, $con);

			$sqlStr = "SELECT commentID, commentTime FROM table_comment WHERE userID = '$userID' ORDER BY commentID DESC";
			$result = mysql_query($sqlStr, $con);
			$row = mysql_fetch_row($result);
			mysql_free_result($result);

			echo '{
				"commentID":"'.$row[0].'",
				"commentTime":"'.$row[1].'",
				"hint":"评论成功！"
			}';

			break;

		case 'deleteComment':	
			$commentID = $_POST[commentID];
			$sqlStr = "DELETE FROM table_comment WHERE commentID = '$commentID'";
			$result = mysql_query($sqlStr, $con);
			mysql_free_result($result);

			echo "评论删除成功！";
		break;

		default:
			
		break;
	}
?>