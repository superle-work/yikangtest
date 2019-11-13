$(function(){
	var stop = true;//控制是否出发滚动加载
	
    function init(){
        bindEvent();
        //异步请求，渲染订单
		renderRecharge(1);
    }

    function bindEvent(){
        //用户变化屏幕方向时调用
        $(window).bind( 'orientationchange resize', function(e){
            width = $("#container").width();//计算当前窗口的宽度
        });

        //滚动条事件
        $(window).scroll(function(){
    		if(stop == true){
	    		var scroll_top = $(window).height()+$(window).scrollTop();
	    		if(scroll_top > get_more_top){
	    			//异步请求，渲染订单
			        renderRecharge(1);
	    		}
    		}
    	})
        
    }

    /**
     * 动态渲染数据
     * type:0点击，1滚动
     */
    function renderRecharge(type){
    	var start = $("#content .get-more").attr("data-start");
    	var num = $("#content .get-more").attr("data-num");
        $.ajax({
            async:true,
            type:'post',
            url:'./index.php?c=base_user&a=pagingRecharge',
            data:{"start":start,"num":num},//从1开始计数
            dataType:'json',
            beforeSend:function(xhr){
                stop = false;
            	$("#content .get-more").text("正在加载中");
            },
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                    var rechargeList = result.data;
                    var dataLength = rechargeList.length;
                    for(var i = 0; i < dataLength; i++){
                        var recharge = rechargeList[i];
                        var add_time = recharge.add_time;//订单状态
                        var money = recharge.money;
                        var reward_coin = recharge.reward_coin;
						var get_real_coin = recharge.get_real_coin;
                        var type = recharge.type== 0?"固定充值":"自定义充值";
                        
                        html+="<div class='recharge-goods-list'>";
                        html+="<table>";
                        html+="<thead><tr><td colspan='2'><span class='add-time'>"+add_time+"</span><td class='td-right'><span>"+type+"</span></td></tr></thead>";
                        html+="<tbody>";
                        html+="<tr class='recharge-goods'>";
                        html+='<td><span>金额：'+money+'元</span></td>';
                        html+='<td class="td-center"><span>赠送'+reward_coin+'元</span></td>';
                        html+='<td class="td-right">共获得'+get_real_coin+'元</td>';
                        html+="</tr>";
                        html+="</table>";
                        html+="</div>";
                    }
                    
					if(type == 1){//滚动
                    	$("#content .recharge-list").append(html);
                    }else{//点击
                    	$("#content .recharge-list").html(html);
                    }
                    if(dataLength < num){//判断数据是否加载完
                		$("#content .get-more").text("没有更多咯");
                	}else{
                		get_more_top = $("#content .get-more").offset().top;
                		$("#content .get-more").attr("data-start",parseInt(start)+1);
                		stop = true;
                		$("#content .get-more").text("下拉加载更多");
                	}
                	
                }else{
                	showDialog('#errorDialog','出错啦~',result.errorInfo,'',1500);
                }

            },
            error:function(){
            	showDialog('#errorDialog','出错啦~','网络异常，操作失败！','',1500);
            }
        });
    }

    init();
});