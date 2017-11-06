// add:archmind 15:44 2014/4/22
// author:zhouxuesong
// 说明:通过此类，
cordova.define("org.apache.cordova.dlfilebreakpointtransfer", function(require,exports,module){
    var exec = require('cordova/exec');
    var DLFileTransferError = require('org.apache.cordova.DLFileTransferError');
    //引入后面定义的PhoneError类	
	/** 
	 * 一共5个参数 
	   第一个 :成功回调 
	   第二个 :失败回调 
	   第三个 :将要调用的类的配置名字(在config.xml中配置) 
	   第四个 :调用的方法名(一个类里可能有多个方法 靠这个参数区分) 
	   第五个 :传递的参数  以json的格式 
	 */  
    module.exports = {
        fileUpload:function(successCallback,errorCallback,filePath) {
        	//定义执行错误时回调的方法
        	var fail = function(code){
        		console.log("====fileUpload() fail==============");
    			errorCallback(new DLFileTransferError(code));
    		};
            exec(successCallback,fail, "DLFileBreakpointsTransferCordovaPlugin", "action_file_upload",[filePath]);
             },
        
		    pause:function(successCallback,errorCallback){
			      exec(successCallback,errorCallback, "DLFileBreakpointsTransferCordovaPlugin", "action_file_pause",[]);
		     },
		
	        progress:function(successCallback,errorCallback){
			      exec(successCallback,errorCallback, "DLFileBreakpointsTransferCordovaPlugin", "action_file_progress",[]);
		     },
		     
    };
});
