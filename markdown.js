//
// CMSUno
// Plugin Markdown
//
function f_save_markdown(f){
	jQuery(document).ready(function(){
		var h=jQuery('#frmMarkdown').serializeArray();
		h.push({name:'action',value:'save'});
		h.push({name:'unox',value:Unox});
		if(f==1){
			h.push({name:'mdpars',value:document.getElementById('markdownPars').options[document.getElementById('markdownPars').selectedIndex].value});
			h.push({name:'mdcss',value:document.getElementById('markdownCss').options[document.getElementById('markdownCss').selectedIndex].value});
			h.push({name:'mdpay',value:document.getElementById('markdownPay').options[document.getElementById('markdownPay').selectedIndex].value});
		}
		jQuery.post('uno/plugins/markdown/markdown.php',h,function(r){f_alert(r);});
	});
}
function f_add_markdown(f,g,h,j){
	var a,b,c,d,v;
	if(f&&f!='mdpars'&&f!='mdcss'&&f!='action'&&(g.length+h.length)>1){
		f=f.replace(/[^\w]/gi, '');
		if(g.length){
			j=g.split('/');
			g='';
			if(j.length)for(v=j.length-1;v>=0;v--){
				g=j[v]+g;
				if(j[v]=='files')v=0;
				else if(v>0)g='/'+g;
			}
		}
		else if(document.getElementsByName('c'+f).length){
			document.getElementById('i'+f).parentNode.removeChild(document.getElementById('i'+f));
			d=document.getElementsByName('c'+f)[0];
			d.parentNode.removeChild(d);
		}
		b=document.createElement('input');
		b.type='hidden';
		b.name=(g.length?'f':'c')+f;
		b.value=(g.length?g:h);
		document.getElementById('frmMarkdown').appendChild(b);
		a=document.getElementById('curMarkdown');
		b=document.createElement('tr');
		b.id='i'+f;
		c=document.createElement('td');
		c.innerHTML=f;
		c.style.width='110px';
		c.style.paddingLeft='40px';
		b.appendChild(c);
		c=document.createElement('td');
		c.innerHTML=(g.length?g:h.substr(0,128));
		c.style.paddingLeft='10px';
		if(!g.length)c.style.border='1px solid #555';
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
		c.onclick=function(){
			this.parentNode.parentNode.removeChild(this.parentNode);
			var d=document.getElementsByName((g.length?'f':'c')+f)[0];
			d.parentNode.removeChild(d);
			f_save_markdown(0);
		}
		b.appendChild(c);
		c=document.createElement('td');
		if(!g.length){
			d=document.createElement('div');
			d.style.backgroundImage='url('+Udep+'includes/img/ui-icons_444444_256x240.png)';
			d.style.backgroundPosition='-62px -110px';
			d.style.width='20px';
			d.style.height='20px';
			d.style.backgroundRepeat='no-repeat';
			c.style.cursor='pointer';
			c.onclick=function(){f_edit_markdown(f);}
			c.appendChild(d);
		}
		b.appendChild(c);
		a.appendChild(b);
		document.getElementById('markdownName').value='';
		document.getElementById('markdownFile').value='';
		document.getElementById('markdownCont').value='';
		f_save_markdown(0);
	}
	else f_alert(j);
}
function f_load_markdown(){
	jQuery(document).ready(function(){
		jQuery.ajax({type:'POST',url:'uno/plugins/markdown/markdown.php',data:{'action':'load','unox':Unox},dataType:'json',async:true,success:function(data){
			var v,t,to;
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
					t=document.getElementById("frmMarkdown");
					jQuery.each(d,function(k1,d1){
						if(typeof d1.u!="undefined"){ // file
							jQuery('#curMarkdown').append('<tr id="i'+k1+'"><td style="width:110px;padding-left:40px;">'+k1+'</td><td style="padding-left:10px;">'+d1.u+'</td><td style="padding-left:40px;padding-right:20px;">[[markdown-'+k1+']]</td><td style="width:20px;cursor:pointer;background:transparent url(\''+Udep+'includes/img/close.png\') no-repeat scroll center center;" onClick="this.parentNode.parentNode.removeChild(this.parentNode);d=document.getElementsByName(\'f'+k1+'\')[0];d.parentNode.removeChild(d);f_save_markdown(0);"></td><td></td></tr>');
							to=document.createElement('input');
							to.type='hidden';
							to.name='f'+k1;
							to.value=d1.u;
							t.appendChild(to);
						}
						else if(typeof d1.c!="undefined"){ // content
							jQuery('#curMarkdown').append('<tr id="i'+k1+'"><td style="width:110px;padding-left:40px;">'+k1+'</td><td style="padding-left:10px;border:1px solid #555;">'+d1.c.substr(0,128)+'</td><td style="padding-left:40px;padding-right:20px;">[[markdown-'+k1+']]</td><td style="width:20px;cursor:pointer;background:transparent url(\''+Udep+'includes/img/close.png\') no-repeat scroll center center;" onClick="this.parentNode.parentNode.removeChild(this.parentNode);d=document.getElementsByName(\'c'+k1+'\')[0];d.parentNode.removeChild(d);f_save_markdown(0);"></td><td onClick="f_edit_markdown(\''+k1+'\')"><div style="width:20px;height:20px;cursor:pointer;background:transparent url(\''+Udep+'includes/img/ui-icons_444444_256x240.png\') no-repeat scroll -64px -112px;"></div></td></tr>');
							to=document.createElement('input');
							to.type='hidden';
							to.name='c'+k1;
							to.value=d1.c;
							t.appendChild(to);
						}
					});
				}
			});
			f_markdownPay(document.getElementById("markdownPars"),document.getElementById("markdownCss"));
		}});
	});
}
function f_edit_markdown(f,g){
	jQuery.post('uno/plugins/markdown/markdown.php',{'action':'edit','unox':Unox,'c':f},function(r){
		var a=JSON.parse(r);
		if(typeof a.c!="undefined"){
			document.getElementById('markdownName').value=a.n;
			document.getElementById('markdownCont').value=a.c;
		}
		else if(typeof a.e!="undefined")f_alert(a.e);
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
	jQuery.post('uno/plugins/markdown/markdown.php',{'action':'block','unox':Unox,'id':g,'i':i},function(r){
		f_alert(r);
		if(r.substr(0,1)!='!'){
			f.innerHTML=h;
			if(i==0){f.onclick=function(){f_markdownBlock(f,g,'yes',1);};jQuery(f).parent().removeClass("pirate");}
			else{f.onclick=function(){f_markdownBlock(f,g,'no',0);};jQuery(f).parent().addClass("pirate");}
		}
	});
}
function f_markdownKey(f,g){
	jQuery.post('uno/plugins/markdown/markdown.php',{'action':'key','unox':Unox,'file':g,'key':Math.random().toString().substr(2)},function(r){s=r.split("|");f_alert(s[1]);
		document.getElementById('markdownK').innerHTML=f+'<strong style="font-weight:700;">&lt;?php $key = "'+s[0]+'"; ?&gt;</strong>';
	});
}
function f_more_markdown(){a=document.getElementById('mdMore');if(a.style.display=="none")a.style.display='block';else a.style.display='none';}
function f_supp_markdownDigital(f,g){
	f.parentNode.parentNode.removeChild(f.parentNode);
	jQuery.post('uno/plugins/markdown/markdown.php',{'action':'suppdigital','unox':Unox,'file':g},function(r){f_alert(r);});
}
function f_suppUrl_markdownDigital(f,g,h){
	f.parentNode.parentNode.removeChild(f.parentNode);
	jQuery.post('uno/plugins/markdown/markdown.php',{'action':'suppurl','unox':Unox,'file':g,'url':h},function(r){f_alert(r);});
}
function f_markdownPay(f,g){
	if(f.options[f.selectedIndex].value=='wp'&&g.options[g.selectedIndex].value=='wp-org.css')document.getElementById('markdownTrPay').style.display='';
	else document.getElementById('markdownTrPay').style.display='none';
}
//
f_load_markdown();
