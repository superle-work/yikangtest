$(function(){
    if($("#service-area .bottom-tag-list").length > 0){
    	//底部客服区域
    	var window_width = $(window).width();//计算浏览器的宽度
    	$("#service-area .bottom-tag-list").css("right", (window_width-$("#container").width())/2+8);//将底部标签放在右边
	    /**
	     * 联系客服弹出微信二维码
	     */
	    $('#service-area .wechat-service').click(function(){
	    	var show = $('#service-area .wechat-code-show');
	    	var img = $(this).attr("data-img");//二维码地址
	    	$('#service-area .wechat-code-show img').attr("src",img);
	    	$('#service-area .wechat-code-show .wechat-code-area').css('display', 'block');
	    	$('#service-area .wechat-code-show').css('display', 'block');
	        
	    	$("#service-area .shadow").show();
	    });
	    //关闭二维码窗口事件
	    $('#service-area .wechat-code-show .leave-wechat-code').click(function(){
	    	$('#service-area .wechat-code-show').css('display', 'none');
	    	$("#service-area .shadow").hide();
	    });
    }
});
