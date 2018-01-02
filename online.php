<?php
include 'config.php';
$now = time();
$online = "";
foreach($users as $k => $v)
{
	$date = file_get_contents($k);
	if($now - 60 < $date)
	{
		$online .= $k." ";
	}
}
echo $online;