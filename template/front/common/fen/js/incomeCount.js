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
            url:'./index.php?c=fen&a=showIncomeCount',
            data:selectInfo,//从1开始计数
            dataType:'json',
            beforeSend:function(xhr){
	            stop = false;
	        },
	        complete:function(){
	            stop = true;
	        },
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                    var myList = result.data.dataList;
					html+='<div class="header"><div class="name">姓名</div><div class="phone">手机号码</div><div class="total">累计收益</div></div>';
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var id = obj.id;
                        var name = obj.name;//名称
                        var phone = obj.phone?obj.phone:'---';//电话
                        var income = obj.total_fee;//收入
                        html+='<div class="user-info"><div class="name2">'+name+'</div>'
			    			+'<div class="phone">'+phone+'</div>'
			    			+'<div class="total income">'+income+'</div></div>';
                    }
                    
                    if(type == 1){//滚动加载
						$("#content .top-area").append(html);
					}else{
						$("#content .top-area").html(html);
					}
            	  	
                    $("#content .get-more").attr("data-num",10); //还原pageSize  
                    if(myList.length < num || result.data.pageInfo.total_page == 1){//判断数据是否加载完
                   	    stop = false;
                   	    $("#content .get-more").attr("data-start",parseInt(start)+1);
               			$("#content .get-more").text("没有更多咯");
	               	}else{
	               		get_more_top = $("#content .get-more").offset().top;
	               		$("#content .get-more").attr("data-start",parseInt(start)+1);	               		
	               		$("#content .get-more").text("下拉加载更多");
	               		stop = true;
	               	}
                    
                    if(myList.length == 0){
                        html = '<p class="text-danger">查询结果为空。</p>';
                    }
                    $("#content .top-area").html(html);


                }else{
                    $("#content .top-area").html("<p class='text-danger'>"+result.errorInfo+"</p>");
                }

            },
            error:function(){
                $("#content .top-area").html("<p class='text-danger'>请求失败，网络异常！</p>");
            }
        });
    }
    
    init();
});