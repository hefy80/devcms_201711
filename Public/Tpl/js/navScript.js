$(function(){   
     var $li = $(".meLeftCon>ul>li"),
         $url = location.href;
         $index = $url.substring($url.lastIndexOf("po")+3,$url.lastIndexOf("po")+4)-1;
     $li.eq($index).children("a").css("color","#ffffff");
     $("#mLlihover").stop(true).css("top",$li.eq($index).position().top);
     $li.eq($index).addClass("active");
     $li.mouseenter(function(){
        var _this = $(this);
        navAnimate(_this.position().top,_this);
     }).parent().mouseleave(function(){
     	if($(this).children("li").hasClass("active")){
     		var _removeSite = $(this).children("li").filter(".active");
     		navAnimate(_removeSite.position().top,_removeSite);
     	}else{
     		navAnimate(14,$li.eq(0));
     	}
     });
     function navAnimate(site,elem){
     	$("#mLlihover").stop(true).animate({top:site},200);
        elem.children("a").css("color","#ffffff").parent().siblings("li").children("a").css("color","#333333");
     }
})
