//URL获取
function GetUrlParms()
{
    var args=new Object();   
    var query=location.search.substring(1);//获取查询串   
    var pairs=query.split("&");//在逗号处断开   
    for(var  i=0;i<pairs.length;i++)   
    {   
        var pos=pairs[i].indexOf('=');//查找name=value   
            if(pos==-1)   continue;//如果没有找到就跳过   
            var argname=pairs[i].substring(0,pos);//提取name   
            var value=pairs[i].substring(pos+1);//提取value   
            args[argname]=unescape(value);//存为属性   
    }
    return args;
}

var msg=[
[
	'getUserInfo',
	'获取登录的账号以及token信息',
	'mam.navigator.loadconfig.getUserInfo</br>(appType, getInfoCallback);</br>'+
	'参数：</br>'+
	'appType:  应用程序类型 {1： 内部用户}</br>'+
	'getInfoCallback： 获取账号后的回调函数。</br>'+
	'appType为1时返回内部用户的相关信息：</br>'+
	'InternalAccountModel{</br>'+
	'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;nameCode:统一框架登录名，</br>'+	
	'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;areaid:区域编号</br>};',
	'cordova加载完毕，现在可以安全地调用js方法</br>'+
	'function onDeviceReady() {</br>'+
	'// 现在可以安全使用移动交互平台JS API</br>'+
	'deviceready=1;</br>'+
	'mam.navigator.loadconfig.getUserInfo(1,login);</br>'+
	'}</br>'+
	'function login(obj){</br>'+
	'consNo=obj.consNo;</br>'+
	'consName = obj.consName;</br>'+
	'elecAddr=obj.elecAddr;</br>'+
	'getalldata();</br>'+
	'}'
],
[
	'loginsession',
	'获取外部用户默认绑定账号信息',
	'//此插件只供电力一点通首页使用。</br>'+
	'mam.navigator.loadconfig2.loginSession(appType, getInfoCallback);</br>'+
	'mam.navigator.loadconfig.loginSession(appType, getInfoCallback);</br>'+
	'参数：</br>'+
	'appType: 应用程序类型{0： 公众用户}</br>'+
	'getInfoCallback： 获取账号后的回调函数。</br>'+
	'appType为0时返回用电户号的相关信息：</br>'+
	'ElectricUserInfoModel{</br>'+
	'consSortCode: 用户分类，</br>'+
	'consNo: 用户编号，</br>'+
	'electAddr:用电地址，</br>'+
	'consName:用户名称，</br>'+
	'orgNo: 供电单位编号，</br>'+
	'voltCode: 供电电压，</br>'+
	'contractCap: 合同容量，</br>'+
	'orgName: 供电单位名称，</br>'+
	'elecType: 用电类编号，</br>'+
	'countyCode: 所属区县，</br>'+
	'};</br>',
	'// cordova加载完毕，现在可以安全地调用js方法</br>'+
	'function onDeviceReady() {</br>'+
	'// 现在可以安全使用移动交互平台JS API</br>'+
	'deviceready=1;</br>'+
	'mam.navigator.loadconfig.loginSession(0,login);</br>'+
	'}</br>'+
	'function login(obj){</br>'+
	'consNo=obj.consNo;</br>'+
	'consName = obj.consName;</br>'+
	'elecAddr=obj.elecAddr;</br>'+
	'getalldata();</br>'+
	'}'
],
[
'messageInfo',
'获取启动时候的消息内容和参数',
'mam.navigator.loadconfig. messageInfo(appType, getMsgCallback);</br>'+
'参数:</br>'+
'appType:  应用程序类型</br>'+
'{0： 公众用户，</br>'+
'1： 内部用户，</br>'+
'2:  特定用户};</br>'+
'getMsgCallback： 获取消息内容后的回调函数，参数是MSG结构。</br>'+
'MSG{</br>'+
'消息头： messageHeader;</br>'+
'消息正文:  messageBody;</br>'+
'消息链接: URI};',
' '
],
[
'getBindingUserInfos',
'获取绑定的用电户号列表',
'mam.navigator.loadconfig.getBindingUserInfos(successCallback);</br>'+
'参数:</br>'+
'无</br>',+
'//号码查询(获取手机号绑定的用电户号)</br>'+
'function getInfos(){</br>'+
'alert("getInfos:");</br>'+
'mam.navigator.loadconfig.getBindingUserInfos(successCallback);</br>'+
'function successCallback(v){</br>'+
'//获取设备平台</br>'+
'var devicePlatform = device.platform;</br>'+
'var bindingUserInfos;</br>'+
'if("iOS" == devicePlatform){</br>'+
'bindingUserInfos = v.BindingUserInfos;</br>'+
'} else {</br>'+
'bindingUserInfos = eval(\'(\'+v.BindingUserInfos+\')\');</br>'+
'}</br>'+
'msgNum = bindingUserInfos.length;</br>'+
'console.log("bindingUserInfos length:"  + msgNum);</br>'+
'for( var i = 0 ; i<msgNum; i++){</br>'+
'console.log("bindingUserInfos:id:"  + bindingUserInfos[i].id + </br>'+
'",account:" + bindingUserInfos[i].account + </br>'+
'",accountType:" + bindingUserInfos[i].accountType +</br>'+ 
'",accountTypeName:" + bindingUserInfos[i].accountTypeName + </br>'+
'",nickName:" + bindingUserInfos[i].nickName + </br>'+
'",isDefault:" + bindingUserInfos[i].isDefault);</br>'+
'}</br>'+
'alert("successCallback:" + v.result +\'bindingUserInfos:\'+ bindingUserInfos.length);</br>'+
'}</br>'+
'}</br>'
],
['3-1','3-2','3-3'],
['4-1','4-2','4-3'],
['5-1','5-2','5-3']
];
	


				
