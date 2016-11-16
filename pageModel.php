<?

class M_Page{
	private $pageNumber;
	private $startPhotoNumber;
	private $amountOfEveryPage = 4;
	private $maxPageNumber;
	private $albumID;
	private $con;
	private $aim;
	private $userID;
	
	public function __construct($c_Con, $c_Aim, $c_UserID){
		$this->con = $c_Con;
		$this->aim = $c_Aim;
		$this->userID = $c_UserID;
		$this->albumID = $this->getAlbumID();
		$this->pageNumber = $this->getPageNumber();
		$this->startPhotoNumber = $this->pageNumber * $this->amountOfEveryPage;
		if($c_Aim == './view'){
			$this->maxPageNumber = $this->getMaxPageNumberView();
		}else if($c_Aim == './main'){
			$this->maxPageNumber = $this->getMaxPageNumberMain();
		}
	}
	
	private function getPageNumber(){
		$_GET['page'] = empty($_GET['page']) ? 0 : $_GET['page'];
		return $_GET['page'];
	}

	private function getAlbumID(){
		$sqlStr = "SELECT albumID FROM table_personalAlbum WHERE userID = '$this->userID' ORDER BY albumID";
		$result = mysql_query($sqlStr, $this->con);
		$row = mysql_fetch_row($result);
		mysql_free_result($result);
		$defaultID = $row[0];

		$_GET['album'] = empty($_GET[album]) ? $defaultID : $_GET['album'];
		return $_GET[album];
	}
	
	private function getMaxPageNumberView(){
		$sqlStr = "SELECT COUNT(*) FROM table_uploadedPhoto WHERE userID = '$this->userID' AND albumID = '$this->albumID'";
		$result = mysql_query($sqlStr, $this->con);
		$row = mysql_fetch_row($result);
		mysql_free_result($result);
		return (ceil ($row[0] / $this->amountOfEveryPage) - 1);
	}

	private function getMaxPageNumberMain(){
		$sqlStr = "SELECT COUNT(*) FROM table_uploadedPhoto WHERE privace = '0'";
		$result = mysql_query($sqlStr, $this->con);
		$row = mysql_fetch_row($result);
		mysql_free_result($result);
		return (ceil ($row[0] / $this->amountOfEveryPage) - 1);
	}
	
	public function showPage(){
		if($this->aim == "./main"){
			$sqlStr = "SELECT filepath, filename, fileID FROM table_uploadedPhoto WHERE privace = '0' AND del = '0' ORDER BY uploadTime DESC LIMIT ".$this->startPhotoNumber.",".$this->amountOfEveryPage;
		}else if($this->aim == "./view"){
			$sqlStr = "SELECT filepath, filename, fileID, privace FROM table_uploadedPhoto WHERE albumID = '$this->albumID' AND userID = '".$_SESSION[userID]."' AND del = '0' ORDER BY uploadTime DESC LIMIT ".$this->startPhotoNumber.",".$this->amountOfEveryPage;
		}

		$imgResult = mysql_query($sqlStr, $this->con);
		while($imgRow = mysql_fetch_row($imgResult)){
			echo 
			"<div class='fullEntry box between'>";


			// ---------------------------------------show-image--------------------------------------------
			echo "
				<div class='fullEntry filename''>
					<h3>$imgRow[1]</h3><hr>
				</div>
				<div class='imgBlock box'>
					<div class='img box center'>
						<img src='".$imgRow[0]."'/>
					</div>";
					
			if($this->aim == "./view"){
				echo "<div class='control'>";
				if($imgRow[3] === '1'){
					echo "私有<label><input class='switchButton' type='checkbox'/><div><div></div></div></label>公开";
				}else{
					echo "私有<label><input class='switchButton' type='checkbox' checked='checked'/><div><div></div></div></label>公开";
				}
				echo "<p class='fileID' hidden='hidden'>$imgRow[2]</p>";
				echo "<label class='deleteImageButton'><div>删除</div></label>";
				echo "</div>";
			}
			echo "</div>";



			// --------------------------------------------show-comment------------------------------------
			echo "<div class='commentBlock box'>";
			echo "<h4>评论</h4><hr>";
			$sqlStr = "SELECT COUNT( * ) FROM table_comment WHERE fileID = '$imgRow[2]'";
			$result = mysql_query($sqlStr, $this->con);
			$row = mysql_fetch_row($result);
			mysql_free_result($result);
			echo "<div class='commentContainer box'>";
			if ($row[0] != 0) {
				$sqlStr = "SELECT userID, commentTime, comment, commentID FROM table_comment WHERE fileID = '$imgRow[2]' ORDER BY commentTime";
				$commentResult = mysql_query($sqlStr, $this->con);
				while ($commentRow = mysql_fetch_row($commentResult)) {
					$sqlStr = "SELECT username FROM table_userinfo WHERE userID = '$commentRow[0]'";
					$nameResult = mysql_query($sqlStr, $this->con);
					$username = mysql_fetch_row($nameResult);
					mysql_free_result($nameResult);
					echo
					"<div>
					<p class='name'>$username[0]:</p>
					<p class='time'>$commentRow[1]</p>
					<p class='content'>$commentRow[2]</p>
					<p hidden='hidden'>$commentRow[3]</p>";
					if ($this->aim == './view') {
						echo "<div class='deleteCommentButton'><div>删除</div></div>";
					}else if ($this->userID == $commentRow[0]) {
						echo "<div class='deleteCommentButton'><div>删除</div></div>";
					}
					echo "</div>";
				}
				mysql_free_result($commentResult);
			}else{
				echo "<div><p class='content'>暂无评论</p></div>";
			}
			echo "</div>";
			if($this->aim == "./main"){
				echo
				"<div class='writeComment box'>
					<div class='fullEntry'><h4>写评论</h4><hr></div>
					<textarea></textarea>
					<div class='commentSubmit'>提交</div>
					<p hidden='hidden'>$imgRow[2]</p>
					<p hidden='hidden'>$_SESSION[username]</p>
				</div>";
			}



			echo "</div></div>";
		}
		mysql_free_result($imgResult);
	}
	
	public function showPageTag(){
		if($this->maxPageNumber <= 0){
		}else{
			echo "<hr />";
			switch($this->pageNumber){
				case 0:
					echo "<ul class='menu'>";
					echo "<li><a class='pageTag pink' href='$this->aim'>首页</a></li>";
					echo "<li><a class='pageTag green' href='$this->aim?album=$this->albumID&page=".($this->pageNumber + 1)."'>下一页</a></li>";
					echo "<li><a class='pageTag blue' href='$this->aim?album=$this->albumID&page=".$this->maxPageNumber."'>尾页</a></li>";
					echo "</ul>";
					break;
				case $this->maxPageNumber:
					echo "<ul class='menu'>";
					echo "<li><a class='pageTag pink' href='$this->aim'>首页</a></li>";
					echo "<li><a class='pageTag green' href='$this->aim?album=$this->albumID&page=".($this->pageNumber - 1)."'>上一页</a></li>";
					echo "<li><a class='pageTag blue' href='$this->aim?album=$this->albumID&page=".$this->maxPageNumber."'>尾页</a></li>";
					echo "</ul>";
					break;
				default :
					echo "<ul class='menu'>";
					echo "<li><a class='pageTag pink' href='$this->aim'>首页</a></li>";
					echo "<li><a class='pageTag green' href='$this->aim?album=$this->albumID&page=".($this->pageNumber - 1)."'>上一页</a></li>";
					echo "<li><a class='pageTag green' href='$this->aim?album=$this->albumID&page=".($this->pageNumber + 1)."'>下一页</a></li>";
					echo "<li><a class='pageTag blue' href='$this->aim?album=$this->albumID&page=".$this->maxPageNumber."'>尾页</a></li>";
					echo "</ul>";
			}
		}
	}

}
?>
