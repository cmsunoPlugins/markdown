//
// CMSUno
// Plugin Markdown
//
function f_save_markdown(){
	jQuery(document).ready(function(){
		h=jQuery('#frmMarkdown').serializeArray();
		h.push({name:'action',value:'save'});
		h.push({name:'unox',value:Unox});
		h.push({name:'mdpars',value:document.getElementById('markdownPars').options[document.getElementById('markdownPars').selectedIndex].value});
		h.push({name:'mdcss',value:document.getElementById('markdownCss').options[document.getElementById('markdownCss').selectedIndex].value});
		h.push({name:'mdpay',value:document.getElementById('markdownPay').options[document.getElementById('markdownPay').selectedIndex].value});
		jQuery.post('uno/plugins/markdown/markdown.php',h,function(r){f_alert(r);});
	});
}
function f_add_markdown(f,g,h){
	if(f&&f!='mdpars'&&f!='mdcss'&&f!='action'&&g.length>1){
		h=g.split('/');
		g='';
		if(h.length)for(v=h.length-1;v>=0;v--){
			g=h[v]+g;
			if(h[v]=='files')v=0;
			else if(v>0)g='/'+g;
		}
		b=document.createElement('input');
		b.type='hidden';
		b.name=f;
		b.value=g;
		document.getElementById('frmMarkdown').appendChild(b);
		a=document.getElementById('curMarkdown');
		b=document.createElement('tr');
		c=document.createElement('td');
		f=f.replace(/[^\w]/gi, '');
		c.innerHTML=f;
		c.style.width='110px';
		c.style.paddingLeft='40px';
		b.appendChild(c);
		c=document.createElement('td');
		c.innerHTML=g;
		c.style.paddingLeft='10px';
		b.appendChild(c);
		c=document.createElement('td');
		c.innerHTML='[[markdown-'+f+']]';
		c.style.paddingLeft='40px';
		c.style.paddingRight='20px';
		b.appendChild(c);
		c=document.createElement('td');
		c.style.backgroundImage='url('+Udep+'includes/img/close.png)';
		c.style.backgroundPosition='center center';
		c.style.backgroundRepeat='no-repeat';
		c.style.cursor='pointer';
		c.width='30px';
		c.onclick=function(){this.parentNode.parentNode.removeChild(this.parentNode);d=document.getElementsByName(f)[0];d.parentNode.removeChild(d);}
		b.appendChild(c);
		a.appendChild(b);
		document.getElementById('markdownName').value='';
		document.getElementById('markdownInp').value='';
	}
	else f_alert(h);
}
function f_load_markdown(){
	jQuery(document).ready(function(){
		jQuery.ajax({type:'POST',url:'uno/plugins/markdown/markdown.php',data:{'action':'load','unox':Unox},dataType:'json',async:true,success:function(data){
			jQuery.each(data,function(k,d){
				if(k=='mdpars'){
					t=document.getElementById("markdownPars");
					to=t.options;
					for(v=0;v<to.length;v++){if(to[v].value==d){to[v].selected=true;v=to.length;}}
				}
				else if(k=='mdcss'){
					t=document.getElementById("markdownCss");
					to=t.options;
					for(v=0;v<to.length;v++){if(to[v].value==d){to[v].selected=true;v=to.length;}}
				}
				else if(k=='mdpay'){
					t=document.getElementById("markdownPay");
					to=t.options;
					for(v=0;v<to.length;v++){if(to[v].value==d){to[v].selected=true;v=to.length;}}
				}
				else if(k=='md'){
					jQuery.each(d,function(k1,d1){
						jQuery('#curMarkdown').append('<tr><td style="width:110px;padding-left:40px;">'+k1+'</td><td style="padding-left:10px;">'+d1.u+'</td><td style="padding-left:40px;padding-right:20px;">[[markdown-'+k1+']]</td><td width="30px" style="cursor:pointer;background:transparent url(\''+Udep+'includes/img/close.png\') no-repeat scroll center center;" onClick="this.parentNode.parentNode.removeChild(this.parentNode);d=document.getElementsByName(\''+k1+'\')[0];d.parentNode.removeChild(d);"></td></tr>');
						jQuery('#frmMarkdown').append('<input type="hidden" name="'+k1+'" value="'+d1.u+'" />');
					});
				}
			});
		}});
	});
}
function f_markdownDigital(){
	document.getElementById('markdownDigital').style.display="block";
	document.getElementById('markdownConfig').style.display="none";
	document.getElementById('markdownD').className="bouton fr current";
	document.getElementById('markdownC').className="bouton fr";
	jQuery("#markdownK").empty();
}
function f_markdownConfig(){
	document.getElementById('markdownDigital').style.display="none";
	document.getElementById('markdownConfig').style.display="block";
	document.getElementById('markdownD').className="bouton fr";
	document.getElementById('markdownC').className="bouton fr current";
}
function f_markdownBlock(f,g,h,i){
	jQuery.post('uno/plugins/markdown/markdown.php',{'action':'block','unox':Unox,'id':g,'ban':h,'i':i},function(r){f_alert(r);});
	if(h!=0){
		f.innerHTML=h;
		if(i==0){f.onclick=function(){f_markdownBlock(f,g,'yes',1);};jQuery(f).parent().removeClass("pirate");}
		else {f.onclick=function(){f_markdownBlock(f,g,'no',0);};jQuery(f).parent().addClass("pirate");}
	}
	else jQuery(f).parent().remove();
}
function f_markdownKey(f,g){
	jQuery.post('uno/plugins/markdown/markdown.php',{'action':'key','unox':Unox,'file':g,'key':Math.random().toString().substr(2)},function(r){s=r.split("|");f_alert(s[1]);
		alert(s[0]);document.getElementById('markdownK').innerHTML=f+'<strong style="font-weight:700;">&lt;?php $key = "'+s[0]+'"; ?&gt;</strong>';
	});
}
function f_more_markdown(){a=document.getElementById('mdMore');if(a.style.display=="none")a.style.display='block';else a.style.display='none';}
//
f_load_markdown();