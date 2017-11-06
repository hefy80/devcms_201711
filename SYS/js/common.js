var fileHost = "https://mam1.ft-power.com.cn:10013/aqscsj-web/aqscPicAction.do?method=downloadFjxx&UUID=";
//var fileHost = "http://127.0.0.1:7001/aqscsj-web/aqscPicAction.do?method=downloadFjxx&UUID=";

var AppInfo ={
	METHOD:'entry',
	METHOD_CODE:'AQSCSJAPP01',
	APP_CODE:'AQGK',
	APP_PIC_CODE:'AQSCSJPIC01',
	APP_SERVERCODE:'/aqscsj-web/aqscAction.do',
	DEBUG_URL:'http://127.0.0.1:7001/aqscsj-web/aqscAction.do?method=entry'
}
var callServiceGetData = function(data,callFun){
	if(loadScript&&loadScript.isDebug){
		$.ajax({
            type : 'GET',
            url : AppInfo.DEBUG_URL,
            data : data,
            dataType : 'jsonp',
            success : callFun,
            error :callFun
        });    
	}else{
		 data.method = AppInfo.METHOD;
		 mam.navigator.ajax.getJSON(AppInfo.APP_CODE, data, AppInfo.METHOD_CODE, callFun);
	}
}
if(loadScript&&loadScript.isDebug){
	window.onload= debugOnBrowser;
}

//打印Object对象
function obj2string(o){ 
	 var r=[]; 
	 if(typeof o=="string"){ 
	  return "\""+o.replace(/([\'\"\\])/g,"\\$1").replace(/(\n)/g,"\\n").replace(/(\r)/g,"\\r").replace(/(\t)/g,"\\t")+"\""; 
	 } 
	 if(typeof o=="object"){ 
	  if(!o.sort){ 
	   for(var i in o){ 
	    r.push(i+":"+obj2string(o[i])); 
	   } 
	   if(!!document.all&&!/^\n?function\s*toString\(\)\s*\{\n?\s*\[native code\]\n?\s*\}\n?\s*$/.test(o.toString)){ 
	    r.push("toString:"+o.toString.toString()); 
	   } 
	   r="{"+r.join()+"}"; 
	  }else{ 
	   for(var i=0;i<o.length;i++){ 
	    r.push(obj2string(o[i])) 
	   } 
	   r="["+r.join()+"]"; 
	  } 
	  return r; 
	 } 
	 return o.toString(); 
}
