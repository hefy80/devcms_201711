//fengxf added begin:
cordova.define("org.apache.cordova.gatheringLocation", function(require,exports,module){

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
		//打开位置上报功能,isOpen:1为打开，0为关闭,businessAppId:appid应用编号
        openSwitch: function(successCallback,errorCallback,isOpen,businessAppId) {
            exec(successCallback, errorCallback, "LocationGathering", "openSwitch", [isOpen,businessAppId]);
        },
		//跳转到高德地图
		toMap: function(successCallback,errorCallback,Lon,lat) {
            exec(successCallback, errorCallback, "LocationGathering", "toGaoDe", [Lon,lat]);
        },
		openApk: function(successCallback,errorCallback,pkgName) {
            exec(successCallback, errorCallback, "LocationGathering", "openApk", [pkgName]);
        },
		addExtra: function(successCallback,errorCallback,size) {
            exec(successCallback, errorCallback, "LocationGathering", "capture", [size]);
        },
		lbs: function(successCallback,errorCallback) {
            exec(successCallback, errorCallback, "LocationGathering", "lbs",[]);
        },
		//跳转到百度地图
		toBaiDu: function(successCallback,errorCallback,Lon,lat) {
            exec(successCallback, errorCallback, "LocationGathering", "toBaiDu", [Lon,lat]);
        }		
    };

});
//lrx added end