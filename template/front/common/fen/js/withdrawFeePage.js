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
        $("input[name=money]").blur(function(){
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
        $(".withdraw a").click(function(){
        	var tx_uid=$("#tx_uid").val();
        	var tx_type=$("#tx_type").val();
        	var aid=$(".account-list .col-1 .item-option.checked ").attr("data-aid");
            //判断金额是否合法且在范围之内
            var myfee = parseFloat($("#content .top-area .myfee").text());//可提现的佣金
            var valuefee = parseFloat($("input[name=money]").val());//填写的佣金
            if(aid==null || aid==''){
            	alert("请选择提现账户");
            	return false;
            }
            if(myfee < valuefee){
            	alert("填写的金额超过可提现的金额");
            	$("input[name=money]").focus();
            	return false;
            }
            else if(valuefee<=0){
            	alert("填写的金额无意义");
            	$("input[name=money]").focus();
            	return false;
            }
            $.ajax({
                type:"post",
                url:"index.php?c=fen&a=cashApply",
                data:{
                	"money":valuefee,
                	"aid":aid,
                	"myfee":myfee,
                	"tx_uid":tx_uid,
                	"tx_type":tx_type
                },
                dataType:"json",
                success:function(json,statusText){
                    if(json.errorCode == 0){
						showDialog("#successDialog",json.errorInfo,json.data,'&#xe604;',1500,function(){
                        	if(tx_type==1){
                        		location.href="index.php?c=fen&a=fenCenter";
                        	}
                        	else if(tx_type==2){
                        		location.href="index.php?c=store&a=clinicCenter";
                        	}
                        	else{
                        		location.href="index.php?c=store&a=hospitalCenter";
                        	}
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