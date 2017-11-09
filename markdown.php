<?php
session_start(); 
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])!='xmlhttprequest') {sleep(2);exit;} // ajax request
if(!isset($_POST['unox']) || $_POST['unox']!=$_SESSION['unox']) {sleep(2);exit;} // appel depuis uno.php
?>
<?php
include('../../config.php');
if (!is_dir('../../data/_sdata-'.$sdata.'/_digital/')) mkdir('../../data/_sdata-'.$sdata.'/_digital/',0711);
include('lang/lang.php');
$q = file_get_contents('../../data/busy.json'); $a = json_decode($q,true); $Ubusy = $a['nom'];
// ********************* actions *************************************************************************
if (isset($_POST['action']))
	{
	switch ($_POST['action'])
		{
		// ********************************************************************************************
		case 'plugin': ?>
		<link rel="stylesheet" type="text/css" media="screen" href="uno/plugins/markdown/markdown.css" />
		<div class="blocForm">
			<div id="markdownD" class="bouton fr" onClick="f_markdownDigital();" title="<?php echo T_("Digital Download");?>"><?php echo T_("Digital Download");?></div>
			<div id="markdownC" class="bouton fr current" onClick="f_markdownConfig();" title="<?php echo T_("Markdown");?>"><?php echo T_("Markdown");?></div>
			<h2><?php echo T_("Markdown");?></h2>
			<div id="markdownConfig">
				<p>
					<?php echo T_("This plugin allows you to display the formatted content of a MarkDown file or content in your page.");?>
					<?php echo T_("It use");?>&nbsp;<a href="https://github.com/erusev/parsedown">Parsedown</a>.
				</p>
				<p><?php echo T_("Just insert the code");?>&nbsp;<code>[[markdown-<?php echo T_("nameofthefileorcontent");?>]]</code>&nbsp;<?php echo T_("in the template or in the page content.");?></p>
				<p><?php echo T_("You can display many differents MarkDown files or content.");?></p>
				<p><a href="javascript:void(0)" onClick="f_more_markdown()"><?php echo T_("More details about WordPress readme file...");?></a></p>
				<div id="mdMore" style="display:none;">
					<p><?php echo T_("You can use this plugin to allow Paid download for a WordPress premium plugin. It's easy and appearance is identical to the plugin page available on wordpress.org.");?></p>
					</ul>
					<h4><?php echo T_("Rules");?></h4>
					<ol style="margin-left:50px;list-style-type:decimal;">
						<li><?php echo T_("The plugin file should be named"); ?>&nbsp;<span style="font-weight:700;">foo.zip</span>&nbsp;<?php echo T_("(name of the shortcode"); ?>&nbsp;[[markdown-foo]]).&nbsp;<?php echo T_("This name will be encrypted by CMSUno."); ?></li>
						<li><?php echo T_("The plugin file must be placed in"); ?>&nbsp;<span style="font-weight:700;">/foo</span>,&nbsp;<?php echo T_("with the finder, directly under files."); ?></li>
						<li><?php echo T_("Banner and screenshot must also be stored in files/foo.");?></li>
						<li><?php echo T_("Banner and screenshot retain the same name as wordpress.org.");?></li>
						<li><?php echo T_("If it's a paying plugin, a line must be added in the .txt file, under the title (with Tags, License...) :");?>&nbsp;<span style="font-weight:700;">Price: 30 euro</span></li>
						<li><?php echo T_("To add a support forum, add this chapter in .txt file :"); ?>&nbsp;<span style="font-weight:700;">== Support ==</span>&nbsp;<?php echo T_("with the contents"); ?>&nbsp;<span style="font-weight:700;">[[support]]</span>&nbsp;<?php echo T_("to call the support plugin."); ?></li>
					</ol>
				</div>
				<h3><?php echo T_("Options");?>&nbsp;:</h3>
				<table class="hForm">
					<tr>
						<td><label><?php echo T_("Parser");?></label></td>
						<td>
							<select name="markdownPars" id="markdownPars" onChange="f_markdownPay(this,document.getElementById('markdownCss'))">
								<option value="std"><?php echo T_("Standard MD");?></option>
								<option value="extra"><?php echo T_("MD with extra tags");?></option>
								<option value="wp"><?php echo T_("WordPress readme");?></option>
							</select>
						</td>
						<td><em><?php echo T_("Extra adds features not available in standard MD.");?>&nbsp;<?php echo T_("WordPress is an alternative used in readme.txt.");?></em></td>
					</tr>
					<tr>
						<td><label><?php echo T_("CSS");?></label></td>
						<td>
							<select name="markdownCss" id="markdownCss" onChange="f_markdownPay(document.getElementById('markdownPars'),this)">
								<option value=""><?php echo T_("No CSS");?></option>
								<option value="github-markdown.css">GitHub CSS</option>
								<option value="mrcoles-markdown.css">MrColes CSS</option>
								<option value="kevin-markdown.css">KevinBurke CSS</option>
								<option value="wp-org.css">WordPress.org CSS</option>
							</select>
						</td>
						<td><em><?php echo T_("CSS to style the HTML output.");?></em></td>
					</tr>
					<tr id="markdownTrPay" style="display:none">
						<td><label><?php echo T_("Payment system");?></label></td>
						<td>
							<select name="markdownPay" id="markdownPay">
								<option value="payment"><?php echo T_("multiple choice (Payment)");?></option>
								<option value="paypal">Paypal</option>
								<option value="payplug">Payplug</option>
							</select>
						</td>
						<td><em><?php echo T_("Only WordPress CSS and Parser. Used to sell a premium plugin. External used in selected plugin required.");?></em></td>
					</tr>
				</table>
				<div class="bouton fr" onClick="f_save_markdown(1);" title="<?php echo T_("Save settings");?>"><?php echo T_("Save");?></div>
				<div style="clear:both;"></div>
				<h3><?php echo T_("Add a content or a MD File :");?></h3>
				<table class="hForm">
					<tr>
						<td><label><?php echo T_("Name");?></label></td>
						<td><input type="text" class="input" name="markdownName" id="markdownName" style="width:80px;margin-right:20px;" value="" /></td>
						<td><em><?php echo T_("The name will be used in the shortcode : [[markdown-name]].");?></em></td>
					</tr>
					<tr>
						<td><label><?php echo T_("MD File");?></label></td>
						<td>
							<input type="text" class="input" name="markdownFile" id="markdownFile" value="" />
							<div class="bouton finder" style="margin-left:30px;" id="bFMarkdown" onClick="f_finder_select('markdownFile')" title="<?php echo T_("File manager");?>"><img src="<?php echo $_POST['udep']; ?>includes/img/finder.png" /></div>
						</td>
						<td></td>
					</tr>
					<tr>
						<td style="vertical-align:middle"><label><?php echo T_("MD Content");?></label></td>
						<td>
							<textarea class="input" style="width:100%" name="markdownCont" id="markdownCont" rows="5"></textarea>
						</td>
						<td style="vertical-align:middle"><em><?php echo T_("Add File or Content, not both !");?></em></td>
					</tr>
					<tr>
						<td></td>
						<td style="text-align:right">
							<div class="bouton" onClick="f_add_markdown(document.getElementById('markdownName').value,document.getElementById('markdownFile').value,document.getElementById('markdownCont').value,'! <?php echo T_("Error");?>')" title="<?php echo T_("Add this file");?>"><?php echo T_("Add");?></div>
						</td>
						<td></td>
					</tr>
				</table>
				<h3><?php echo T_("Selection");?> :</h3>
				<form id="frmMarkdown">
					<table id="curMarkdown"></table>
				</form>
				<br />
			</div>
			<div id="markdownDigital" style="display:none;">
				<select id="markdownSelF">
					<?php
					$q = file_get_contents('../../data/_sdata-'.$sdata.'/markdown.json'); $a = json_decode($q,true);
					$data = ',';
					foreach($a[$Ubusy]['md'] as $k=>$v)
						{
						echo '<option value="'.$k.'">'.$k.'</option>';
						$data .= $k .',';
						}
					?>
				</select>
				<div class="bouton" onClick="f_markdownKey('<?php echo T_("Create a file named key.php with this content");?> : ',document.getElementById('markdownSelF').options[document.getElementById('markdownSelF').selectedIndex].value);" title="<?php echo T_("Create new free Key");?>"><?php echo T_("Create new free Key");?></div>
				<div id="markdownK"></div>
				<h3><?php echo T_("Updates monitoring"); ?></h3>
				<p><?php echo T_("The updates are automatically blocked from 5 URL on the same key.");?></p>
				<?php
				$tab = array(); $d = '../../data/_sdata-'.$sdata.'/_digital/';
				if($dh=opendir($d))
					{
					while(($file = readdir($dh))!==false) { if ($file!='.' && $file!='..') $tab[] = $d.$file; }
					closedir($dh);
					}
				if(count($tab))
					{
					$b = array();
					foreach($tab as $r)
						{
						$q = @file_get_contents($r);
						$a = json_decode($q,true);
						if(strpos($data,','.$a['d'].',')!==false) $b[] = $a; // filtre
						}
					function sortTime($u1,$u2) {return (isset($u2['t'])?$u2['t']:0) - (isset($u1['t'])?$u1['t']:0);}
					usort($b, 'sortTime');
					if(count($b))
						{
						echo '<br /><table>';
						echo '<tr><th>'.T_("File").'</th><th>'.T_("Date of purchase").'</th><th>'.T_("Key").'</th><th>'.T_("Payment").'</th><th>'.T_("Detail").'</th><th>'.T_("Banish").'</th><th>'.T_("Del").'</th></tr>';
						foreach($b as $r)
							{
							if($r)
								{
								$pirate = 0;
								$o = '';
								$o .= '<td>'.(isset($r['d'])?$r['d']:'').'</td>';
								$o .= '<td>'.(isset($r['t'])?date("dMy H:i", $r['t']):'').'</td>';
								$o .= '<td>'.(isset($r['k'])?$r['k']:'').'</td>';
								$o .= '<td>'.(isset($r['p'])?$r['p']:'').'</td>';
								// DETAILS
								$o .= '<td>';
								if(isset($r['s']))
									{
									$c = 0;
									foreach($r['s'] as $k1=>$v1)
										{
										if(isset($r['k']) && isset($r['d'])) $s = ' class="suppUrl" style="cursor:pointer;background:transparent url(\''.$_POST['udep'].'includes/img/close.png\') no-repeat scroll center center;" onClick="f_suppUrl_markdownDigital(this,\''.$r['k'].$r['d'].'\',\''.$k1.'\')"';
										else $s = '';
										if(is_array($v1) && isset($v1['t']) && isset($v1['v'])) $o .= '<div>'.date("dMy H:i", $v1['t']).' (V'.$v1['v'].') : '.base64_decode($k1).'<span'.$s.'>&nbsp;</span></div>';
										else $o.= '<div>'.date("dMy H:i", $v1).' : '.base64_decode($k1).'<span'.$s.'>&nbsp;</span></div>';
										++$c;
										}
									if($c>4) $pirate = 1; // >4 URL => BLOCKED ! cf markdownUpdate.php
									}
								$o .= '</td>';
								// BANISH
								if(isset($r['k']) && isset($r['d']))
									{
									if(empty($r['b'])) $o .= '<td onClick="f_markdownBlock(this,\''.$r['k'].$r['d'].'\',\''.T_("Yes").'\',1)" class="yesno">'.T_("No").'</td>';
									else $o .= '<td onClick="f_markdownBlock(this,\''.$r['k'].$r['d'].'\',\''.T_("No").'\',0)" class="yesno">'.T_("Yes").'</td>';
									}
								else $o .= '<td></td>';
								// DEL
								if(empty($r['s']) && isset($r['k']) && isset($r['d'])) $o .= '<td width="30px" style="cursor:pointer;background:transparent url(\''.$_POST['udep'].'includes/img/close.png\') no-repeat scroll center center;" onClick="f_supp_markdownDigital(this,\''.$r['k'].$r['d'].'\')">&nbsp;</td>';
								else $o .= '<td></td>';
								//
								//
								echo '<tr class="'.(isset($r['d'])?$r['d']:'').((!empty($r['b'])||$pirate)?' pirate':((isset($r['s'])&&count($r['s'])>1)?' doute':'')).'">' . $o . '</tr>';
								}
							}
						echo '</table>';
						}
					}
				?>
			</div>
			<div class="clear"></div>
		</div>
		<?php break;
		// ********************************************************************************************
		case 'save':
		$key = 0; $a = false;
		if(file_exists('../../data/_sdata-'.$sdata.'/markdown.json'))
			{
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/markdown.json');
			$b = json_decode($q,true);
			$a = $b;
			}
		else $b = array();
		if(!isset($b[$Ubusy])) $b[$Ubusy] = array('mspars'=>'std','mdcss'=>'','mdpay'=>'payment');
		if(isset($b[$Ubusy]['md'])) unset($b[$Ubusy]['md']);
		foreach($_POST as $k=>$v)
			{
			if($k=='mdpars' || $k=='mdcss' || $k=='mdpay') $b[$Ubusy][$k] = $v;
			else if($k!='action' && $k!='unox')
				{
				if(substr($k,0,1)=='f') // file : u, k, p
					{
					$c = substr($k,1);
					$b[$Ubusy]['md'][$c]['u'] = $v;
					if(!isset($a[$Ubusy]['md'][$c]['k'])) $b[$Ubusy]['md'][$c]['k'] = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),0,16);
					else $b[$Ubusy]['md'][$c]['k'] = $a[$Ubusy]['md'][$c]['k'];
					$b[$Ubusy]['md'][$c]['p'] = 0; // prix modifie par Make
					}
				else if(substr($k,0,1)=='c') $b[$Ubusy]['md'][substr($k,1)]['c'] = $v; // content : c
				}
			}
		$out = json_encode($b);
		if(file_put_contents('../../data/_sdata-'.$sdata.'/markdown.json', $out)) echo T_('Backup performed');
		else echo '!'.T_('Impossible backup');
		break;
		// ********************************************************************************************
		case 'load':
		if(file_exists('../../data/_sdata-'.$sdata.'/markdown.json'))
			{
			$c = 0;
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/markdown.json');
			$a = json_decode($q,true);
			echo json_encode($a[$Ubusy]);
			// crypt file name (WP)
			if(isset($a[$Ubusy]['md'])) foreach($a[$Ubusy]['md'] as $k=>$v)
				{
				if(file_exists('../../../files/'.$k.'/'.$k.'.zip'))
					{
					rename('../../../files/'.$k.'/'.$k.'.zip','../../../files/'.$k.'/'.$v['k'].$k.'.zip');
					$sh = substr(sha1(substr($v['k'],0,8).date('z').date('Y')),0,10); // key for the day
					@copy('../../../files/'.$k.'/'.$v['k'].$k.'.zip', '../../../files/upload/'.$sh.$k.'.zip'); // Update Zip of the day (cf markdownUpdate.php)
					}
				if(!is_dir('../../../files/'.$k)) mkdir('../../../files/'.$k,0711);
				@chmod('../../../files/'.$k,0711);
				if(!file_exists('../../../files/'.$k.'/index.html')) file_put_contents('../../../files/'.$k.'/index.html', '<html></html>');
				if($a[$Ubusy]['mdpars']=='wp' && !file_exists('../../../files/'.$k.'/readme.txt') && file_exists('../../../'.$v['u']))
					{
					copy('../../../'.$v['u'], '../../../files/'.$k.'/readme.txt');
					unlink('../../../'.$v['u']);
					$a[$Ubusy]['md'][$k]['u'] = 'files/'.$k.'/readme.txt';
					$c = 1;
					}
				}
			if($c) file_put_contents('../../data/_sdata-'.$sdata.'/markdown.json', json_encode($a));
			}
		else echo 0;
		break;
		// ********************************************************************************************
		case 'edit':
		$name = isset($_POST['c'])?strip_tags($_POST['c']):false;
		if($name && file_exists('../../data/_sdata-'.$sdata.'/markdown.json'))
			{
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/markdown.json');
			$a = json_decode($q,true);
			$b = array('e'=>'!'.T_('Error'));
			if(isset($a[$Ubusy]['md'][$name]['c']))
				{
				$b['n'] = $name;
				$b['c'] = $a[$Ubusy]['md'][$name]['c'];
				}
			echo json_encode($b);
			}
		break;
		// ********************************************************************************************
		case 'block':
		$id = isset($_POST['id'])?strip_tags($_POST['id']):false;
		$i = isset($_POST['i'])?strip_tags($_POST['i']):false;
		$succes = 0;
		$q = @file_get_contents('../../data/_sdata-'.$sdata.'/_digital/'.$id.'.json');
		if($q)
			{
			$a = json_decode($q,true);
			$a['b'] = $i; // 1 ou 0
			$out = json_encode($a);
			if(file_put_contents('../../data/_sdata-'.$sdata.'/_digital/'.$id.'.json', $out))
				{
				echo T_('Treated');
				$succes = 1;
				}
			}
		if(!$succes) echo '!'.T_('Error');
		break;
		// ********************************************************************************************
		case 'key':
		$succes = 0;
		if(isset($_POST['key']) && isset($_POST['file']))
			{
			$k = strip_tags($_POST['key']);
			$d = strip_tags($_POST['file']);
			if(file_put_contents('../../data/_sdata-'.$sdata.'/_digital/'.$k.$d.'.json', '{"t":"'.time().'","p":"free","d":"'.$d.'","k":"'.$k.'"}'))
				{
				echo $k.'|'.T_('Treated');
				$succes = 1;
				}
			}
		if(!$succes) echo '000|'.'!'.T_('Error');
		break;
		// ********************************************************************************************
		case 'suppdigital':
		$d = isset($_POST['file'])?strip_tags($_POST['file']):false;
		if($d && file_exists('../../data/_sdata-'.$sdata.'/_digital/'.$d.'.json'))
			{
			unlink('../../data/_sdata-'.$sdata.'/_digital/'.$d.'.json');
			echo T_('Removed');
			}
		else echo '!'.T_('Error');
		break;
		// ********************************************************************************************
		case 'suppurl':
		$d = isset($_POST['file'])?strip_tags($_POST['file']):false;
		$u = isset($_POST['url'])?strip_tags($_POST['url']):false;
		$succes = 0;
		if($d && $u && file_exists('../../data/_sdata-'.$sdata.'/_digital/'.$d.'.json'))
			{
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/_digital/'.$d.'.json');
			if($q)
				{
				$a = json_decode($q,true);
				if(isset($a['s'][$u]))
					{
					unset($a['s'][$u]);
					if(file_put_contents('../../data/_sdata-'.$sdata.'/_digital/'.$d.'.json', json_encode($a)))
						{
						echo T_('Removed');
						$succes = 1;
						}
					}
				}
			}
		if(!$succes) echo '!'.T_('Error');
		break;
		// ********************************************************************************************
		}
	clearstatcache();
	exit;
	}
?>
