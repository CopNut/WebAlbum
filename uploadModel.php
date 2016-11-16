<?
class M_UploadRequest{
	private $userID;
	private $albumID;
	private $fileName;
	private $fileType;
	private $fileSize;
	private $tmpName;
	private $con;
	public $errorMsg;
	//privace default:1(privata), 0(public)
	
	public function __construct($c_UserID, $c_AlbumID, $c_FileName, $c_FileType, $c_FileSize, $c_TmpName, $c_Con){
		$this->userID = $c_UserID;
		$this->albumID = $c_AlbumID;
		$this->fileName = $c_FileName;
		$this->fileType = $c_FileType;
		$this->fileSize = $c_FileSize;
		$this->tmpName = $c_TmpName;
		$this->con = $c_Con;
	}
	
	private function isLegal(){
		if(($this->fileSize <= 524288000)){
			return true;
		}else{
			$this->errorMsg = "上传的图片限定为jpg或bmp格式且小于5M";
			return false;
		}
	}
	
	public function upload(){
		if($this->isLegal()){

			$sqlStr = "SELECT username FROM table_userinfo WHERE userID = '$this->userID'";
			$result = mysql_query($sqlStr, $this->con);
			$row = mysql_fetch_row($result);
			mysql_free_result($result);
			$username = $row[0];

			$sqlStr = "SELECT albumname FROM table_personalalbum WHERE albumID = '$this->albumID'";
			$result = mysql_query($sqlStr, $this->con);
			$row = mysql_fetch_row($result);
			mysql_free_result($result);
			$albumname = $row[0];

			$filePath = "./photo/$username/$albumname/$this->fileName"+".jpeg";
			$sqlStr = "INSERT INTO table_uploadedPhoto (userID, albumID, filename, filepath) VALUE ('$this->userID', '$this->albumID', '$this->fileName', '$filePath')";

			if (preg_match("/^WIN/i", PHP_OS)) {//调整编码格式
				$filePath = iconv('UTF-8', 'GBK', $filePath);
			}
			move_uploaded_file($this->tmpName, $filePath);
			$result = mysql_query($sqlStr, $this->con);
			
			
			echo "<p>".$this->fileName." 上传成功</p>";
		}else{
			echo "<p>".$this->fileName." 上传失败 ".$this->errorMsg."</p>";
		}
	}	
}
?>
