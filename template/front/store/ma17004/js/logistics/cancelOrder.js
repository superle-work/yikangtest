$(function(){
//  var total = 1;//分页总页面数
//  var currentPage = 1;//当前页
//  var pageSize = 20;//每页显示的记录数
    var stop = true;//控制是否出发滚动加载

    function init(){
       	myPagination();	    	
        bindEvent();
    }

    function bindEvent(){
    	//滚动实现加载
    	$(window).scroll(function(){
    		if(stop == true){
	    		var scroll_top = $(window).height()+$(window).scrollTop();
	    		if(scroll_top > get_more_top){
	    			//异步请求，渲染商品列表
	    			render(true,1);
	    		}
    		}
    	});
    	
    	//点击选择显示收入记录、提现记录
    	$(".top-area div").click(function(){
    		if(!$(this).hasClass('cur')){
    			$(this).siblings().removeClass("cur");
    			$(this).addClass("cur");
    			render(true,2);
    		}
    	})
    }

    /**
     * 分页显示方法
     */
    function myPagination(){
        render(true,1);
        //调用公共分页方法
//      pagination("#page-selection",{total:total,pageSize:pageSize},render);

    }
    /**
     * 获取模糊参数
     */
    function getSelectInfo(){     
        var selectInfo = {
        	state:$(".top-area .cur").attr("data-state"),
            pageIndex : $("#content .get-more").attr("data-start"),
        	pageSize : $("#content .get-more").attr("data-num")
        };
        return selectInfo;
    }
    /**
     * 分页动态渲染数据
     * @param async ajax请求是否异步
     * @param pageIndex 当前显示页
     * @param pageSize 每页显示记录数
     */
    function render(async,type){
    	if(type != 1){
			$("#content .get-more").attr('data-start',1);
		}
        var selectInfo = getSelectInfo();
        var num = $("#content .get-more").attr("data-num");
        var start = $("#content .get-more").attr("data-start");
        $.ajax({
            async:async,
            type:'post',
            url:'./index.php?c=store&a=pagingCancelOrder',
            data:selectInfo,//从1开始计数
            dataType:'json',
            beforeSend:function(xhr){
	            stop = false;
            	//$("#content .get-more").text("正在加载中");
	        },
//	        complete:function(){
//	               //隐藏“加载中。。。”
//	            	hideDialog("#loadingDialog");
//	        },
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                	var nick_name='';
                	if(result.nick_name){
                		nick_name=result.nick_name;
                	}
                	else{
                		nick_name="提现";
                	}
                    var myList = result.data.orderList;
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var oid = obj.id;
                        var add_time = obj.add_time;//下单时间
                        var order_num = obj.order_num;//订单号
                        var nick_name = obj.nick_name;//昵称
                        var head_img_url = obj.head_img_url;//头像
                        
						
                        var state=""
                        if(obj.state==2){
                        	state="<div class='state-2'>待送检</div>";
                        }
                        else if(obj.state==3){
                        	state="<div class='state-3'>送检中</div>";
                        }
                        else if(obj.state==4){
                        	state="<div class='state-4'>检测中</div>";
                        }
                        else if(obj.state==5){
                        	state="<div class='state-5'>已完成</div>";
                        }
                        html+='<div class="order-info">'
            				+'<a href="index.php?c=store&a=orderDetail&from=3&oid='+oid+'">'
	            			+'<div class="order-num"><div class="num">'
	            			+'<i class="icon iconfont">&#xe62e;</i>&nbsp;'
	            			+'<span>订单号:'+order_num+'</span></div>'+state+'</div>'
	            			+'<div class="user-info"><div class="head_img">'
	            			+'<img src="'+head_img_url+'"/>'
	            			+'</div><div class="userInfo"><div class="name">'+nick_name+'</div>'
	            			+'<div class="addtime">'+add_time+'</div></div></div></a></div>'
            				+'<div class="space2"></div>';
                    }
                    
                    if(type == 1){//滚动加载
						$("#content .order-list").append(html);
					}else{
						$("#content .order-list").html(html);
					}
            	  	
                    $("#content .get-more").attr("data-num",10); //还原pageSize  
                    if(myList.length < num || result.data.pageInfo.total_page == 1){//判断数据是否加载完
                   	    stop = false;
                   	    $("#content .get-more").attr("data-start",parseInt(start)+1);
               			//$("#content .get-more").text("没有更多咯");
	               	}else{
	               		get_more_top = $("#content .get-more").offset().top;
	               		$("#content .get-more").attr("data-start",parseInt(start)+1);	               		
	               		//$("#content .get-more").text("下拉加载更多");
	               		stop = true;
	               	}
	               	
	               	if(myList.length==0){
	               		$("#content .order-list").html("<p class='text-danger'>无数据！！！</p>");
	               	}
                }else{
                    $("#content .order-list").html("<p class='text-danger'>"+result.errorInfo+"</p>");
                }
            },
            error:function(){
                $("#content .order-list").html("<p class='text-danger'>请求失败，网络异常！</p>");
            }
        });
    }
    
    init();
});