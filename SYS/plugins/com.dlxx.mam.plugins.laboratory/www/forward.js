// add:archmind 15:44 2014/4/22
// author:zhouxuesong

cordova.define("org.apache.cordova.forward", function(require,exports,module){
    var exec = require('cordova/exec');
	/** 
	 * 一共5个参数 
	   第一个 :成功回调 
	   第二个 :失败回调 
	   第三个 :将要调用的类的配置名字(在config.xml中配置) 
	   第四个 :调用的方法名(一个类里可能有多个方法 靠这个参数区分) 
	   第五个 :传递的参数  以json的格式 
	 */ 
    module.exports = {
	    // 跳转到扫描二维码activity 
        goToScanTwoDiemCode:function(successCallback,errorCallback){
			alert("999");
			exec(successCallback,errorCallback, "Forward", "action_go_to_scan_two_diem_code",[]);
		},
		// 打开文件选择器
		goToFileExplorer:function(successCallback,errorCallback,action){
			exec(successCallback,errorCallback, "Forward", "action_go_to_file_explorer",[action]);
		},
		//跳转到pdf浏览界面
		goToPdfBrowser:function(successCallback,errorCallback){
			exec(successCallback,errorCallback, "Forward", "action_go_to_pdf_browser",[]);
		}
    };
});

