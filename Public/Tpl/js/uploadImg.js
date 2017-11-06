var uploadImg = function( e, fileElem, imgElem ){
	this.e = e;
	this.fileElem = fileElem;
	this.imgElem = imgElem;
};
uploadImg.prototype = {
	constructor : uploadImg,
	UA : navigator.userAgent,
	isIE : function(){return /MSIE/.test(this.UA)},
	isVersion : function(){
		if(/MSIE ([^;]+)/.test(this.UA)){
			return (RegExp['$1'] | 0);
		}
	},
	trim : function(){
		return this.fileElem.value.replace(/(^\s+)|(\s+$)/g,'');
	},
	outputImage : function(){
		if(this.trim() !== ''){
			if(!/(jpg$)|(gif$)/gi.test(this.trim())){
				if(this.isIE() && this.isVersion() < 9){
					this.fileElem.outerHTML="<input type='file' value='' name='bpic'  class='vertical fileSty' />";
				}else{
					this.fileElem.value = '';
				}
				alert('图片格式必须为jpg或者gif格式！');
				this.e.preventDefault();
			}
			if(this.isIE() && this.isVersion() === 6){
				this.imgElem.src = this.fileElem.value;
			}else if(this.isIE() && (this.isVersion() === 7 || this.isVersion() === 8)){
				this.fileElem.select();
				var selectText = 'progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod="scale",src="'+document.selection.createRange().text+'")',
					isFilter = document.getElementById("filterDivShow");
				if(!isFilter){
					var createDiv = document.createElement('div');
					createDiv.id = 'filterDivShow';
					createDiv.style.width = this.imgElem.width + 'px';
					createDiv.style.height = this.imgElem.height + 'px';
					createDiv.style.filter = selectText;
					this.imgElem.parentNode.appendChild(createDiv);
				}else{
					isFilter.style.filter = selectText;
				}
				this.imgElem.style.display = 'none';
			}else{
				try{
					var files = this.e.target.files[0],
						reader = new FileReader,
						thatImg = this.imgElem;
					reader.onload = (function(i){
						return function( e ){
							thatImg.setAttribute('src',e.target.result);
							thatImg.style.display = 'block';
						}
					})(files);
					reader.readAsDataURL(files);
				}catch(x){
					return ;
				}
			}
		}
	}
}
