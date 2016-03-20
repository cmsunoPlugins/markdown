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
			<div id="markdownD" class="bouton fr" onClick="f_markdownDigital();" title="<?php echo _("Digital Download");?>"><?php echo _("Digital Download");?></div>
			<div id="markdownC" class="bouton fr current" onClick="f_markdownConfig();" title="<?php echo _("Markdown");?>"><?php echo _("Markdown");?></div>
			<h2><?php echo _("Markdown");?></h2>
			<div id="markdownConfig">
				<p>
					<?php echo _("This plugin allows you to display the formatted content of a MarkDown file in your page.");?>
					<?php echo _("It use");?>&nbsp;<a href="https://github.com/erusev/parsedown">Parsedown</a>.
				</p>
				<p><?php echo _("Just insert the code");?>&nbsp;<code>[[markdown-<?php echo _("nameofthefile");?>]]</code>&nbsp;<?php echo _("in the template or in the page content.");?></p>
				<p><?php echo _("You can display many differents MarkDown files.");?></p>
				<p><a href="javascript:void(0)" onClick="f_more_markdown()"><?php echo _("More details about WordPress readme file...");?></a></p>
				<div id="mdMore" style="display:none;">
					<p><?php echo _("You can use this plugin to allow Paid download for a WordPress premium plugin. It's easy and appearance is identical to the plugin page available on wordpress.org.");?></p>
					</ul>
					<h4><?php echo _("Rules");?></h4>
					<ol style="margin-left:50px;list-style-type:decimal;">
						<li><?php echo _("The plugin file should be named"); ?>&nbsp;<span style="font-weight:700;">foo.zip</span>&nbsp;<?php echo _("(name of the shortcode"); ?>&nbsp;[[markdown-foo]]).&nbsp;<?php echo _("This name will be encrypted by CMSUno."); ?></li>
						<li><?php echo _("The plugin file must be placed in"); ?>&nbsp;<span style="font-weight:700;">/foo</span>,&nbsp;<?php echo _("with the finder, directly under files."); ?></li>
						<li><?php echo _("Banner and screenshot must also be stored in files/foo.");?></li>
						<li><?php echo _("Banner and screenshot retain the same name as wordpress.org.");?></li>
						<li><?php echo _("If it's a paying plugin, a line must be added in the .txt file, under the title (with Tags, License...) :");?>&nbsp;<span style="font-weight:700;">Price: 30 euro</span></li>
						<li><?php echo _("To add a support forum, add this chapter in .txt file :"); ?>&nbsp;<span style="font-weight:700;">== Support ==</span>&nbsp;<?php echo _("with the contents"); ?>&nbsp;<span style="font-weight:700;">[[support]]</span>&nbsp;<?php echo _("to call the support plugin."); ?></li>
					</ol>
				</div>
				<h3><?php echo _("Options");?>&nbsp;:</h3>
				<table class="hForm">
					<tr>
						<td><label><?php echo _("Parser");?></label></td>
						<td>
							<select name="markdownPars" id="markdownPars">
								<option value="std"><?php echo _("Standard MD");?></option>
								<option value="extra"><?php echo _("MD with extra tags");?></option>
								<option value="wp"><?php echo _("WordPress readme");?></option>
							</select>
						</td>
						<td><em><?php echo _("Extra adds features not available in standard MD.");?>&nbsp;<?php echo _("WordPress is an alternative used in readme.txt.");?></em></td>
					</tr>
					<tr>
						<td><label><?php echo _("CSS");?></label></td>
						<td>
							<select name="markdownCss" id="markdownCss">
								<option value=""><?php echo _("No CSS");?></option>
								<option value="github-markdown.css">GitHub CSS</option>
								<option value="mrcoles-markdown.css">MrColes CSS</option>
								<option value="kevin-markdown.css">KevinBurke CSS</option>
								<option value="wp-org.css">WordPress.org CSS</option>
							</select>
						</td>
						<td><em><?php echo _("CSS to style the HTML output.");?></em></td>
					</tr>
					<tr>
						<td><label><?php echo _("Payment system");?></label></td>
						<td>
							<select name="markdownPay" id="markdownPay">
								<option value="payment"><?php echo _("multiple choice");?></option>
								<option value="paypal">Paypal</option>
								<option value="payplug">Payplug</option>
							</select>
						</td>
						<td><em><?php echo _("Only WordPress CSS and Parser. Used to sell a premium plugin. External used in selected plugin required.");?></em></td>
					</tr>
				</table>
				<h3><?php echo _("Add a MD File :");?></h3>
				<table class="hForm">
					<tr>
						<td><label><?php echo _("Name of the file");?></label></td>
						<td><input type="text" class="input" name="markdownName" id="markdownName" style="width:80px;margin-right:20px;" value="" /></td>
					</tr>
					<tr>
						<td><label><?php echo _("MD File");?></label></td>
						<td>
							<input type="text" class="input" name="markdownInp" id="markdownInp" value="" />
							<div class="bouton finder" style="margin-left:30px;" id="bFMarkdown" onClick="f_finder_select('markdownInp')" title="<?php echo _("File manager");?>"><img src="<?php echo $_POST['udep']; ?>includes/img/finder.png" /></div>
							<div class="bouton" onClick="f_add_markdown(document.getElementById('markdownName').value,document.getElementById('markdownInp').value,'! <?php echo _("Wrong format");?>')" title="<?php echo _("Add this file");?>"><?php echo _("Add");?></div>
						</td>
					</tr>
				</table>
				<h3><?php echo _("Selection");?> :</h3>
				<form id="frmMarkdown">
					<table id="curMarkdown"></table>
				</form>
				<div class="bouton fr" onClick="f_save_markdown();" title="<?php echo _("Save settings");?>"><?php echo _("Save");?></div>
			</div>
			<div id="markdownDigital" style="display:none;">
			<select id="markdownSelF">
				<?php
				$q = file_get_contents('../../data/_sdata-'.$sdata.'/markdown.json'); $a = json_decode($q,true); $data = ',';
				foreach($a[$Ubusy]['md'] as $k=>$v)
					{
					echo '<option value="'.$k.'">'.$k.'</option>';
					$data .= $k .',';
					}
				?>
			</select>
			<div class="bouton" onClick="f_markdownKey('<?php echo _("Create a file named key.php with this content");?> : ',document.getElementById('markdownSelF').options[document.getElementById('markdownSelF').selectedIndex].value);" title="<?php echo _("Create new free Key");?>"><?php echo _("Create new free Key");?></div>
			<div id="markdownK"></div>
			<h3><?php echo _("Updates monitoring"); ?></h3>
			<p><?php echo _("The updates are automatically blocked from 4 URL on the same key.");?></p>
			<?php
			$tab=''; $d='../../data/_sdata-'.$sdata.'/_digital/';
			if ($dh=opendir($d))
				{
				while (($file = readdir($dh))!==false) { if ($file!='.' && $file!='..') $tab[]=$d.$file; }
				closedir($dh);
				}
			if(count($tab))
				{
				$b = array();
				foreach($tab as $r)
					{
					$q=@file_get_contents($r);
					$a=json_decode($q,true);
					if(strpos($data,','.$a['d'].',')!==false) $b[]=$a; // filtre
					}
				function sortTime($u1,$u2) {return (isset($u2['t'])?$u2['t']:0) - (isset($u1['t'])?$u1['t']:0);}
				usort($b, 'sortTime');
				if(count($b))
					{
					echo '<br /><table>';
					echo '<tr><th>'._("File").'</th><th>'._("Date of purchase").'</th><th>'._("Key").'</th><th>'._("Payment").'</th><th>'._("Detail").'</th><th>'._("Banish").'</th><th>'._("Del").'</th></tr>';
					foreach($b as $r)
						{
						if($r)
							{
							echo '<tr class="'.(isset($r['d'])?$r['d']:'').((isset($r['b'])&&$r['b'])?' pirate':((isset($r['s'])&&count($r['s'])>1)?' doute':'')).'">';
							echo '<td>'.(isset($r['d'])?$r['d']:'').'</td>';
							echo '<td>'.(isset($r['t'])?date("dMy H:i", $r['t']):'').'</td>';
							echo '<td>'.(isset($r['k'])?$r['k']:'').'</td>';
							echo '<td>'.(isset($r['p'])?$r['p']:'').'</td>';
							echo '<td>';
							if(isset($r['s']))
								{
								$c = 0;
								foreach($r['s'] as $k1=>$v1)
									{
									if($c) echo '<br />';
									if(is_array($v1) && isset($v1['t']) && isset($v1['v'])) echo date("dMy H:i", $v1['t']).' (V'.$v1['v'].') : '.base64_decode($k1);
									else echo date("dMy H:i", $v1).' : '.base64_decode($k1);
									++$c;
									}
								}
							echo '</td>';
							if(isset($r['k']) && isset($r['d']))
								{
								if(isset($r['p']) && $r['p']!="free")
									{
									if(!isset($r['b'])||!$r['b']) echo '<td onClick="f_markdownBlock(this,\''.$r['k'].$r['d'].'\',\''._("Yes").'\',1)" class="yesno">'._("No").'</td>';
									else echo '<td onClick="f_markdownBlock(this,\''.$r['k'].$r['d'].'\',\''._("No").'\',0)" class="yesno">'._("Yes").'</td>';
									}
								else echo '<td '.((!isset($r['b'])||!$r['b'])?'onClick="f_markdownBlock(this,\''.$r['k'].$r['d'].'\',0,0)"':'').' class="yesno">'._("Del").'</td>';
								}
							else echo '<td></td>';
							if(!isset($r['s']) && isset($r['k']) && isset($r['d'])) echo '<td width="30px" style="cursor:pointer;background:transparent url(\''.$_POST['udep'].'includes/img/close.png\') no-repeat scroll center center;" onClick="f_supp_markdownDigital(this,\''.$r['k'].$r['d'].'\')">&nbsp;</td>';
							else echo '<td></td>';
							echo '</tr>';
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
		$key=0; $a = false;
		if(file_exists('../../data/_sdata-'.$sdata.'/markdown.json'))
			{
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/markdown.json');
			$b = json_decode($q,true);
			$a = $b;
			if(isset($b[$Ubusy])) unset($b[$Ubusy]);
			}
		else $b = array();
		foreach($_POST as $k=>$v)
			{
			if($k!='action' && $k!='mdpars' && $k!='mdcss' && $k!='mdpay' && $k!='unox')
				{
				$b[$Ubusy]['md'][$k]['u'] = $v;
				if(!isset($a[$Ubusy]['md'][$k]['k'])) $b[$Ubusy]['md'][$k]['k'] = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),0,16);
				else $b[$Ubusy]['md'][$k]['k'] = $a[$Ubusy]['md'][$k]['k'];
				$b[$Ubusy]['md'][$k]['p'] = 0; // prix modifie par Make
				}
			else if($k!='action') $b[$Ubusy][$k] = $v;
			}
		$out = json_encode($b);
		if(file_put_contents('../../data/_sdata-'.$sdata.'/markdown.json', $out)) echo _('Backup performed');
		else echo '!'._('Impossible backup');
		break;
		// ********************************************************************************************
		case 'load':
		if(file_exists('../../data/_sdata-'.$sdata.'/markdown.json'))
			{
			$c = 0;
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/markdown.json');
			$a = json_decode($q,true);
			echo stripslashes(json_encode($a[$Ubusy]));
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
				chmod('../../../files/'.$k,0711);
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
		case 'block':
		$q = @file_get_contents('../../data/_sdata-'.$sdata.'/_digital/'.$_POST['id'].'.json');
		if(!$_POST['ban'])
			{
			unlink('../../data/_sdata-'.$sdata.'/_digital/'.$_POST['id'].'.json');
			echo _('Removed');
			}
		else
			{
			if($q)
				{
				$a = json_decode($q,true);
				$a['b'] = $_POST['i']; // 1 ou 0
				}
			$out = json_encode($a);
			if (file_put_contents('../../data/_sdata-'.$sdata.'/_digital/'.$_POST['id'].'.json', $out)) echo _('Treated');
			else echo '!'._('Error');
			}
		break;
		// ********************************************************************************************
		case 'key':
		if(isset($_POST['key']) && isset($_POST['file']))
			{
			$k = $_POST['key'];
			$d = $_POST['file'];
			if(file_put_contents('../../data/_sdata-'.$sdata.'/_digital/'.$k.$d.'.json', '{"t":"'.time().'","p":"free","d":"'.$d.'","k":"'.$k.'"}')) echo $k.'|'._('Treated');
			else echo '000|'.'!'._('Error');
			}
		else echo '000|'.'!'._('Error');
		break;
		// ********************************************************************************************
		case 'suppdigital':
		if(file_exists('../../data/_sdata-'.$sdata.'/_digital/'.$_POST['file'].'.json'))
			{
			unlink('../../data/_sdata-'.$sdata.'/_digital/'.$_POST['file'].'.json');
			echo _('Removed');
			}
		else echo '!'._('Error');
		break;
		// ********************************************************************************************
		}
	clearstatcache();
	exit;
	}
?>
