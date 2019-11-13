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
        	$('.picker-modal').css({'max-width': width, left: (window_width - width)/2});
    	});

        bindEvent();
    }

    /**
     * 绑定事件
     */
    function bindEvent(){
        //保存修改
        $("#content .operation-area .btn-save").click(function(){

            var id = $("#content .operation-area #id").val();
            var name = $.trim($("#content .myinfo #name").val());
            var nick_name = $.trim($("#content .myinfo #nick_name").val());
            var phone = $.trim($("#content .myinfo #phone").val());
            //var province = $.trim($("#content .myinfo #province").val());
            //var city = $.trim($("#content .myinfo #city").val());
            var address = $.trim($("#content .myinfo #addr").val());
            //var email = $.trim($("#content .myinfo #email").val());
           // var password = $.trim($("#content .myinfo #password").val());

            //发送请求，修改我的个人信息
            $.ajax({
                url:"./index.php?c=base_user&a=updateUser",
                type:"post",
                data:{"id":id,"name":name,"nick_name":nick_name,"phone":phone,"address":address},
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
                    	$("#content .action-tip").html('<div class="success-info text-success">保存成功!</div>');
                        $("#content .action-tip").show();
                        setTimeout(function(){
                            $("#content .action-tip").hide();
                        },2000);
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