<?php
if(!isset($_SESSION['cmsuno'])) exit();
?>
<?php
if(file_exists('data/_sdata-'.$sdata.'/markdown.json'))
	{
	$q1 = file_get_contents('data/_sdata-'.$sdata.'/markdown.json');
	$a2 = json_decode($q1,true);
	$a1 = $a2[$Ubusy];
	$markdown = 0; $mdfoot = 0; $maj = 0;
	include('parsedown/Parsedown.php');
	if($a1['mdpars']=='extra')
		{
		include('parsedown-extra/ParsedownExtra.php');
		$Parsedown = new ParsedownExtra();
		}
	else $Parsedown = new Parsedown();
	if(isset($a1['md'])) foreach($a1['md'] as $k1=>$v1)
		{
		$out = 0; $price=0; $q2 = '';
		if(strstr($Uhtml,'[[markdown-'.$k1.']]') || strstr($Ucontent,'[[markdown-'.$k1.']]'))
			{
			// 1. import data
			if(isset($v1['u']) && file_exists('../'.$v1['u'])) $q2 = file_get_contents('../'.$v1['u']);
			else if(isset($v1['c'])) $q2 = $v1['c'];
			// 2. parse content
			// ************************************ WP ******************************************
			if($a1['mdpars']=="wp")
				{
				$q2 = str_replace("\r\n", "\n", $q2);
				$q2 = str_replace("\r", "\n", $q2);
				$q2 = str_replace("&lt;","<", $q2);
				$q2 = str_replace("&gt;",">", $q2);
				$r2 = explode("\n",$q2);
				$q2="";$me="";$ti="";$other=0;
				$requires_at_least=0;$tested_up_to=0;$stable_tag=0;$requires_php=0;$tags=0;$contributors=array();$donate_link=0;$license=0;$license_uri=0;$short_description=0;$cart=0;$mdfoot2='';
				foreach($r2 as $v2)
					{
					$v2 = rtrim($v2);
					if($v2=="") $v2 = ".";
					if(substr_count($v2, "`")==1 && substr_count($v2, "```")==0 && substr($v2,0,1)=="`") $v2 = str_replace("`","```\n", $v2);
					else if(substr_count($v2, "`")==1 && substr_count($v2, "```")==0 && substr($v2,-1)=="`") $v2 = str_replace("`","\n```\n", $v2);
					else if(substr_count($v2, "`")==2 && substr_count($v2, "```")==0 && substr($v2,0,1)=="`" && substr($v2,-1)=="`") $v2 = "\n```\n".substr($v2,1,-1)."\n```";
					if(strstr($v2,"===") && substr($v2,-3)=="===")
						{
						$r3 = explode("===",' '.$v2);
						if($ti!="")	$v2 = "# ";
						foreach($r3 as $k3=>$v3)
							{
							if($k3>0)
								{
								if($ti=="") $ti=trim($v3);
								else if(strstr($v2,"# ")) $v2.=$v3;
								}
							}
						}
					else if(strstr($v2,"==") && substr($v2,-2)=="==")
						{
						$r3 = explode("==",' '.$v2);
						$v2 = "## ";
						foreach($r3 as $k3=>$v3) {if($k3>0) $v2.=$v3;}
						if($me=="") $me = " "; // detection end bloc title
						}
					else if(strstr($v2,"=") && substr($v2,-1)=="=")
						{
						$r3 = explode("=",' '.$v2);
						$v2 = "### ";
						foreach($r3 as $k3=>$v3) {if($k3>0) $v2.=$v3;}
						}
					foreach(array('description','installation','frequently_asked_questions','screenshots','changelog','change_log','upgrade_notice','support') as $mn)
						{
						if(stristr($v2,str_replace('_',' ',$mn)) && substr($v2,0,2)=="##")
							{
							if($mn=='frequently_asked_questions') $me .= '<li class="section-'.$mn.(($me=="" || $me==" ")?' current':'').'" onClick="mdwpMenu(\''.$mn.'\',\''.$k1.'\')"><a href="javascript:void(0)">F.A.Q.</a></li>';
							else $me .= '<li class="section-'.$mn.(($me=="" || $me==" ")?' current':'').'" onClick="mdwpMenu(\''.$mn.'\',\''.$k1.'\')"><a href="javascript:void(0)">'.ucfirst($mn).'</a></li>';
							}
						else if(substr($v2,0,2)=="##") $other = '<li class="section-other" onClick="mdwpMenu(\'other\',\''.$k1.'\')"><a href="javascript:void(0)">Other</a></li>';
						}
					if($me=="" && $a1['mdcss']=="wp-org.css")
						{
						if(preg_match('|Requires at least:(.*)|i',$v2,$m)) $requires_at_least = trim($m[1]);
						else if(preg_match('|Tested up to:(.*)|i',$v2,$m)) $tested_up_to = trim($m[1]);
						else if(preg_match('|Stable tag:(.*)|i',$v2,$m)) $stable_tag = trim($m[1]);
						else if(preg_match('|Requires PHP:(.*)|i',$v2,$m)) $requires_php = trim($m[1]);
						else if(preg_match('|Tags:(.*)|i',$v2,$m)) $tags = trim($m[1]);
						else if(preg_match('|Contributors:(.*)|i',$v2,$m))
							{
							$m = preg_split('|,[\s]*|', trim($m[1]));
							foreach(array_keys($m) as $n)
								{
								$tm = trim($m[$n]);
								if(strlen(trim($tm))>0) $contributors[$n] = $tm;
								}
							}
						else if(preg_match('|Donate link:(.*)|i',$v2,$m)) $donate_link = $m[1];
						else if(preg_match('|License:(.*)|i',$v2,$m)) $license = $m[1];
						else if(preg_match('|License URI:(.*)|i',$v2,$m)) $license_uri = $m[1];
						else if(preg_match('|Price:(.*)|i',$v2,$m)) $price = preg_replace('#[^0-9\.]#','',str_replace(',','.',$m[1]));
						else if(!$short_description && !strstr($v2,":") && !strstr($v2,"=") && strlen($v2)>1) $short_description = substr(trim($v2), 0, 150);
						$v2 = "";
						}
					if($v2!="") $q2 .= (($v2=='.')?'':$v2)."\r\n";
					}
				if($a1['mdcss']=="wp-org.css")
					{
					if($price) $cart = '{"prod":{"0":{"n":"'.$ti.'","p":'.$price.',"i":"","q":1}},"digital":"'.$Ubusy.'|'.$k1.'","Ubusy":"'.$Ubusy.'"}';			
					$q3 = $Parsedown->text($q2);
					$out = '<div class="markdown markdown-'.$k1.'">'."\r\n".'<div class="col-10">'."\r\n";
					$out .= '<div class="plugin-title with-banner" style="background-image:url(files/'.$k1.'/banner-772x250.png);"><div class="vignette"></div><h2>'.$ti.'</h2></div><!-- #plugin-title -->'."\r\n";
					$out .= '<div class="plugin-description"><p class="shortdesc">'.($short_description?$short_description:'').'</p><div class="description-right"><p id="mdDop" class="button">';
					if($price && isset($a1['mdpay'])) $out .= '<a id="mdDoa" href="javascript:void(0)" onClick="'.$a1['mdpay'].'Cart(mdCart'.$k1.')">Download Version '.$stable_tag.'</a>';
					else $out .= '<a href="files/'.$k1.'/'.$v1['k'].$k1.'.zip">Download Version '.$stable_tag.'</a>';
					$out .= '</p></div></div><!-- .plugin-description-->'."\r\n";
					$out .= '<div class="col-7"><div class="plugin-info block">'."\r\n";
					if($cart)
						{
						$Ufoot .= "<script type=\"text/javascript\">var mdCart".$k1."='".$cart."';</script>\r\n";
						$mdfoot2 = "<script type=\"text/javascript\">function mdGvu(n){return decodeURIComponent((new RegExp('[?|&]'+n+'='+'([^&;]+?)(&|#|;|$)').exec(location.search)||[,\"\"])[1].replace(/\+/g,'%20'))||null;};";
						$mdfoot2 .= "function mdDig(dg){var exp,t=new Date(),s=dg.split('|'),x=new XMLHttpRequest(),params='n='+s[1]+'&r='+s[2];x.open('POST','uno/plugins/markdown/markdownDigital.php',true);x.setRequestHeader('Content-type','application/x-www-form-urlencoded;charset=utf-8');x.setRequestHeader('Content-length',params.length);x.setRequestHeader('Connection','close');x.onreadystatechange=function(){if(x.readyState==4&&x.status==200){if(x.responseText){var a=document.getElementById('mdDoa');a.href='files/upload/'+s[2]+s[1]+'.zip';a.onclick='';document.getElementById('mdDop').style.backgroundColor='#00e052';t.setTime(t.getTime()+(24*60*60*1000));exp='expires='+t.toUTCString();document.cookie='mdDigit='+dg+'; '+exp;}else setTimeout(function(){mdDig(dg)},2000);}};x.send(params);};";
						$mdfoot2 .= "if(dg=mdGvu('digit'))mdDig(dg);else{var co=document.cookie.split(';');if(co[0].search('mdDigit=')!=-1){co=co[0].split('=');mdDig(co[1]);}};</script>\r\n";
						}
					if($me!="")
						{
						$out .= '<div class="head head-big"><ul id="mdm-'.$k1.'" class="sections">'.$me.($other?$other:'').'<ul></div><!-- .head -->'."\r\n";
						if(!$mdfoot)
							{
							$mdfoot = "<script type=\"text/javascript\">function mdwpMenu(f,g){var a=document.getElementById('md-'+g).getElementsByTagName('div');for(var v=0;v<a.length;v++){if(a[v].id.search('mdtab-')!=-1){if(a[v].id.search(f)!=-1)a[v].style.display='block';else a[v].style.display='none';}};a=document.getElementById('mdm-'+g).getElementsByTagName('li');for(v=0;v<a.length;v++){if(a[v].className.search(f)!=-1)a[v].className='section-'+f+' current';else a[v].className=a[v].className.replace(' current','');};};function mdwpDownload(f){[[markdowndDL]]};markdowndDL=null;</script>"."\r\n";
							}
						}
					$out .= '<div id="md-'.$k1.'" class="block-content">'."\r\n".$q3.'</div><!-- .block-content -->'."\r\n"; // !! used bellow in str_replace !!
					$out .= '</div><!-- .plugin-info .block -->'."\r\n".'</div><!-- .col-7 -->'."\r\n";
					$out .= '</div><!-- .col-10 -->'."\r\n".'</div><!-- .markdown -->'."\r\n".'<div style="clear:both;"></div>'."\r\n";
					$o="";$b=0;$img=0;$ch=0;
					for($v=0;$v<strlen($out);++$v)
						{
						if(substr($out,$v,5)==chr(10).'<h2>')
							{
							$img=0;$ch=0;
							$c = "other";
							foreach(array('description','installation','frequently_asked_questions','screenshots','changelog','change_log','upgrade_notice','support') as $mn)
								{
								if(stristr(substr($out,$v,strlen($mn)+6),str_replace('_',' ',$mn))) $c = $mn;
								}
							if(stristr(substr($out,$v,15),'F.A.Q.')) $c = 'frequently_asked_questions';
							else if(stristr(substr($out,$v,20),'screenshots')) $img = -1;
							else if(stristr(substr($out,$v,20),'changelog') || stristr(substr($out,$v,20),'change_log')) $ch = 1;
							if(!$b) $o.='<div id="mdtab-'.$c.'">';
							else $o.='</div><div id="mdtab-'.$c.'" style="display:none;">';
							$b = 1;
							}
						if($img==-1 && substr($out,$v,4)=='<ol>') $img = 1;
						else if(substr($out,$v,5)=='</ol>') $img = 0;
						else if($ch==1 && substr($out,$v,4)=='<h3>') $out = substr($out,0,$v).'<h4>'.substr($out,$v+4);
						else if($ch==1 && substr($out,$v,5)=='</h3>') $out = substr($out,0,$v).'</h4>'.substr($out,$v+5);
						if($img>0 && substr($out,$v,5)==chr(10).'<li>')
							{
							$o.='<br /><a href="files/'.$k1.'/screenshot-'.$img.'.jpg" title="Click to view full-size screenshot '.$img.'"><img class="screenshot" src="files/'.$k1.'/screenshot-'.$img.'.jpg" alt="'.$ti.' screenshot '.$img.'" /></a>';
							++$img;
							}
						$o.=substr($out,$v,1);
						}
					if(!$b)$out = $o;
					else
						{
						$o1="";
						if($requires_at_least) $o1 .= '<strong>Requires at least:</strong> '.$requires_at_least."<br />"."\r\n";
						if($tested_up_to) $o1 .= '<strong>Tested up to:</strong> '.$tested_up_to."<br />"."\r\n";
						if($stable_tag) $o1 .= '<strong>Stable tag:</strong> '.$stable_tag."<br />"."\r\n";
						if($requires_php) $o1 .= '<strong>Requires PHP:</strong> '.$requires_php."<br />"."\r\n";
						if($tags) $o1 .= '<strong>Tags:</strong> '.$tags."<br />"."\r\n";
						if($donate_link) $o1 .= '<strong>Donate link:</strong><a href="'.$donate_link.'"> '.$donate_link."</a><br />"."\r\n";
						if($license) $o1 .= '<strong>License:</strong> '.$license."<br />"."\r\n";
						if($license_uri) $o1 .= '<strong>License URI:</strong><a href="'.$license_uri.'"> '.$license_uri."</a><br />"."\r\n";
						if($price) $o1 .= '<strong>Price:</strong> '.$price."<br />"."\r\n";
						if($o1!="") $out = str_replace('class="block-content">','class="block-content">'."\r\n".'<div id="mdtab-other" style="display:none;"><h2>'.$ti."</h2>\r\n<p>".$o1.'</p></div>',$o);
						$out = str_replace('<!-- .block-content',"\r\n".'</div><!-- .block-content',$out);
						}
					}
				}
			// **********************************************************************************
			if(!$out) $out = '<div class="markdown markdown-'.$k1.'">'.$Parsedown->text($q2).'</div><!-- .markdown -->'."\r\n".'<div style="clear:both;"></div>'."\r\n";
			// 3. publish result
			$Uhtml = str_replace('[[markdown-'.$k1.']]',$out,$Uhtml);
			$Ucontent = str_replace('[[markdown-'.$k1.']]',$out,$Ucontent);
			if(!$markdown && isset($a1['mdcss']) && $a1['mdcss']!='')
				{
				$Uhead .= '<link rel="stylesheet" href="uno/plugins/markdown/css/'.$a1['mdcss'].'" type="text/css" />'."\r\n";
				$markdown = 1;
				}
			}
		if($price && isset($v1['u']))
			{
			$a2[$Ubusy]['md'][$k1]['p'] = $price; // MAJ price
			$maj = 1;
			}
		}
	if($maj) file_put_contents('data/_sdata-'.$sdata.'/markdown.json', json_encode($a2)); // MAJ price
	if($mdfoot) $Ufoot .= $mdfoot;
	if(isset($mdfoot2) && $mdfoot2) $Ufoot .= $mdfoot2;
	}
?>
