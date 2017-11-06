/*
 * 记录访客日志的js
 * 
 */
		var start; 
		var end;
		var fostart; 
		function load() 
		{ 
		    start=new Date();
		    fostart = timestamptostr(start);    
		} 
		function unload() 
		{ 
			end=new Date(); 
		    var len = (end.getTime() - start.getTime()) / 1000; 
		    var img = new Image();
		    var uurl = geturl();
			var euurl = encodeURI(uurl);
		    img.src = _WANBU_+"/log.php?aa="+fostart+"&visitlength=" + len+"&visitpage="+euurl;
		}
		function timestamptostr(timestamp) {
		    d = new Date(timestamp.getTime());
		    var jstimestamp = (d.getFullYear())+"-"+(d.getMonth()+1)+"-"+(d.getDate())+" "+(d.getHours())+":"+(d.getMinutes())+":"+(d.getSeconds());
		    return jstimestamp;
		} 
		function geturl()
		{
		    var thisURL = document.URL; 
		    var thisHREF = document.location.href; 
		    var thisSLoc = self.location.href; 
		    var thisDLoc = document.location; 
		    var strwrite = thisURL; 
		    //strwrite += " thisHREF:  [" + thisHREF + "]<br />" 
		    //strwrite += " thisSLoc:  [" + thisSLoc + "]<br />" 
		    //strwrite += " thisDLoc:  [" + thisDLoc + "]<br />" 
		    return strwrite;
		}
		
		window.onload = load;
		window.onunload = unload;