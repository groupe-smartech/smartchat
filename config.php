<?php
session_start();
define('SITE_URL', 'http://www.w3bdeveloper.com');
define('INSTALLED_FOLDER','chat-v1');
# example: http://www.w3bdeveloper.com/chat-v1/

$users = array("user1" => "202cb962ac59075b964b07152d234b70", // pass: md5('123')
			   "user2" => "202cb962ac59075b964b07152d234b70"); // pass: md5('123')
$usersColors = array("user1" => "blue", // works with #ccc
			  		 "user2" => "red");
$msgPerPage = 35;

$smileys = array(':d' => '<img src="smileys/biggrin.gif">', 
				':D' => '<img src="smileys/biggrin.gif">', 
				':((' => '<img src="smileys/crying.gif">',
				':))' => '<img src="smileys/laughing.gif">', 
				':)' => '<img src="smileys/happy.gif">',
				':-)' => '<img src="smileys/happy.gif">',
				':-*' => '<img src="smileys/kiss.gif">',
				':*' => '<img src="smileys/kiss.gif">',
				':(' => '<img src="smileys/sad.gif">',
				':-(' => '<img src="smileys/sad.gif">',
				':p' => '<img src="smileys/tongue.gif">',
				':P' => '<img src="smileys/tongue.gif">',
				';)' => '<img src="smileys/winking.gif">',
				';-)' => '<img src="smileys/winking.gif">',
				':x' => '<img src="smileys/love.gif">',
				':X' => '<img src="smileys/love.gif">',
				'<3' => '<img src="smileys/Heart-icon.png">',
				);

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['clearMessage'])) {
	if(isset($_SESSION['user']))	{
		clearMessages();
	}
	else {
		echo 'Error!';exit;
	}
}				

function clearMessages() {
	#clear text
	$myFile = "chat.txt";
	$stringData = file_get_contents($myFile);
	$fh = fopen($myFile, 'w') or die("can't open file");
	fwrite($fh, "<div><span style='font-size:10px;color:#949494;'>11 Jul 15:11</span> <span style='color:#1a80e6;'><b style='color:blue;'>Daniel:</b></span> <span style='font-size:11px;'>Hi. Please visit www.w3bdeveloper.com :)</span></div>");
	fclose($fh);
	#clear big images
	$imgs = str_replace('config.php','images/*', __FILE__);
	$files = glob($imgs); 
	foreach($files as $file){ 
	  if(is_file($file))
	    unlink($file); 
	}
	#clear thumbs
	$imgs = str_replace('config.php','images/thumbs/*', __FILE__);
	$files = glob($imgs); 
	foreach($files as $file){
	  if(is_file($file))
	    unlink($file);
	}
	echo 'Messages deleted.';exit;
}				
				
				
function isLoggedIn()
{
	if(array_key_exists("user", $_SESSION))
	{
		return true;
	}
	return false;
}

function login($data, $users)
{
	if(array_key_exists("username", $data) && array_key_exists("password", $data))
	{
		if(array_key_exists($data['username'], $users) && in_array(md5($data['password']), $users))
		{
			$_SESSION['user'] = $data;
			$fh = fopen('log.txt', 'a') or die("can't open file");
			$stringData = $_SESSION['user']['username'].' ----- '.date("Y-m-d H:i:s", time()).' IP: '.$_SERVER['REMOTE_ADDR'].' UserAgent: '.$_SERVER['HTTP_USER_AGENT']."\n\n";
			fwrite($fh, $stringData);
			fclose($fh);
			return true;
		}
	}
	$_SESSION['error'] = "Invalid Login!";
	return false;
}

function logout()
{
	unset($_SESSION['user']);
}

function addMsg($data,$usersColors)
{
	if(array_key_exists("message", $data) && trim($data['message']) != "")
	{
		$myFile = "chat.txt";
		$stringData = file_get_contents($myFile);
		$fh = fopen($myFile, 'w') or die("can't open file");

		$userColor = " style='color:".$usersColors[ $_SESSION['user']['username'] ].";'";

		$username = $_SESSION['user']['username'];
		
		$stringDataNew = "<div><span style='font-size:10px;color:#949494;'>".date('d M H:i')."</span> <span style='color:#1a80e6;'><b".$userColor.">".$username.":</b></span> <span style='font-size:11px;'>".$data['message']."</span></div>"."\n";
		$stringData = $stringData.$stringDataNew;
		#$stringData = "";
		fwrite($fh, $stringData);
		fclose($fh);
	}
}

	function imageResizeRatio($source, $imageName) 
	{ 
		 $sourceW = imagesx($source); 
		 $sourceH = imagesy($source); 
		
		if($sourceW > $sourceH)
		{
			$finalWidthThumb = 180;
			$finalWidth = 800;
			$finalHeightThumb = ($finalWidthThumb * $sourceH) / $sourceW;
			$finalHeight = ($finalWidth * $sourceH) / $sourceW;
		}else
		{
			$finalHeightThumb = 180;
			$finalHeight = 800;
			$finalWidthThumb = ($finalHeightThumb * $sourceW) / $sourceH;
			$finalWidth = ($finalHeight * $sourceW) / $sourceH;
		}
		
		 $ratioImage = imageCreateTrueColor(floor($finalWidth), floor($finalHeight));
		 $bg = imagecolorallocate ( $ratioImage, 255, 255, 255 ); 
		 imagefill ( $ratioImage, 0, 0, $bg );
		 imageCopyResampled($ratioImage, 
													 $source, 
													 0, 
													 0, 
													 0, 
													 0, 
													 $finalWidth, 
													 $finalHeight, 
													 $sourceW, 
													 $sourceH);
			$ratioImageThumb = imageCreateTrueColor(floor($finalWidthThumb), floor($finalHeightThumb));
			$bg = imagecolorallocate ( $ratioImageThumb, 255, 255, 255 ); 
			imagefill ( $ratioImageThumb, 0, 0, $bg );
			imageCopyResampled($ratioImageThumb, 
													 $source, 
													 0, 
													 0, 
													 0, 
													 0, 
													 $finalWidthThumb, 
													 $finalHeightThumb, 
													 $sourceW, 
													 $sourceH); 
		$picture = "/".INSTALLED_FOLDER."/images/".$imageName.".jpg";
		$thumb = "/".INSTALLED_FOLDER."/images/thumbs/".$imageName.".jpg";
		ImageJPEG($ratioImage, $_SERVER["DOCUMENT_ROOT"].$picture); 
		ImageJPEG($ratioImageThumb, $_SERVER["DOCUMENT_ROOT"].$thumb); 
	}

function addPhoto($file,$usersColors)
{
	$accepted = array("image/jpeg", "image/png");
	if(array_key_exists("thefile", $file) && $file['thefile']['tmp_name'] != "")
	{
		$image = imageCreateFromString(file_get_contents($file['thefile']['tmp_name']));
		$finalName = time();
		imageResizeRatio($image, $finalName);
		
		$myFile = "chat.txt";
		$stringData = file_get_contents($myFile);
		$fh = fopen($myFile, 'w') or die("can't open file");
		$userColor = " style='color:".$usersColors[ $_SESSION['user']['username'] ].";'";
		
		$username = $_SESSION['user']['username'];
		
		$stringDataNew = "<div><span style='font-size:10px;color:#949494;'>".date('d M H:i')."</span> <span style='color:#1a80e6;'><b".$userColor.">".$username.":</b></span> <span style='font-size:11px;'>photo: <br><a target='_parent' href='images/".$finalName.".jpg'><img style='' src='images/thumbs/".$finalName.".jpg'></a></span></div>"."\n";
		$stringData = $stringData.$stringDataNew;
		
		fwrite($fh, $stringData);
		fclose($fh);
	}
}