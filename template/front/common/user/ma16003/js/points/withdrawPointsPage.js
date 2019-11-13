/**
 * 佣金提现
 * @since 2015-11-14
 * @author xdzhu
 */

$(function(){
    /**
     * 初始化
     */
    function init(){
    	
        bindEvent();
    }
    
    /**
     * 
     */
    function bindEvent(){
    	//选择提现金额
    	
    	$("#content .chose_area .item").click(function(){
        	$(this).addClass("cur");
        	$(this).siblings().removeClass("cur");
        })
    	
    	
    	//提交提现金额
        $("#content .submit").click(function(){
        	
	        if($("#content .chose_area input.item").hasClass("cur")){//自定义提现
	         	money = $.trim($("#content .chose_area input.item.cur").val());
	        }else{//固定提现
	         	money = $("#content .chose_area .item.cur").attr("value");
	        }
	        var total_points = parseInt($("#content .total_points").text());
	        var proportion = $("#content .total_points").attr("proportion");
	        var points = money/proportion;
	        if(points > total_points){
	        	showDialog("#errorDialog","错误","当前积分不足！","",2000);
	        	return false;
			}	        
	        //发送请求，修改我的个人信息
            $.ajax({
                url:"./index.php?c=base_user&a=addPointsRecord",
                type:"post",
                data:{"points":points},
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
                        showDialog('#successDialog','提现成功！','','','2000',function (){
                        	window.history.go(-1);
                        });
                    }else{
                        //注册失败
                    	showDialog('#errorDialog','提现失败！',"操作失败，"+json.errorInfo+"！",'');
                    }
                },
                error:function(){
                    //请求失败
                	showDialog('#errorDialog','抱歉！网络异常',"网络异常，请稍后再试！",'');
                }
            });
	         
        
        })
	}
    init();
});