/***
 * 系统设置
 */
$(function(){


    function init(){
        bindEvent();
    }

    function bindEvent(){
        $(".items .submit").click(function(){
            $(".items form").ajaxSubmit($.extend(true,{},formOptions,options,{url:'./change.php?c=config&a=setConfig&page=baseConfig'}));
            return false;
        });

        $("#configCenter .inner-section > .nav li a").click(function(){
	        if($(this).parent().hasClass("selected")){
	            return false;
	        }
	        $("#configCenter .inner-section > .nav li").removeClass("selected");
	        $(this).parent().addClass("selected");
	
	        var id = $(this).attr("flag");//获取显示区域标签id值
	        $(".list-table .item").hide();
	        $(".inner-section").find(id).show();
	        return false;
	    });
    }
    init();

});