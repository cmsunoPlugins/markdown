<?php
if(file_exists(dirname(__FILE__).'/../../../files/upload/'.strip_tags($_POST['r'].$_POST['n']).'.zip')) echo true;
else echo false;
//
// Unlink old files in UPLOAD (30h)
$tab=''; $d=dirname(__FILE__).'/../../../files/upload/';
if(is_dir($d) && $dh=opendir($d))
	{
	while (($file = readdir($dh))!==false)
		{
		if($file!='.' && $file!='..' && strpos($file,'.zip')!==false && time()-filemtime($d.$file)>108000) unlink($d.$file);
		}
	closedir($dh);
	}
?>
