// add:archmind 15:44 2014/4/22
// author:zhouxuesong
//定义插件操作相关的错误码

cordova.define("org.apache.cordova.DLFileTransferError", function(require, exports, module) {
/**
 *  DLFileTransferError
 *  An error code assigned by an implementation when an error has occurred
 * @constructor
 */
var DLFileTransferError = function(err) {
    //this.code = (typeof err != 'undefined' ? err : null);
	this.code = err || null;
}

/**
 * 定义DLFileTransferError类错误码Error codes
 */
DLFileTransferError.ARGS_IS_NULL = 1;
DLFileTransferError.ARGS_UPLOAD_FILE_PATH_IS_EMPTY = 2;
DLFileTransferError.UPLOAD_FILE_PATH_IS_NOT_EXISTS = 3;
DLFileTransferError.UPLOAD_OBJ_IS_NOT_FILE = 4;
DLFileTransferError.UPLOAD_FILE_EXCEPTION = 5;
DLFileTransferError.UPLOAD_FILE_ERROR = 6;
DLFileTransferError.JSON_EXCEPTION = 7;
DLFileTransferError.UPLOAD_INNIER_ERROR = 8;
DLFileTransferError.UPLOAD_SOCKET_TIMEOUT_EXCEPTION = 9;
module.exports = DLFileTransferError;
});
