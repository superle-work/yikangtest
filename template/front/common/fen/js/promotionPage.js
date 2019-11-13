/**
 * 推广页面
 * @since 2015-11-11
 * @author jjhu
 */
$(function(){
    function init(){

        var width = $("#container").width();//计算当前窗口的宽度
        $("#container #myBottomNav").width(width);//将底部导航的宽度设置与移动设备相同
        $("#container .distribute-guide").width(width-20);
        $("#container #myBottomNav").css("display","block");

        bindEvent();
    }

    function bindEvent(){
    	//二维码头像样式
    	$(window).load(function(){
    		var width = $('#container #content .code').width();
    		var codeWidth = $('#container #content .code .code-img').width();
    		var codeHeight = $('#container #content .code .code-img').height();
    		var headWidth = codeWidth/10;
    		var headHeight = codeHeight/10;
    		$('#container #content .code .head-img').css({
    			'width':headWidth,
    			'height':headHeight,
    			'top':(codeHeight-headHeight)/2 + 10,
    			'left':(width-headWidth)/2 + 10
			});
    	});
    	
        //用户变化屏幕方向时调用
        $(window).bind( 'orientationchange resize', function(e){
            width = $("#container").width();//计算当前窗口的宽度
            $("#container #myBottomNav").width(width);//将底部导航的宽度设置与移动设备相同
            $("#container .distribute-guide").width(width-20);
        });

        //图片分销
        $("#myBottomNav .image-distribute").click(function(){
            alert("长按保存图片并将图片发送给您的好友或朋友圈。");
        });

        //链接分销
        $("#myBottomNav .link-distribute").click(function(){
            $("#container .distribute-guide").show();
        });
        $("#container .distribute-guide").click(function(){
            $(this).hide();
        });
    }

    init();
});