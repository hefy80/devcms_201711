// add:archmind 15:44 2014/4/22
// author:zhouxuesong
//定义插件操作相关的错误码

cordova.define("org.apache.cordova.DLFileInstallError", function(require, exports, module) {
/**
 *  DLFileInstallError
 *  An error code assigned by an implementation when an error has occurred
 * @constructor
 */
var DLFileInstallError = function(err) {
    //this.code = (typeof err != 'undefined' ? err : null);
	this.code = err || null;
}

/**
 * 定义DLFileInstallError类错误码Error codes
 */
DLFileInstallError.ARGS_IS_NULL = 1;
DLFileInstallError.ARGS_INSTALL_FILE_PATH_IS_EMPTY = 2;
DLFileInstallError.INSTALL_FILE_PATH_IS_NOT_EXISTS = 3;
DLFileInstallError.INSTALL_OBJ_IS_NOT_FILE = 4;
DLFileInstallError.INSTALL_FILE_EXCEPTION = 5;
DLFileInstallError.INSTALL_FILE_ERROR = 6;
DLFileInstallError.JSON_EXCEPTION = 7;
DLFileInstallError.INSTALL_INNIER_ERROR = 8;
DLFileInstallError.FILE_IS_NOT_SUPPORTED=9;
DLFileInstallError.FILE_HAVE_BEEN_INSTALLED=10;

module.exports = DLFileInstallError;

});
