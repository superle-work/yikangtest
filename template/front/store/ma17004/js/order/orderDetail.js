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
			window.location.href = "./pay/request/code.php?&oid="+oid+"&module=storePay";
		})
		
		//上传报告
		$("#addReport").click(function(){
			var oid=$(this).attr("data-oid");
			location.href="index.php?c=store&a=addReport&oid="+oid;
		})
	}
	
	/*
	//关闭订单
    function closeOrder(){
        var id = $(this).attr("oid");
        var state = "4";//交易关闭
        setOrderState(id,state);
    };
    //确认收货
    function confirmReceipt(){
        var id = $(this).attr("oid");
        var state = "3";//确认收货
        setOrderState(id,state);
    }

    //交易完成
    function successOrder(){
        var id = $(this).attr("oid");
        var state = "5";//交易完成
        setOrderState(id,state);
    }
    //显示退款
    function applyOrder(){
    	var id = $(this).attr("oid");
    	var state = "6";//交易完成
    	setOrderState(id,state);
    }

  //删除订单
    function deleteOrder(){
    	var id = $(this).attr("data-id");
		showConfirmDialog("#dangerConfirmDialog",'确认删除','是否删除订单','','',function(){
			 $.ajax({
		            url:"./index.php?c=store&a=deleteOrder",
		            type:"post",
		            data:{"id":id},
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
		                    showDialog("#seccessDialog",'恭喜您','操作成功','',1500,function(){
//		                    	window.location.href="./index.php?c=store&a=manageOrder&r="+Math.random()+"";
		                   		window.location.href="./index.php?c=store&a=orderList&r="+Math.random()+"";
		                    });
			                $("#shadow").hide();
		                }else{
		                	showDialog('#errorDialog','',json.errorInfo)
		                }
		            },
		            error:function(){
		            	showDialog("#errorDialog",'','请求失败，网络异常！','',1500);
		            }
		        });
		});
    }
    
    //设置订单状态 3:确认收货 4:交易关闭
    function setOrderState(id,state){
        $.ajax({
            url:"./index.php?c=store&a=setOrderState",
            type:"post",
            data:{"oid":id,"state":state},
            dataType:"json",
            beforeSend:function(xhr){
               //显示“加载中。。。”
               showDialog('#loadingDialog');
            },
            complete:function(){
               //隐藏“加载中。。。”
               hideDialog('#loadingDialog');
            },
            success:function(json,statusText){
                if(json.errorCode == 0){
                    $("#shadow").hide();
                    showDialog("#successDialog",'恭喜您','操作成功','','',function(){
		                    window.location.href="./index.php?c=store&a=manageOrder&r="+Math.random()+"";
                     });
                }else{
                	showDialog("#errorDialog",'',json.errorInfo,'',1500);
                }
            },
            error:function(){
            	showDialog("#errorDialog",'','请求失败，网络异常！','',1500);
            }
        });
    }
    */
	
	init();
})
