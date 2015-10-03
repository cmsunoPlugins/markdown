<?php
// CMSUno - Plugin Markdown
// CHECK update for Premium WordPress Plugin
// Use plugin-update-checker in your plugin : https://github.com/YahnisElsts/plugin-update-checker
// -  -  -  -  -  -  -  -  -  -  -
// 1/ Add the folder (and the content) /plugin-update-checker/ in your WP plugin
// 2/ Add this in myplugin.php - customizes data :
//	if(is_admin())
//		{
//		require(dirname(__FILE__).'/key.php');
//		require(dirname(__FILE__).'/plugin-update-checker/plugin-update-checker.php');
//		$MyUpdateChecker= new PluginUpdateChecker_2_0('http://LINK-TO-THIS FILE/markdownUpdate.php?s=myplugin&k='.$key.'&u='.$_SERVER['SERVER_NAME'], __FILE__, 'myplugin');
//		}
//
include(dirname(__FILE__).'/../../config.php'); // sdata
$d1=dirname(__FILE__).'/../../../files/';
$d2=dirname(__FILE__).'/../../data/';
$s = (isset($_GET['s'])?strip_tags($_GET['s']):false); // slug
$k = (isset($_GET['k'])?strip_tags($_GET['k']):false); // key
$u = (isset($_GET['u'])?strip_tags($_GET['u']):false); // url
if(!$s || !$k || !$u || !file_exists($d2.'_sdata-'.$sdata.'/_digital/'.$k.$s.'.json') || !file_exists($d2.'_sdata-'.$sdata.'/markdown.json'))
	{
	echo json_encode(array());
	sleep(1);
	exit;
	}
$q = file_get_contents($d2.'_sdata-'.$sdata.'/markdown.json'); $a = json_decode($q,true); $k1 = false;
// ***** RESPONSE *****
	// GET Data from readme.txt
	$data = file_get_contents($d1.$s.'/readme.txt');
	$pos = strpos($data,'== Description');
	if($pos!==false) $data = substr($data,0,$pos);
	$name = ''; $contributor = ''; $stable_tag = 0;
	$name = explode("===",' '.$data);
	if(is_array($name)) $name = $name[1];
	if(preg_match('|Contributors:(.*)|i',$data,$m))
		{
		$m = preg_split('|,[\s]*|', trim($m[1]));
		foreach(array_keys($m) as $n)
			{
			$tm = trim($m[$n]);
			if(strlen(trim($tm))>0) $contributors .= $tm . ' - ';
			}
		}
	if(preg_match('|Stable tag:(.*)|i',$data,$m)) $stable_tag = trim($m[1]);
	//
	// JSON Response
	$j = array(
		"name"=>$name,
		"slug"=>$s,
		"author"=>substr($contributors,0,-3)
		);
	$j['version'] = '1.0';
	$j['download_url'] = '';
	$j['sections'] = array("description"=>$name);
	//
// ***** *****
foreach($a as $r)
	{
	if(isset($r['md'][$s])) { $k1 = $r['md'][$s]['k']; break; }
	}
if(!$k1)
	{
	echo json_encode($j);
	file_put_contents('retour65.txt', $s.' - '.$k.' - '.$u. ' - '.json_encode($j));
	exit;
	}
$q = file_get_contents($d2.'busy.json'); $a = json_decode($q,true); $Ubusy = $a['nom'];
$q = file_get_contents($d2.$Ubusy.'/site.json'); $a = json_decode($q,true);  $base = $a['url'];
if(!file_exists($d1.$s.'/'.$k1.$s.'.zip'))
	{
	echo json_encode($j);
	file_put_contents('retour73.txt', $s.' - '.$k.' - '.$u. ' - '.json_encode($j));
	exit;
	}
//
// update zip for user
if(!is_dir($d1.'upload/')) mkdir($d1.'upload/');
$k2 = substr(sha1(substr($k1,0,8).date('z').date('Y')),0,10); // key for the day
if(!file_exists($d1.'upload/'.$k2.$s.'.zip'))
	{
	copy($d1.$s.'/'.$k1.$s.'.zip', $d1.'upload/'.$k2.$s.'.zip');
	$tab='';
	if(is_dir($d1.'upload/') && $dh=opendir($d1.'upload/'))
		{
		while (($file = readdir($dh))!==false)
			{
			if($file!='.' && $file!='..' && strpos($file,'.zip')!==false && time()-filemtime($d1.'upload/'.$file)>108000) unlink($d1.'upload/'.$file); // 30 h
			}
		closedir($dh);
		}
	}
//
// SAVE Server for this key
$q = file_get_contents($d2.'_sdata-'.$sdata.'/_digital/'.$k.$s.'.json'); $a = json_decode($q,true);
if(!isset($a['s'][base64_encode($u)]))
	{
	$a['s'][base64_encode($u)] = time();
	file_put_contents($d2.'_sdata-'.$sdata.'/_digital/'.$k.$s.'.json', json_encode($a));
	}
//
$j['download_url'] = $base.'/files/upload/'.$k2.$s.'.zip';
$j['version'] = $stable_tag;
echo json_encode($j);
?>