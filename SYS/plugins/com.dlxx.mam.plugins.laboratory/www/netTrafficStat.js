// add:archmind 15:44 2014/4/22
// author:zhouxuesong

cordova.define("org.apache.cordova.netTrafficStat", function(require,exports,module){
    var exec = require('cordova/exec');
	/** 
	 * 一共5个参数 
	   第一个 :成功回调 
	   第二个 :失败回调 
	   第三个 :将要调用的类的配置名字(在config.xml中配置) 
	   第四个 :调用的方法名(一个类里可能有多个方法 靠这个参数区分) mam.navigator.netTrafficStat.getOneDayTraffic
	   第五个 :传递的参数  以json的格式mam.navigator.netTrafficStat.getOneDayTraffic   getOneMonthTraffic getRencentMoreDayTraffic
	 */ 
    module.exports = {
	    // 跳转到扫描二维码activity 
        getOneDayTraffic:function(successCallback,errorCallback,day){
			exec(successCallback,errorCallback, "netTrafficStat", "getOneDayTraffic",[day]);
		},
		getOneMonthTraffic:function(successCallback,errorCallback,monthStart,monthEnd){
			exec(successCallback,errorCallback, "netTrafficStat", "getOneMonthTraffic",[monthStart,monthEnd]);
		},
		getRencentMoreDayTraffic:function(successCallback,errorCallback,count,endDay,type){
			exec(successCallback,errorCallback, "netTrafficStat", "getRencentMoreDayTraffic",[count,endDay,type]);
		},
		showDateDialog:function(successCallback,errorCallback){
			exec(successCallback,errorCallback, "netTrafficStat", "showDateDialog",[]);
		}
    };
});

