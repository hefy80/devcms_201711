//fengxf added begin:
cordova.define("org.apache.cordova.requestheader", function(require,exports,module){

    var exec = require('cordova/exec');

    module.exports = {	
		/** 
		 * 一共5个参数 
		   第一个 :成功回调 
		   第二个 :失败回调 
		   第三个 :将要调用的类的配置名字
		   第四个 :调用的方法名(一个类里可能有多个方法 靠这个参数区分) 
		   第五个 :传递的参数  以json的格式 
		 */  
        getJSON: function(appcode,params,appservercode,getRequestHeaderCallBack) {
			 alert("plugins → getJSON");
            exec(getRequestHeaderCallBack, null, "RequestHeader", "getJSON", [appcode,params,appservercode]);
        },
		
		get: function(appcode,params,appservercode,getRequestHeaderCallBack) {
			alert("plugins → get");
            exec(getRequestHeaderCallBack, null, "RequestHeader", "get", [appcode,params,appservercode]);
        },
		
		post:function(appcode,params,appservercode,getRequestHeaderCallBack) {
			alert("plugins → post");
            exec(getRequestHeaderCallBack, null, "RequestHeader", "post", [appcode,params,appservercode]);
        },
		postJSON:function(appcode,params,appservercode,getRequestHeaderCallBack) {
			alert("plugins → postJSON");
			exec(getRequestHeaderCallBack, null, "RequestHeader", "postJSON", [appcode,params,appservercode]);
        }

    };

});
//fengxf added end


