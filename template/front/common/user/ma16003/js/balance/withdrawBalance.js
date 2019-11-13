/**
 * 佣金提现
 * @since 2015-11-14
 * @author jjhu
 */

$(function(){
    /**
     * 初始化
     */
    function init(){
        bindEvent();
    }
    function limitTime(){
    	var date = new Date();
    	var limitTime = $("#content .notice1").attr("limit-time");
    	var errorInfo = $("#content .notice1").attr("error-info");
    	if(date.getDate() < limitTime){
    		$("#content .notice2").html('本月提现时间还没到，不能提现哦');
    		return false;
    	}else if(errorInfo != 0){
    		$("#content .notice2").html('本月您已经操作过咯，再等等吧');
    	}
    	
    }
    /**
     * 绑定事件
     */
    function bindEvent(){
    	//limitTime();
        //账户单选事件
        $(".account-list .item-option").click(function(){
            var _this = $(this);
            if(!_this.hasClass("checked")){//当前为选中状态,则取消选中状态
                //分销商账户单选事件
                $(".account-list .item-option").removeClass("checked");
                $(".account-list .item-option").find(".selected").hide();
                $(".account-list .item-option").find(".un-selected").show();
                _this.find(".un-selected").hide();
                _this.find(".selected").show();
                _this.addClass("checked");
            }
        });
        
        //控制输入框输入金额格式
        $("#content .cash-area .cash-value").blur(function(){
            var reg = $(this).val().match(/\d+\.?\d{0,2}/);
            var txt = '';
            if(reg != null){
                txt = reg[0];
            }
            $(this).val(txt);
        }).change(function(){
            var v = $(this).val();
            if(/\.$/.test(v)){
                $(this).val(v.substr(0, v.length - 1));
            }
        }).keyup(function(){
        	$(this).triggerHandler('blur');
        }).keypress(function(){
        	$(this).triggerHandler('blur');
        });
        
        //我要提现
        $("#content .withdraw a").click(function(){
        	var aid = "";//分销商账户表主键id
        	var model = $(this).attr("data-model");//提现模式，1：微信零钱，0：填写账号，线下打款 ,2:红包
        	var user_id = $(this).attr("data-user-id");//分销商主键id
            var money = $.trim($("#content .cash-value").val());//提现金额
        	if(model == 0){//线下打款
	        	//限制提现时间
	        	var date = new Date();
	        	var limitTime = $("#content .notice1").attr("limit-time");
	        	var errorInfoData = $("#content .notice1").attr("error-info");
	        	if(errorInfoData == 1){
	        		alert('本月您已经操作过咯，再等等吧');
	        		return false;
	        	}else if(date.getDate() < limitTime){
	        		alert('本月提现时间还没到，不能提现哦');
	        		return false;
	        	}
	        	
	        	//选择取款账户
	            var checkedOption = $(".account-list .item-option.checked");
	            if(checkedOption.length == 0){
	                alert("请选择收款账户！");
	                return false;
	            }
	            aid = checkedOption.attr("data-aid");//分销商账户表主键id
        	}else if(model == 2){//微信红包模式  微信红包限制2000元（包含）以内
        		if(money > 2000){
        			alert("抱歉，提现金额超额，微信零钱提现不能超过2000元");
        			return false;
        		}
        	}else{
        		if(money > 200){
        			alert("抱歉，提现金额超额，微信红包不能超过2000元");
        			return false;
        		}
        	}
        	
            
            if( $("#content .cash-area .cash-value").focus().val() == '' || $("#content .cash-area .cash-value").focus().val() == 0){
	            alert("请填写金额");
            	return false;
            }
            //判断金额是否合法且在范围之内
           
            var myfee = parseFloat($("#content .top-area .myfee").text());//可提现的佣金
            var valuefee = parseFloat($("#content .cash-area .cash-value").val());//填写的佣金
            if(myfee < valuefee){
            	alert("填写的金额超过可提现的金额");
            	$("#content .cash-area .cash-value").focus();
            	return false;
            }
            $.ajax({
                type:"post",
                url:"./index.php?c=base_user&a=balanceApply",
                data:{"user_id":user_id,"aid":aid,"money":money,"model":model},
                dataType:"json",
                success:function(json,statusText){
                    if(json.errorCode == 0){
                        showDialog("#successDialog",json.errorInfo,json.data,'',3000,function(){
                        	var search = window.location.search;
                        	window.location.href="./index.php"+search+'&r='+Math.random();
                        })
                    }else{
                        showDialog("#alertDialog",json.errorInfo,json.data)
                    }
                },
                error:errorResponse
            });
        });
    }

    init();
});