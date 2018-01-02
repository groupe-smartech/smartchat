<?php
	include 'config.php';
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.1//EN" "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Chat</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" >
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<link rel="stylesheet" href="style.css" />
		<script>
			var site_url = '<?php echo SITE_URL; ?>';
			var installed_folder = '<?php echo INSTALLED_FOLDER; ?>';
		</script>
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<?php if(isset($_SESSION['user'])) { ?>
			<script type="text/javascript" src="main.js"></script>
		<?php } ?>
	</head>
	<body>
		<div class="main">
			<?php
			if(array_key_exists('logout', $_GET))
			{
				logout();
				header("Location: http://".$_SERVER['HTTP_HOST']."/".INSTALLED_FOLDER);
				exit;
			}
			if(!isLoggedIn())
			{
				if(count($_POST) != 0)
				{
					if(login($_POST, $users))
					{
						header("Location: http://".$_SERVER['HTTP_HOST']."/".INSTALLED_FOLDER);
						exit;
					}
				}
			?>
			<div class="login">
				<h1>Login</h1>
				<?php
					if(array_key_exists("error", $_SESSION))
					{
						?>
						<div class="error"><?php echo $_SESSION['error']; ?></div>
						<?php
						unset($_SESSION['error']);
					}
				?>
				<form method="post">
				<p>Username: &nbsp;&nbsp;<input type="text" name="username"></p>
				<p>Password: &nbsp;&nbsp;&nbsp;<input type="password" name="password"></p>
				<p><input class="button" type="submit" name="Login" value="Login"></p>
				</form>
				<p>For demo use: user1 and 123 OR user2 and 123</p>
			</div>
			<?php
			}else
			{
				if(count($_POST) != 0 || (isset($_FILES['thefile']['error']) && $_FILES['thefile']['error'] === 0) )
				{
					if(!array_key_exists("message", $_SESSION))
					{
						$_SESSION['message'] = '';
					}
					if($_SESSION['message'] != $_POST['message'])
					{
						addMsg($_POST,$usersColors);
					}
					$_SESSION['message'] = $_POST['message'];
					if($_FILES['thefile']['error'] === 0)
					{
						addPhoto($_FILES,$usersColors);
						header("Location: http://".$_SERVER['HTTP_HOST']."/".INSTALLED_FOLDER);
						exit;
					}
				}
			?>
			<div class="chat">
				<div class="chat_info">
				<h2>
					<img src="smileys/favicon.ico">&nbsp; Hi, <?php echo $_SESSION['user']['username'] ?>&nbsp;&nbsp;&nbsp;<a href="?logout">logout</a>&nbsp;&nbsp;&nbsp;
					<a style="float:right;padding-right:10px;padding-top:5px;" href="" >REFRESH</a>
					<a style="float:right;padding-right:10px;padding-top:5px;" href="#" onclick="clearMessages()">CLEAR</a>
				</h2>
				</div>
				
				<div id="chat_messages"></div>
				<form enctype="multipart/form-data" method="post">
					<p style="">
						<input class="file" type = "file" name = "thefile"><br><br>
						<input class="message" type="text" name="message" autocomplete="off" placeholder="...type your message" id="message_box">
						<br/>
						<input class="button2" type="submit" name="send" value="Send">
						<br/>
						
					</p>
				</form>
			</div>
			<?php
			}
			?>
		</div>
		<br/>
		<div class="main">
			<p><i>This chat was developed by <a href="http://www.w3bdeveloper.com" target="_blank">w3bdeveloper.com</a> website. You can use it for free, you can distribute it, but please give a link to our website. Thanks, Enjoy.</i></p>
		</div>
	</body>
</html>
