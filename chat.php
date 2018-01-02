<?php
include 'config.php';

if(!isLoggedIn())
{
	die('WTF ...');
}

$file = file('chat.txt');
end( $file );
$last_line = key($file);

//$file = file('chat2.txt');

function strReplaceAssoc(array $replace, $subject) 
{
	return str_replace(array_keys($replace), array_values($replace), $subject);   
}
?>
<div class="chat_window">
	<?php 
		$i = $last_line - $msgPerPage;
		while($i < ($last_line + 1))
		{
			if(array_key_exists($i, $file))
			{
				echo strReplaceAssoc($smileys, $file[$i]);
			}
			$i++;
		}
	?>
</div>
<?php exit; ?>
