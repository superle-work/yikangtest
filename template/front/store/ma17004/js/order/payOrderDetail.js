$(function(){
	
	/*
	 * 初始化
	 */
	function init(){
		bindEvent();
	}
	
	
	/*
	 * 捆绑函数
	 */
	function bindEvent(){
		//支付
		$("#goPay").click(function(){
			var oid=$(this).attr("data-oid");
			$.ajax(
                {
                  	type:"post",
                  	url:"http://yikang.chuyuanshengtai.com/index.php?c=store&a=payOrderDetail",
                  	data:{
                      	"oid":oid,
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
                    	  	var jsApiParametersResult = $.parseJSON(json.data);
                   			callpay(jsApiParametersResult);
                      	}else{
                          	showDialog("#errorDialog", "支付失败", json.errorInfo );
                          	return false;
                      	}
                  	},
                  	error:function(xhr,status,thrown){
                	  	showDialog('#errorDialog','','网络异常，请求失败！','');
                      	return false;
                  	}
                }
            );
		})
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
                       window.location.href = "http://yikang.chuyuanshengtai.com/index.php?c=store&a=index";//跳转支付完成
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
})
