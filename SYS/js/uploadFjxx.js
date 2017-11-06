    var piclists=[];
    var filename;
    var picPath;
    $(function () {
        var pictureSource;		//图片来源
        var destinationType;		//设置返回值的格式

        document.addEventListener("deviceready", onDeviceReady, false);

        function onDeviceReady() {
        	deviceready=1;
            pictureSource = mam.navigator.camera.PictureSourceType;
            destinationType = mam.navigator.camera.DestinationType;


            var devicePlatform = device.platform;
            if("Android" == devicePlatform){

                    $("#imgList").before('<input id="doc-form-file" type="file"   accept="image/*"  onchange="onFileChange()" >');

            }

            if("iOS" == devicePlatform){
                $('#imgList').click(function () {
                    if ($('.baseImg').length >= 3) {
                        Showbo.Msg.alert('最多上传3张图片');
                    } else {
                        getPhoto(pictureSource.PHOTOLIBRARY);
                    }
                });
            }
        }

      //*************************************** 拍照  start*************************************************//
        $('#camera').click(function () {
            //alert($('.baseImg').length);
            if ($('.baseImg').length >= 3) {
                Showbo.Msg.alert('最多上传3张图片');
            } else {
                mam.navigator.camera.getPicture(onSuccess, onError, {quality: 10});
            }
        });


        function onSuccess(imageData) {
        	
            picPath=imageData;
            appUpload(imageData);
        }

        function onError(cameraError) {
            console.log("onError() cameraError:" + cameraError);
        }

        function getPhoto(source) {
            mam.navigator.camera.getPicture(onPhotoURISuccess, onFail, {
                quality: 50,
                destinationType: destinationType.FILE_URI, sourceType: source
            });
        }

        function onPhotoURISuccess(imageURI) {
            picPath=imageURI;
            appUpload(imageURI);

        }

        function onFail(mesage) {
            Showbo.Msg.alert("获取图片失败!");
        }

    });

    //*************************************** 图片上传  start*************************************************//
    function appUpload(imageData){
        var filePath = imageData;
        var devicePlatform = device.platform;
        filename=imageData.substring(imageData.lastIndexOf('/')+1,imageData.length);
        if("Android" == devicePlatform){
            if(imageData.indexOf("file:///storage/emulated/0")==-1){
                filePath = imageData.replace("file:///storage/sdcard0", "/sdcard");
            }else{
                filePath = imageData.replace("file:///storage/emulated/0", "/sdcard");
            }
        }

        if("iOS" == devicePlatform){
            filePath = imageData.replace("file:///", "/");
        }

        $('.loading').css('display','block');
        $('.shield').css('display', 'block');

        picPath=filePath;
        executeCompress(picPath);
    }

    function successUploadPICCallback(obj) {
        $('.loading').css('display','none');
        $('.shield').css('display', 'none');

        //显示图片
        $('<div class="baseImg"><img src="' + picPath + '"></div>').appendTo('.cameraImgList');

        var piclist={};
        piclist.FILENAME=filename;
        piclist.UUID=obj;
        piclist.TYPE="1";
        piclists.push(piclist);
    }

    function errCallback(obj) {
    	alert("errCallback:"+obj2string(obj));
        $('.loading').css('display','none');
        $('.shield').css('display', 'none');
        Showbo.Msg.alert("上传失败!");
    }


    //压缩图片
    function executeCompress(filePath) {
        mam.navigator.ContentCompression.getSmallPicture(function(obj){
            var data={method:"uploadFjxx"};
            mam.navigator.appupload.appServerUpload(successUploadPICCallback, errCallback, "appcode_SYS13","upload", obj.result, data);

        }, function(dlFileTransferError){
            Showbo.Msg.alert("压缩失败");
            $('.loading').css('display','none');
            $('.shield').css('display', 'none');
        }, filePath,50);
    }


    //获取图片
    var mpImg;
    var resImg = document.getElementById('megapix');
    function onFileChange(){
        if ($('.baseImg').length >= 3) {
            Showbo.Msg.alert('最多上传3张图片');
            return;
        }
        var file = document.getElementById('doc-form-file').files[0];
        var reader = new FileReader();
        reader.onload = function (e) {
            var dataURL = e.target.result;
            $('.loading').css('display','block');
            $('.shield').css('display', 'block');
            //获取图片的路径
            navigator.ContentCompression.getPicturePath(pathsuccessCallback,patherrorCallback,dataURL.split('base64,')[1]);
            function pathsuccessCallback(v){
                appUpload(v.result);
            }
            function patherrorCallback(){
                mam.navigator.notification.toast("图片处理失败，请重试",0);
            }

        };
        reader.readAsDataURL(file);
    }
    //*************************************** 图片上传  end*************************************************//
    
    //*************************************** 扫描二维码 start*************************************************//
    /*扫一扫单击事件*/
    $('.inspectorHeader').on('click', function () {
        mam.navigator.forward.goToScanTwoDiemCode(successCallback,errorCallback);
        function successCallback(obj){
            var data=obj;
            if(typeof(obj)=="string"){
                data=eval("("+obj+")");
            };
          alert(JSON.stringify(data));
            var dwid=data['DWID'];
            if(dwid==undefined){
               Showbo.Msg.alert("你扫描的二维码有误!");
            }else{
            	alert('测试_扫描二维码成功！');
            }
       };
        function errorCallback(obj){
                   Showbo.Msg.alert("扫描失败!");
        }
	});
    //*************************************** 扫描二维码 end*************************************************//
    
    
    //*********************************** 录音  start ***********************************************//
    
//    function captureAudio() {
    	 $('#captureAudio_xz').on('click', function () {
    		 alert('测试_captureAudio_click');
    	        // 启动设备的音频录制应用程序,  
    	        // 允许用户最多采集2个音频剪辑
    		 	var captureAudioOptions = { limit:1};
    		 	mam.navigator.device.capture.captureAudio(captureSuccess, captureError,captureAudioOptions);
//    	        mam.navigator.device.capture.captureAudio(captureSuccess, captureError, {limit:1});
    	 });


//    }

    function captureSuccess(mediaFiles) {
    	alert("测试_captureSuccess");
        var i, len;
        for (i = 0, len = mediaFiles.length; i < len; i += 1) {
            uploadFile(mediaFiles[i]);
        }
    }

    // 采集操作出错后的回调函数
    function captureError(error) {
    	alert('测试_captureError');
        var msg = 'An error occurred during capture: ' + error.code;
        mam.navigator.notification.alert(msg, null, 'Uh oh!');
    }
    
    // 上传文件到服务器 
    function uploadFile(mediaFile) {
    	alert('测试_uploadFile');
        var ft = new FileTransfer(),
                path = mediaFile.fullPath,
                name = mediaFile.name;
        alert('测试_name：'+name+'   path：'+path);
        ft.upload(path,
                "https://mam1.ft-power.com.cn:10013/aqscsj-web/aqscPicAction.do?method=uploadFjxx",
                function(result) {
        			alert('测试_uploadSuccess');
                    console.log('Upload success: ' + result.responseCode);
                    console.log(result.bytesSent + ' bytes sent');
                },
                function(error) {
                	alert('测试_uploadError');
                    console.log('Error uploading file ' + path + ': ' + error.code);
                },
                { fileName: name,GLID:sessionStorage.GLID,LB:sessionStorage.LB });
    }
  //*********************************** 录音 end ***********************************************//