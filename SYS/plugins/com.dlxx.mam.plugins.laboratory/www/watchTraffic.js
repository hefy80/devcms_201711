// fengxf added begin: 20140326
// file: lib/common/plugin/watchTraffic.js

 cordova.define("org.apache.cordova.watchTraffic", function(require, exports, module) {

        var cordova = require('cordova'),
            exec = require('cordova/exec');

        function handlers() {
            return watchTraffic.channels.trafficupdated.numHandlers;
        }

        var WatchTraffic = function() {
			
			this.channels = {
                trafficupdated:cordova.addWindowEventHandler("trafficupdated"),
            };
            for (var key in this.channels) {
                this.channels[key].onHasSubscribersChange = WatchTraffic.onHasSubscribersChange;
            }
        };
		 /** 
		 * 一共5个参数 
		   第一个 :成功会掉 
		   第二个 :失败回调 
		   第三个 :将要调用的类的配置名字(在config.xml中配置 稍后在下面会讲解) 
		   第四个 :调用的方法名(一个类里可能有多个方法 靠这个参数区分) 
		   第五个 :传递的参数  以json的格式 
		 */  
        WatchTraffic.onHasSubscribersChange = function() {
		
            if (this.numHandlers === 1 && handlers() === 1) {
			
                exec(watchTraffic._messageupdate, watchTraffic._error, "watchTraffic", "trafficupdated", []);
            } else if (handlers() === 0) {
                exec(null, null, "watchTraffic", "stopwatch", []);
            }
        };

        WatchTraffic.prototype._messageupdate = function(info) {
            if (info) {
                cordova.fireWindowEvent("trafficupdated", info);
            }
        };

        WatchTraffic.prototype._error = function(e) {
            console.log("Error initializing WatchTraffic: " + e);
        };

        var watchTraffic = new WatchTraffic();

        module.exports = watchTraffic;

    });
// fengxf added end: 20140326
