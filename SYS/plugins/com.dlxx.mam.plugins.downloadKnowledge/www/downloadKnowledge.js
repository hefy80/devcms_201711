//zhouq80 added begin:
cordova.define("org.apache.cordova.downloadKnowledge", function(require,exports,module){

    var exec = require('cordova/exec');

    module.exports = {	
		/** 
		 * 一共5个参数 
		   第一个 :成功回调 
		   第二个 :失败回调 
		   第三个 :url  文件下载url 例如getJSON请求的url,,,ssss/ssss.do?ssss.mp4
		   第四个 :localPath 文件保存在本地的路径，例如：lujing/
		   第五个 :fileName  文件名称  mmm.mp4
		   第六个 :appcode 应用编码
		   第七个 :appservercode 应用服务编码
		 */  

        down: function(successCallback,errorCallback,url,localPath,fileName,appcode,appservercode) {
			alert(99);
            exec(successCallback, errorCallback, "DownloadKnowledge", "startDownload", [url,fileName,localPath,appcode,appservercode]);
        },
/** 
		 * 一共5个参数 
		   第一个 :成功回调 
		   第二个 :失败回调 
		   第三个 :path 文件保存在本地的路径，例如：lujing/mmm.mp4
		 */  
		openByThirdPartyApp: function(successCallback,errorCallback,path) {
            exec(successCallback, errorCallback, "DownloadKnowledge", "openByThirdPartyApp",[path]);
        }
    };

});
//zhouq80 added end