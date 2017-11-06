//**********************图片上传预览插件************************
//作者：shooke
//QQ：82523829
//
//版本：1.1

//说明：图片上传预览插件
//上传的时候可以生成固定宽高范围内的等比例缩放图

//参数设置：
//width                     存放图片固定大小容器的宽
//height                    存放图片固定大小容器的高
//imgPreview                    页面DIV的JQuery的id
//imgType                   数组后缀名
//**********************图片上传预览插件*************************
(function($) {
    jQuery.fn.extend({
        uploadPreview: function(opts) {
            opts = jQuery.extend({
                width: 0,
                height: 0,
                imgPreview: "#imgPreview",
                imgType: ["gif", "jpeg", "jpg", "bmp", "png"],
                callback: function() { return false; }
            }, opts || {});
            var _self = this;
            var _this = $(this);
            var imgPreview = $(opts.imgPreview);
            imgPreview.width(opts.width);
            imgPreview.height(opts.height);
			

            autoScaling = function() {
                if ($.browser.version == "7.0" || $.browser.version == "8.0") imgPreview.get(0).filters.item("DXImageTransform.Microsoft.AlphaImageLoader").sizingMethod = "image";
                var img_width = imgPreview.width();
                var img_height = imgPreview.height();
                if (img_width > 0 && img_height > 0) {
                    var rate = (opts.width / img_width < opts.height / img_height) ? opts.width / img_width : opts.height / img_height;
                    if (rate <= 1) {
                        if ($.browser.version == "7.0" || $.browser.version == "8.0") imgPreview.get(0).filters.item("DXImageTransform.Microsoft.AlphaImageLoader").sizingMethod = "scale";
                        imgPreview.width(img_width * rate);
                        imgPreview.height(img_height * rate);
                    } else {
                        imgPreview.width(img_width);
                        imgPreview.height(img_height);
                    }
                    var left = (opts.width - imgPreview.width()) * 0.5;
                    var top = (opts.height - imgPreview.height()) * 0.5;
                    imgPreview.css({ "margin-left": left, "margin-top": top });
                    imgPreview.show();
                }
            }
            _this.change(function() {
				imgPreview.html('');
                if (this.value) {
                    if (!RegExp("\.(" + opts.imgType.join("|") + ")$", "i").test(this.value.toLowerCase())) {
                        alert("图片类型必须是" + opts.imgType.join("，") + "中的一种");
                        this.value = "";
                        return false;
                    }
                    imgPreview.hide();
                    if ($.browser.msie) {
                        if ($.browser.version == "6.0") {
                            var img = $("<img />");
                            imgPreview.replaceWith(img);
                            imgPreview = img;

                            var image = new Image();
                            image.src = 'file:///' + this.value;
							//alert(this.value);

                            imgPreview.attr('src', image.src);
                            autoScaling();
                        }
                        else {
                            imgPreview.css({ filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=image)" });
                            imgPreview.get(0).filters.item("DXImageTransform.Microsoft.AlphaImageLoader").sizingMethod = "image";
                            try {
                                imgPreview.get(0).filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = this.value;
                            } catch (e) {
                                alert("无效的图片文件！");
                                return;
                            }
							autoScaling();

                        }
                    }
                    else {					
                        var img = $("<img />");
                        imgPreview.replaceWith(img);
                        imgPreview = img;
						
						if($.browser.version<7){
                        	//imgPreview.attr('src', this.files.item(0).getAsDataURL());
							imgPreview.attr('src',window.URL.createObjectURL(sender.files[0]));
						}else{
							oFReader = new FileReader(), rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;

							oFReader.onload = function (oFREvent) {
							  imgPreview.attr('src', oFREvent.target.result);
							};
							var oFile = this.files[0];
							oFReader.readAsDataURL(oFile);
						}
                       imgPreview.css({ "vertical-align": "middle","width": opts.width,"height": opts.height });
                    }
                }
            });
        }
    });
})(jQuery);

