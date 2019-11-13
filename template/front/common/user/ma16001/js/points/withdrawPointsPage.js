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
     * 绑定事件
     */
    function bindEvent(){
        
        //控制输入框输入金额格式
        $("#content .points-area .points-value").keyup(controllInput);
        
        //我要提现
        $("#content .withdraw a").click(function(){
        	var _this = $(this);
        	if(_this.hasClass("can-use")){
        		_this.removeClass("can-use");
        		var points = $("#content .points-area .points-value").val();
        		if(isNaN(points)){
	           		alert("积分填写错误");
	           		return false;
	            }
        		var type = $(this).attr('data-type');
	            $.ajax({
	                type:"post",
	                url:"./index.php?c=base_user&a=addPointsRecord",
	                data:{"points":points,"type":type},
	                dataType:"json",
	                success:function(json,statusText){
	                    if(json.errorCode == 0){
	                        alert("提现成功,请去平台领取红包");
	                        window.location.href = "./index.php?c="+type+"&a=pointsCenter&r="+Math.random();
	                    }else{
	                        alert(json.errorInfo);
	                    }
	                },
	                error:function(){
	                    alert("请求失败，网络异常！");
	                }
	            });
            }
        });
        
    }
    
	/**
	 * 控制输入框填写
	 */
	function controllInput(){
		var points = parseInt($(this).val());
        if(!/^[1-9]\d*$/.test(points) ){
//        	$(this).val(points.substr(0,points.length - 1));
        	return false;
        }else if(points > parseInt($("#content .myfee").text())){
    		this.value = parseInt($("#content .myfee").text());
    		points = this.value;
        }
        
        var pointsRate = $(this).attr("data-rate");
        var money = (points*pointsRate).toFixed(3).toString();
        var cashMoney = money.substring(0,money.lastIndexOf('.')+3);
        $("#content .cash-area .cash-value").text(cashMoney);
        //判断是否满足提现门槛
        if(points >= parseInt($(this).attr("data-max")) || points > 0 ||cashMoney <= 200){
        	$("#content .withdraw a").addClass("can-use");
        }else{
        	$("#content .withdraw a").removeClass("can-use");
        }
            
	}
    init();
});