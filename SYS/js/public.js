var dataurl='http://pubtest.js.sgcc.com.cn/dlsh_web/ajax/api.do';

//设置js方法调用的父对象为mam
var mam = {navigator:navigator}

function loading(){
	if($('#loaddiv').length<=0){
		$(document.body).append('<div id="loaddiv" style="text-align:center;position:fixed;top:100px;width:100%;"><div style="display:inline-block;padding:10px;border-radius:5px;"><img src="images/loading.gif" style="width:50px;" /></div></div>');
	}
	$('#loaddiv').show();
}


function loading_hide(){
	$('#loaddiv').hide();
}

/*读取模板*/
function getTemp(filename,res){
	var cc=$('#'+filename).html();
	for(i in res){
		var reg=new RegExp("\\$"+i,"gi");
		cc=cc.replace(reg,res[i]);
	}
	return cc;
}


function msg(v){
	if($('#msgbox').length<=0){
		var content='<div id="msgbox" style="position:fixed;top:50px;width:100%;text-align:center;">'+
					'	<div style="display:inline-block;width:90%;background:#fff;box-shadow:0px 0px 5px #999;padding:5px;text-align:left;overflow:hidden;">'+
					'		<div id="msgbody">'+v+'</div>'+
					'		<div style="text-align:center;padding-top:5px;border-top:1px solid #ccc;margin-top:5px;"><a class="button" onclick="msg_hide();"><span>关闭</span></a></div>'+
					'	</div>'+
					'</div>';
		$(document.body).append(content);
	}else{
		$('#msgbody').html(v);
	}
		
	$('#msgbox').show();
}

function msg_hide(){
	$('#msgbox').hide();
}

var deviceready=0;
window.onload=function(){
	document.addEventListener("deviceready", onDeviceReady, false);
}

function get_obj_position(e)
{ 
	var t=e.offsetTop; 
	var l=e.offsetLeft; 
	var w=e.offsetWidth;
	var h=e.offsetHeight;
	while(e=e.offsetParent)
	{ 
		t+=e.offsetTop; 
		l+=e.offsetLeft; 
	}
	var re=new Array();
	re[0]=t;
	re[1]=l;
	re[2]=w;
	re[3]=h;
	return re;
}
function $$(o){
	return document.getElementById(o);
}

function userchanged(obj){
	consNo=obj.consNo;
	consName = obj.consName;
	elecAddr=obj.elecAddr;
	window.location.reload();
}

function getqueryvalue(v){
	var s=window.location.search;
	var para=s.split('?')[1];
	if(para){
	para=para.split('&');
	for(i=0;i<para.length;i++){
		para[i]=para[i].split('=');
		if(para[i][0]==v){
			return para[i][1];
		}
	}
	}
	return '';
}

function addlink(path){
		var head = document.getElementsByTagName('head')[0];
        var link = document.createElement('link');
        link.href = path;
        link.rel = 'stylesheet';
        link.type = 'text/css';
        head.appendChild(link);
}
$(document).ready(function(){
	var cssname=getqueryvalue('c');
	if(cssname!=''){
		addlink('style/'+cssname+'.css');
	}

});