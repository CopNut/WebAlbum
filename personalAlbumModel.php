<?php
class M_Album{
	private $userID;
	private $con;
	private $aim;

	public function __construct($c_userID, $c_Con, $c_Aim)
	{
		$this->userID = $c_userID;
		$this->con = $c_Con;
		$this->aim = $c_Aim;
	}

	public function showAlbum()
	{	
		echo "<div class='albumSelect box'>";
		echo "<div class='fullEntry'><label for='selectedAlbum'><h2>选择相册</h2></label><hr /></div>";

		$sqlStr = "SELECT albumID, albumname FROM table_personalalbum WHERE userID = '$this->userID' AND del = '0' ORDER BY albumID";
		$result = mysql_query($sqlStr, $this->con);
		
		// -------------------------------------------------default-album------------------------------------------
		$row = mysql_fetch_row($result);
		$sqlStr = "SELECT filepath FROM table_uploadedphoto WHERE albumID = '$row[0]' ORDER BY uploadTime DESC";
		$coverResult = mysql_query($sqlStr, $this->con);
		$cover = mysql_fetch_row($coverResult);
		mysql_free_result($coverResult);
		if ($this->aim == './view') {
			echo "<a href='$this->aim?album=$row[0]'>";
		}
		echo "<label><input type='radio' name='selectedAlbum' value='$row[0]' id='default' checked='checked'/><div></div><div class='album'><div class='albumCover block'><img class='cover' src='$cover[0]' /></div><div class='albumName block'>$row[1]</div></div></label>";
		if ($this->aim == './view') {
			echo "</a>";
		}



		// --------------------------------------------------------user-album--------------------------------------------
		while ($row = mysql_fetch_row($result)) {
		$sqlStr = "SELECT filepath FROM table_uploadedphoto WHERE albumID = '$row[0]' ORDER BY uploadTime DESC";
		$coverResult = mysql_query($sqlStr, $this->con);
		$cover = mysql_fetch_row($coverResult);
		mysql_free_result($coverResult);
		if ($this->aim == './view') {
			echo "<a href='$this->aim?album=$row[0]'>";
		}
			echo "<label>";
			if ($_GET[album] == $row[0]) {
				echo "<div id='selected'></div>";//用于切换相册刷新页面后，实现相册选择的视觉交互效果
			}
			echo "<input type='radio' name='selectedAlbum' value='$row[0]'/><div class='deleteAlbumButton'><div>删除</div></div><div class='album'><div class='albumCover block'><img class='cover' src='$cover[0]' /></div><div class='albumName block'>$row[1]</div></div>
			</label>";
		}
		if ($this->aim == './view') {
			echo "</a>";
		}



		// -------------------------------------------------------creat-album----------------------------------------------
		if ($this->aim == "./upload") {
			echo "<div id='creatAlbumButton' class='album'><div class='albumCover block'></div><div class='albumName block'>新建相册+</div></div>";
		}
			
		echo "</div>";

		mysql_free_result($result);
	}

}
?>