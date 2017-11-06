/**
 * 长征详细数据
 * 
 */
/* 
var groupid2;
var datestd2;
var activeid;
var ggid;
var userid;
var date1;var date2;
*/

function pop_close(aa){
	if(aa=="p_d"){
		var box = new LightBox("p_detial");
	}
	else if(aa=="g_d"){
		var box = new LightBox("g_detial");
	}
	else if(aa=="pg_d"){
		var box = new LightBox("pg_detial");
	}else if(aa == "pg_desc"){
		var box = new LightBox("pg_desc");
	}else if(aa == "kuo_d"){
		var box = new LightBox("kuo_detial");
	}else if(aa == "pkuo_d"){
		var box = new LightBox("pkuo_detial");
	}else if(aa == "tj_jie"){
		var box = new LightBox("tj_info");
	}else if(aa=="tanchuang6"){
		var box = new LightBox("tanchuang6");
	}
	box.Close(); 
	}


function g_open(aid){ 
		jQuery.post(_URL_+'/AjaxHandle?type=p_d&aid='+aid,'',function (data){
			document.getElementById("p_detial").innerHTML = data;
		    var box = new LightBox("p_detial");
			box.Show();
        },"json");    
}

//团队多日祥单
function g_open2(aid,gid, p){ 
	//activeid=aid,
	//ggid=groupid;
	//groupid2 = groupid;
	if(!p)
		p = 1;
	if(gid && aid){
		$.get(_URL_+'/AjaxHandle', {aid:aid,gid:gid,p:p,ajaxFunName:"g_open2"},function (data){
			if(data.msg.result==null){
				alert ("暂无统计数据");
			}else{
				var str='';
				var newstr='';
				var tg_fh=data.msg.credit1p;var dh_xsh=data.msg.credit2distance;var groupname=data.msg.groupname; 
				var result=data.msg.result;
				str='<div style="width:320px;height:15px;color:red;"><span style="float:left; width:150px;text-align:left;">积分汇总：'+tg_fh+'分</span><span style="float:right; width:145px; text-align:right;padding-right:5px;_padding-right:3px;">1积分='+dh_xsh+'公里</span></div>';
				newstr=' <div class="pop6"></div>'+
							'<div class="pop6_content" style="_height:405px;">'+
							'<div class="pop6_bt"><span class="pop6_text">'+groupname+'积分明细</span>'+
							'<div onclick="pop_close(\'g_d\')"  class="pop_close" >'+
							'<img src="'+_TEMP_+'/images/pop_close.jpg" /></div></div>'+
							'<div class="pop6_con" style="_height:405px;"><div class="history_con" style="_height:405px;">'+
							'<table width="344" border="0" cellspacing="0" cellpadding="0" id="info">'+str+'<tr>'+
							'<td width="125" class="td_padten"><font color="#333333">日期</font></td>'+
							'<td width="121" class="td_164"><font color="#333333">基础积分</font></td>'+
							'<td width="70" class="td_right"><font color="#333333">奖励积分</font></td></tr>';
				for (i in result){
					var datestd = result[i]['walkdate'];
					newstr+= '<tr>'+
							'<td class="td_padten"><a href="javascript:void(0);" onclick="g_open3(\''+aid+'\',\''+gid+'\',\''+datestd+'\');">'+result[i]['walkdate']+'</a></td>'+
							'<td>'+result[i]['credit2']+'</td><td>'+result[i]['credit3']+'</td></tr>';
				}
				newstr+='</table><div class="onehundred_ranklist_listcon_page" id="page">'+data.msg.page+'</div></div></div></div>';
				document.getElementById("g_detial").innerHTML = newstr;
		    	var box = new LightBox("g_detial");
				box.Show();  
			}
			
        },"json");   
		}     
}

//团队单日多人的祥单
function g_open3(aid, gid, datestd, p){ 
	if(!p)
		p = 1;
	if(aid && gid && datestd){
		$.get(_URL_+'/AjaxHandle', {aid:aid, gid:gid, datestd:datestd, p:p, ajaxFunName:"g_open3"},function (data){
			if(data.msg.result==null||data.msg.result==''){
				alert ("暂无统计数据");
			}else{
				var str='';var newstr='';var dh_xsh=data.msg.credit2distance;var groupname=data.msg.groupname;var result=data.msg.result;
				str='<div style="width:320px;height:15px;text-align:right;color:red;padding-right:5px;_padding-right:3px;">1积分='+dh_xsh+'公里</div>';
				newstr='<div class="pop6"></div>'+
						'<div class="pop6_content" style="_height:405px;">'+
						'<div class="pop6_bt"><span class="pop6_text2">'+groupname+'积分明细（'+datestd+'）</span>'+
						'<div onclick="pop_close(\'pg_d\')"  class="pop_close2" >返回</div></div>'+
						'<div class="pop6_con" style="_height:405px;" ><div class="history_con" style="_height:405px;">'+
						'<table width="344" border="0" cellspacing="0" cellpadding="0" id="info">'+str+'<tr>'+
						'<td width="125" class="td_padten"><font color="#333333">姓名</font></td>'+
						'<td width="121" class="td_164"><font color="#333333">基础积分</font></td>'+
						'<td width="70" class="td_right"><font color="#333333">奖励积分</font></td></tr>';
				for (i in result){
					newstr+= '<tr><td class="td_padten"><a href="'+_WANBU_+'/myspace.php?uid='+result[i]['userid']+'" target="_blank">'+result[i]['username']+'</a></td>'+
					'<td>'+result[i]['credit2']+'</td><td>'+result[i]['creditm']+'</td></tr>';
				}
				newstr+='</table><div class="onehundred_ranklist_listcon_page" id="page">'+data.msg.page+'</div></div></div></div>';
				document.getElementById("pg_detial").innerHTML = newstr;
			    var box = new LightBox("pg_detial");
			    
				box.Show();  
			}
			
        },"json");   
		}     

}

//个人多日祥单
function g_open4(aid,uid,gid,p){ 
	//activeid=aid;ggid=gid;userid=uid;
	if(!p)
		p = 1;
	$.get(_URL_+'/AjaxHandle',{aid:aid,uid:uid,gid:gid,p:p,ajaxFunName:"g_open4"},function (data){
		if(data.msg==null){
			alert ("暂无统计数据！");
		}else{
			var nickname=data.msg.nickname;
			var credit1p=data.msg.credit1p;
			var credit2distance=data.msg.credit2distance;
			var result=data.msg.result;
			var str="<div style='width:320px;height:15px;color:red;'><span style='float:left; width:150px;text-align:left;'>积分汇总："+credit1p+"</span><span style='float:right; width:145px; text-align:right;padding-right:5px;_padding-right:3px;'>1积分="+credit2distance+"公里</span></div>";
			var newstr='<div class="pop6"></div>'+
						  '<div class="pop6_content">'+
						  '<div class="pop6_bt"><span class="pop6_text">'+nickname+'积分明细</span>'+
						  '<div onclick="pop_close(\'p_d\')"  class="pop_close" >'+
						  '<img src="'+_TEMP_+'/images/pop_close.jpg" />'+
						  '</div></div>'+
						  '<div class="pop6_con">'+
						  '<div class="history_con">'+
						  '<table width="344" border="0" cellspacing="0" cellpadding="0" id="info">'+
						  '<tr>'+str+'<td width="125" class="td_padten"><font color="#333333">日期</font></td>'+
						  '<td width="121" class="td_164"><font color="#333333">基础积分</font></td>'+
						  '<td width="70" class="td_right"><font color="#333333">奖励积分</font></td></tr>';
			for (i in result){
				newstr+= "<tr><td class='td_padten'>"+result[i]['walkdate']+"</td><td>"+result[i]['credit2']+"</td><td>"+result[i]['creditm']+"</td></tr>";
			}
			newstr+='</table><div class="onehundred_ranklist_listcon_page" id="page">'+data.msg.page+'</div></div></div></div>';			
			document.getElementById("p_detial").innerHTML = newstr;
	    	var box = new LightBox("p_detial");
			box.Show(); 
		}		 
    },"json");    
}

function g_open5(aid,gid,p){ 
	if(gid && aid){
		$.get(_URL_+'/AjaxHandle',{aid:aid,gid:gid,p:p,ajaxFunName:"g_open5"},function (data){
			if(data.msg.result==null||data.msg.result==''){
				alert("暂无统计数据");
			}else{
				var result=data.msg.result;
				var str='<div style="width:320px;height:15px;color:red;"><span style="float:left; width:160px;text-align:left;">扩军汇总：  '+data.msg.totnum+' 人</span></div>';
				var newstr='<div class="pop6"></div>'+
				'<div class="pop6_content">'+
				'<div class="pop6_bt"><span class="pop6_text">'+data.msg.groupname+'扩军明细</span>'+
				'<div onclick="pop_close(\'kuo_d\')"  class="pop_close" >'+
				'<img src="'+_TEMP_+'/images/pop_close.jpg" /></div></div>'+
				'<div class="pop6_con"><div class="history_con">'+
				'<table width="344" border="0" cellspacing="0" cellpadding="0" >'+str+'<tr>'+
				'<td width="125" class="td_padten"><font color="#333333">日期</font></td>'+
				'<td width="121" class="td_164"><font color="#333333">扩军人数</font></td>'+
				'<td width="70" class="td_right"><font color="#333333">操作</font></td></tr>';
				for (i in result){
					newstr += '<tr><td width=\"125\" class=\"td_padten\">'+
					'<a href=\"javascript:void(0);\" onclick=\"g_open6('+aid+','+gid+',\''+result[i]['initdate']+'\');\">'+result[i]['initdate']+'</a></td>'+
					'<td width=\"121\" class=\"td_164\">'+result[i]['k_count']+'</td><td  width=\"70\" class=\"td_right\">'+
					'<a href=\"javascript:void(0);\" onclick=\"g_open6('+aid+','+gid+',\''+result[i]['initdate']+'\');\">查看</a></td></tr>';
				}
				newstr +='</table><div class=\"onehundred_ranklist_listcon_page\">'+data.msg.page+'</div></div></div></div>';
				document.getElementById("kuo_detial").innerHTML = newstr;
		    	var box = new LightBox("kuo_detial");
				box.Show();
			}
	    },"json");  
	}     
}


function g_open6(aid,gid,datestd,p){ 
	if(gid){
		$.get(_URL_+'/AjaxHandle',{aid:aid,gid:gid,p:p,datestd:datestd,ajaxFunName:"g_open6"},function (data){
			if(data.msg.result==null||data.msg.result==''){
				alert("暂无统计数据");
			}else{
				var result=data.msg.result;
				var str='<div class="pop6"></div>'+
					'<div class="pop6_content"><div class="pop6_bt">'+
					'<span class="pop6_text2">'+data.msg.groupname+'新增人员（'+datestd+'）</span>'+
					'<div onclick="pop_close(\'pkuo_d\')"  class="pop_close2" >返回</div></div>'+
					'<div class="pop6_con"><div class="history_con">'+
					'<table width="344" border="0" cellspacing="0" cellpadding="0" ><tr>'+
					'<td width="125"><font color="#333333">姓名</font></td>'+
					'<td width="121"><font color="#333333"></font></td>'+
					'<td width="70"><font color="#333333"></font></td></tr>'; 	
				var mi = 1;
				str+= "<tr><td>";
				var total=result.length;
				for (i in result){
					if(mi%3==0&&total!=mi){
						str += "<a href='"+_WANBU_+"/myspace.php?uid="+result[i]['userid']+"' target='_blank'>"+result[i]['username']+"</a></td></tr><tr><td>";
					}else{
						str += "<a href='"+_WANBU_+"/myspace.php?uid="+result[i]['userid']+"' target='_blank'>"+result[i]['username']+"</a></td><td>";
					}
					mi ++;
				}
				str +='</td></tr></table><div class=\"onehundred_ranklist_listcon_page\">'+data.msg.page+'</div></div></div></div>';
				document.getElementById("pkuo_detial").innerHTML = str;
		    	var box = new LightBox("pkuo_detial");
				box.Show(); 
			}
	    },"json"); 
	}
}

function g_open7(){
	    var box = new LightBox("pg_desc");
		box.Show();    
}

/**
 * 小组页面 小组介绍
 * @author wangjc
 * @time   2012-8-27
 * @param  gname string 团队名
 * @param  description string 团队介绍
 */
function g_open_group_des( gname,description ){
	gname = decodeURIComponent(gname);
	description = decodeURIComponent(description);
	var html ='<div class="pop_bg"></div>'+'<div class="pop8_content">'+
			        '<div class="pop8_bt">'+
			          '<span class="pop8_text">'+gname+'</span><div class="pop_close" onclick="pop_close(\'pg_desc\')"><img src="'+_TEMP_+'/images/pop_close.jpg"></div>'+
			      '</div>'+
			        '<div class="pop8_t_benzhu">'+description+'</div>'+
			  '</div>';
	document.getElementById("pg_desc").innerHTML = html;
	var box = new LightBox("pg_desc");
	box.Show(); 
}