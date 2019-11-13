$(function(){
    /**
     * 初始化
     */
    function init(){
    	
        /**
    	 * 省市区选择器初始化
    	 */
    	$("#addr").cityPicker({
    	    toolbarTemplate: '<header class="bar bar-nav">\
    	    <button class="button button-link pull-right close-picker">确定</button>\
    	    <h1 class="title">选择地址</h1>\
    	    </header>'
    	 });
    	
    	/**
    	 * 控制弹出框大小位置
    	 */
    	$("#addr").click(function(){
    		var width=$("#container").width();
    		var window_width = $(window).width();
        	//$('.picker-modal').css({'max-width': width, left: (window_width - width)/2});
    	});

        bindEvent();
    }

    /**
     * 绑定事件
     */
    function bindEvent(){
        //保存修改
        $("#container .top .right-icon a.btn").click(function(){

            var id = $("#content .operation-area #id").val();
            var nick_name = $.trim($("#content .myinfo #nick_name").val());
            var name = $.trim($("#content .myinfo #name").val());
            var phone = $.trim($("#content .myinfo #phone").val());
            var address = $.trim($("#content .myinfo #addr").val());
            var detail = $.trim($("#content .myinfo #detail").val());

            //发送请求，修改我的个人信息
            $.ajax({
                url:"./index.php?c=base_user&a=updateUser",
                type:"post",
                data:{"id":id,"nick_name":nick_name,"name":name,"phone":phone,"detail":detail,"address":address},
                dataType:"json",
                beforeSend:function(xhr){
  	               //显示“加载中。。。”
  	            	showDialog("#loadingDialog");
                 },
                 complete:function(){
  	               //隐藏“加载中。。。”
  	            	hideDialog("#loadingDialog");
                 },
                success:function(json,statusText){
                    if(json.errorCode == 0){
                    	showDialog('#successDialog','保存成功',' ','','1500',function(){window.history.go(-1);})
//                      window.history.go(-1);
                    }else{
                        //注册失败
                    	showDialog('#errorDialog','保存失败',"操作失败，"+json.errorInfo+"！",'');
                    }
                },
                error:function(){
                    //请求失败
                	showDialog('#errorDialog','抱歉！网络异常',"网络异常，请稍后再试！",'');
                }
            });
        });
    }
    init();
});