// add:archmind 15:44 2014/4/22
// author:zhouxuesong
// 说明:通过此类，可以通过Cordova提供的"com.dlxx.mam.plugins.DLFileTransferCordovaPlugin"类型插件启动调用电话应用相关的功能

cordova.define("org.apache.cordova.dlfiletransfer", function(require,exports,module){
    var exec = require('cordova/exec');
    //引入后面定义的PhoneError类
	var DLFileTransferError = require('org.apache.cordova.DLFileTransferError');
	
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
        		//console.log("====================");
    			errorCallback(new DLFileTransferError(code));
    		};
            exec(successCallback,fail, "DLFileTransferCordovaPlugin", "action_file_upload",[filePath]);
        }
    };
});
