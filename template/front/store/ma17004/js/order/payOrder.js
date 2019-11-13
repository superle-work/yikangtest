/*提交订单*/
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
       	//无默认体检人员
       	$(".addMem-area .addCheckMember").click(function(){
       		var storage=window.localStorage;
       		storage['url']=window.location.href;
       		location.href="http://yikang.chuyuanshengtai.com/index.php?c=store&a=checkMember";
       	})
       	
       	//重新选择体检人员
       	$(".member-list .action .reSel").click(function(){
       		var storage=window.localStorage;
       		storage['url']=window.location.href;
       		location.href="http://yikang.chuyuanshengtai.com/index.php?c=store&a=checkMember";
       	})
       
       	
        //提交订单
        $(".bottom-nav .pay-method").click(function(){
            $("#content .tip-info").hide();
            //验证体检信息
            var option = $(".member-list .info");
            if(option.length == 0){
                showDialog("#errorDialog",'','添加检查人员信息','&#xe6a9;',2000);
                return false;
            }
            
            var checkMember_id = option.attr("data-id");//收货人
            
            var cgids = $("#content #cgids").val();//从购物车下单，购物车里面待删除的商品
            var gids = $("#content #gids").val();//商品主键
            //var gpids = $("#content #gpids").val();  //商品属性主键
            var counts = $("#content #counts").val();//商品数量
            //总价
            var total_price=$(".order-bottom-area .goods-total #total-price-1").text();
            //推荐诊所id
            var clinicID=$(".clinic-area .clinicID").val();
            
            var uid=$(this).attr("data-user");
            $.ajax(
                {
                  	type:"post",
                  	url:"http://yikang.chuyuanshengtai.com/index.php?c=store&a=payOrder",
                  	data:{
                      	"gids":gids,
                      	"cgids":cgids,
                      	"counts":counts,
                      	"pay_method":1,
                      	"checkMember_id":checkMember_id,
                      	"total_price":total_price,
                      	"clinicID":clinicID,
                      	'uid':uid
                  	},
                  	dataType:"json",
                  	beforeSend:function(xhr){
                       	//显示“加载中。。。”
                       	//showDialog('#loadingDialog');
                  	},
                  	complete:function(){
				   		//隐藏“加载中。。。”
                	  	//hideDialog('#loadingDialog');
                  	},
                  	success:function(json,statusText){
                      	if(json.errorCode == 0){
                      		var storage=window.localStorage;
       						storage.clear();
                    	  	var jsApiParametersResult = $.parseJSON(json.data);
                   			callpay(jsApiParametersResult);
                      	}else{
                          	showDialog("#errorDialog", "下单失败", json.errorInfo );
                          	return false;
                      	}
                  	},
                  	error:function(xhr,status,thrown){
                	  	showDialog('#errorDialog','','网络异常，请求失败！','');
                      	return false;
                  	}
                }
            );
       	});
		
    }
    
    //调用微信JS api 支付
    function jsApiCall(jsApiParameters)
    {	
        WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                {
                   "appId":jsApiParameters.appId,     //公众号名称，由商户传入     
                   "timeStamp":jsApiParameters.timeStamp,         //时间戳，自1970年以来的秒数     
                   "nonceStr":jsApiParameters.nonceStr, //随机串     
                   "package":jsApiParameters.package,     
                   "signType":"MD5",         //微信签名方式：     
                   "paySign":jsApiParameters.paySign //微信签名 
                },
                function(res){
                    //WeixinJSBridge.log(res.err_msg);
                    if(res.err_msg == "get_brand_wcpay_request:ok" ) {//支付成功
                        // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。
                       window.location.href = "http://yikang.chuyuanshengtai.com/index.php?c=store&a=index";//跳转附近诊所
                    }else if(res.err_msg == "get_brand_wcpay_request:cancel"){//取消支付

                    }else if(res.err_msg == "get_brand_wcpay_request:fail"){//支付失败
						
                    }
                }
        );
    }

    function callpay(jsApiParameters)
    {
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
            jsApiCall(jsApiParameters);
        }
    }
    
    init();
});
