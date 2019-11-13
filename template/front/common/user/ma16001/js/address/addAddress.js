$(function(){
    /**
     * 初始化
     */
    function init(){
        bindEvent();
    }

    /**
     * 绑定事件
     */
    function bindEvent(){
    	
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
       
        $("#content .address-top-area .back").click(function(){
            window.history.back(-1);
        });

        //添加地址--提交新地址
        $("#content table .add-address").click(function(){
            var callname = $.trim($("#content table .callname").val());
            var phone = $.trim($("#content table .phone").val());
            //var province = $("#content table #province").val();
           // var city = $("#content table #city").val();
           // var area = $("#content table #area").val();
            var addr = $.trim($("#content table #addr").val());
            var info = $.trim($("#content table #detail").val());
            if(callname == "" || phone == "" || addr == "" || info==""){
            	showDialog("#maskDialog");
            	showDialog("#alertDialog","信息不完善",'请完善信息再提交','',2000);
                return false;
            }
            var address = {
                //'province':province,
                //'city':city,
              //  'area':area,
                'detail':detail
            }
            $.ajax({
                url:"./index.php?c=base_user&a=insertAddress",
                type:"post",
                data:{
                	   "addr": addr,
                	   "detail" :info,
                       "phone":phone,
                       "call_name":callname
                    },
                dataType:"json",
                beforeSend:function(){
                	showDialog('#loadingDialog');
                },
                complete:function(){
                	hideDialog('#loadingDialog');
                },
                success:function(json,statusText){
                    if(json.errorCode == 0){
                    	showDialog("#maskDialog");
                    	showDialog("#successDialog",'资料保存成功',' ','','',function(){window.history.back(-1);});
                    }else{
                    	showDialog("#maskDialog");
                    	showDialog("#alertDialog","抱歉！操作异常",json.errorInfo,'',2000);
                    }

                },
                error:function(){
                	showDialog("#maskDialog");
                	showDialog("#alertDialog","抱歉！系统繁忙",'您的操作失败咯','',2000);
                }
            });
        });
       
    }

    init();
});