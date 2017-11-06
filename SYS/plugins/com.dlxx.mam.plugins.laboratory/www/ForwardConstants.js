cordova.define("org.apache.cordova.ForwardConstants", function(require, exports, module) {
module.exports = {
  //到文件浏览器解码传递的参数
   FileExplorer:{
     ACTION_COMMON_FILE_UPLOAD: "action_common_file_upload",// 普通文件上传界面调用传入调用此参数[普通文件上传需要需要限制文件大小]
     ACTION_BREAK_POINT_FILE_UPLOAD: "action_break_point_file_upload",// 标识为文件断点上传
     ACTION_APP_LOCAL_INSTALL: "action_app_local_install", // 标识为安装软件
     ACTION_OPEN_MEDIA_FILE: "action_open_media_file" //标识为通过此浏览器打开媒体文件
   }
  };
});
