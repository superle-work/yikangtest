$(function(){
    //到付界面js
    function init(){
        bindEvent();
    }
	
	var CAN_CLICK = true;//按钮是否可以操作
    function bindEvent(){
        $(".pay-method-area .daofu").click(function(){
        	if(!CAN_CLICK){return false;}
        	
            var oid = $(this).attr("data-oid");
            $.ajax({
                type:"post",
                url:"./index.php?c=store&a=inCash",
                data:{"oid":oid},
                dataType:"json",
                beforeSend:function(xhr){
                	CAN_CLICK = false;
                    //显示“加载中。。。”
                    showDialog('#loadingDialog');
                },
                complete:function(){
                	CAN_CLICK = true;
                    //隐藏“加载中。。。”
                	hideDialog('#loadingDialog');
                },
                success:function(json,statusText){
                	showDialog('#successDialog',' ','购买成功，等着接收宝贝吧~','','',function(){
                		window.location.href = "./index.php?c=store&a=orderList&state=1";
                	});
                    
                },
                error:function(){
                	showDialog('#errorDialog','','请求失败，网络异常！','');
                }
            });
        });
    }

    init();
});