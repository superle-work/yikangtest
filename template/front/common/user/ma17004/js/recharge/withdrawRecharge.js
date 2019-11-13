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
     * 绑定余额提现
     */
    function bindEvent(){
    	//选择提现金额    	
    	$("#content .chose_area .item").click(function(){
    		var _this = $(this);
    		var money = _this.attr("data-money");
        	
        	if(_this.hasClass("get_money")){       	
    			_this.find(".defined").focus();  
			}    
			
			if(_this.hasClass("active")){
    			return false;
    		}
			
			$("#content .chose_area .item.active").removeClass("active");
    		_this.addClass("active");
    		
        	
        })
 	
    	//提交提现金额
        $("#content .submit").click(function(){
        	
	        if($("#content .chose_area .item.active").hasClass("defined")){//自定义提现
	         	money = $.trim($("#content .chose_area .defined").val());
	        }else{//固定提现
	         	money = $("#content .chose_area .item.active").attr("data-money");
	        }
	        var total_balance = $("#content .total_balance").text();
	        if(money > 200){
	        	showDialog("#errorDialog","错误","提现金额不得大于200元！","",2000);
	        	return false;
			}
	        
//	        if(money > total_balance){
//	        	showDialog("#errorDialog","错误","当前余额不足！","",2000);
//	        	return false;
//			}
//     
	        //发送请求，修改我的个人信息
            $.ajax({
                url:"./index.php?c=base_user&a=withdrawBalance",
                type:"post",
                data:{"money":money},
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