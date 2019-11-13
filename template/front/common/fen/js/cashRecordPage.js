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
        	type:$(".top-area .cur").attr("data-type"),
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
            url:'./index.php?c=fen&a=pageingRecord',
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
                    var myList = result.data.dataList;
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var id = obj.id;
                        var add_time = obj.add_time;//提现时间
                        
                        if(selectInfo.type==1){
                        	var money = '<div class="money">-'+obj.money+'</div>';//提现金额
                        }
                        else if(selectInfo.type==2){
                        	var money='<div class="money red">+'+obj.money+'</div>';//提现金额
                        }
                        
						
                        var typeStr=""
                        //两个表中标识类型的字段不同
                        if(obj.tx_type==1 || obj.distributor_type==1){
                        	typeStr="<span class='tip'>(个人)</span>";
                        }
                        else if(obj.tx_type==2 || obj.distributor_type==2){
                        	typeStr="<span class='tip'>(诊所)</span>";
                        }
                        else if(obj.tx_type==3 || obj.distributor_type==3){
                        	typeStr="<span class='tip'>(医院)</span>";
                        }
                        
                        html+='<tr><td align="left">'
                        	+'<div class="title">'+nick_name+typeStr+'</div>'
                        	+'<div class="add_time">'+add_time+'</div></td>'
			    			+'<td align="right">'+money+'</td></tr>';
                    }
                    
                    if(type == 1){//滚动加载
						$("#content .cash-record-list table").append(html);
					}else{
						$("#content .cash-record-list table").html(html);
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
                }else{
                    $("#content .cash-record-list table").html("<p class='text-danger'>"+result.errorInfo+"</p>");

                }

            },
            error:function(){
                $("#content .cash-record-list table").html("<p class='text-danger'>请求失败，网络异常！</p>");
            }
        });
    }
    
    init();
});