/*待付款订单*/
$(function(){
	var payType = $("#content #payType").val();
    /**
     * 初始化
     */
    function init(){
        bindEvent();
    }

    /**
     * 绑定事件
     */
    var CAN_CLICK = true;
    function bindEvent(){
    	//选择支付方式
    	$(".pay-type .is-balance-pay").change(function(){
    		var check = $(".pay-type .is-balance-pay").is(':checked');
    		if(check){
    			 $(".pay-method-area .balance-pay").show();
    			 $(".pay-method-area .pay-money").hide();
    		}else{
    			$(".pay-method-area .balance-pay").hide();
   			 	$(".pay-method-area .pay-money").show();
    		}
    	});
    	
       //微信支付/货到付款
        $(".pay-method-area .pay-method a").click(function(){
		   if(!CAN_CLICK){return false;}
           var oid = $(this).attr("data-oid");//详单主键
		   
		   var paymethod = 0;//支付方式
		   
		   if($(this).hasClass("wechat")){//微信支付
               paymethod = 1;
           }else if($(this).hasClass("daofu")){//货到付款
               paymethod = 3;
           }else if($(this).hasClass("alipay")){//支付宝付款
			   paymethod = 2;
		   }else if($(this).hasClass("balance-pay")){//余额支付
			   paymethod = 4;
		   }
           if(paymethod == 3){//选择货到付款
               $.ajax({
                   type:"post",
                   url:"/index.php?c=store&a=inCash",
                   data:{"oid":oid},
                   dataType:"json",
                   beforeSend:function(xhr){
                       //显示“加载中。。。”
                       CAN_CLICK = false;
                       showDialog('#loadingDialog');
                   },
                   complete:function(){
                       //隐藏“加载中。。。”
                       CAN_CLICK = true;
                       hideDialog('#loadingDialog');
                   },
                   success:function(json,statusText){
                       if(json.errorCode == 0){
                           showDialog('#successDialog','','操作成功','','',function(){
                        	   window.location.href = "/index.php?c=store&a=orderList&state=1";
                           });
                       }else{
                    	   showDialog('#errorDialog','',json.errorInfo,'');
                       }
                   },
                   error:function(){
                	   showDialog('#errorDialog','','请求失败，网络异常！','');
                   }
               });
           }else if(paymethod ==1){//选择微信支付
               return true;//继续执行默认行为
           }else if(paymethod == 4){//余额支付
        	   $.ajax({
                   type:"post",
                   url:"/index.php?c=store&a=payBalance",
                   data:{"oid":oid},
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
                    	   //showDialog("#maskDialog");
                     	   showDialog("#successDialog",'操作成功','提交成功！','', '',function(){
                     		  window.location.href = "/index.php?c=store&a=orderList&state=1";
                     	   });
                       }else{
                    	   showDialog('#errorDialog',' ',json.errorInfo);
                       }
                   },
                   error:function(){
                	   showDialog('#errorDialog',' ',"请求失败，网络异常！");
                   }
               });
           }
       });
    }

    init();
});
