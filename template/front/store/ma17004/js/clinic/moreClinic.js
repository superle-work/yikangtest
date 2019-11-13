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
    	// //滚动实现加载
    	// $(window).scroll(function(){
    	// 	if(stop == true){
	    // 		var scroll_top = $(window).height()+$(window).scrollTop();
	    // 		if(scroll_top > get_more_top){
	    // 			//异步请求，渲染商品列表
	    // 			render(true,1);
	    // 		}
    	// 	}
    	// });
    	
        //enter事件
        $(".search-goods-area input").keydown(function(event){
            event = event ? event:window.event;
            if(event.keyCode == 13){
                render(true);
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
        var keywords = $.trim($(".search-goods-area .search-text").val());
        var cid = $.trim($("#cid").val());        
        var field = $("#content .goods-sort-area .sort-selected").attr('sort-field');
        var sort = $("#content .goods-sort-area .sort-selected").attr('sort');
        var f_sort = field+" "+sort;
        var selectInfo = {
            "keywords":keywords,
            "sort":f_sort,
            "cid":cid,
            'pageIndex' : $("#content .get-more").attr("data-start"),
        	'pageSize' : $("#content .get-more").attr("data-num")
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
        //var selectInfo = getSelectInfo();
        var num = $("#content .get-more").attr("data-num");
        var start = $("#content .get-more").attr("data-start");
        $.ajax({
            async:async,
            type:'post',
            url:'./index.php?c=store&a=getMoreClinic',
            data:selectInfo,//从1开始计数
            dataType:'json',
            beforeSend:function(xhr){
	            stop = false;
            	showDialog("#loadingDialog");
	        },
	        complete:function(){
	            //隐藏“加载中。。。”
	            hideDialog("#loadingDialog");
	        },
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
//                  total = result.data.pageInfo.total_page;
//                  if(async){
//                      $("#page-selection").bootpag({total:total});//重新计算总页数
//                  }
//                  currentPage = result.data.pageInfo.current_page;
                    var myList = result.data;
					console.log(myList)
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var id = obj.id;
                        var name = obj.name;
                        var thumb = obj.thumb;//缩略图
                        var address = obj.address;//地址
                        var phone = obj.phone;//电话
                        var distance = obj.distance;//距离
                        html +='<div class="col-xs-12 col-sm-12 col-md-12 item"><div class="goods">'
                        	 +'<a href="./index.php?c=store&a=clinicDetail&id='+id+'" class="goods-info" 								title="诊所详情" data-id="'+id+'">'
	                         +'<div class="image"><img alt="'+name+'" src="'+thumb+'"></div>'
	                         +'<div class="detail"><div class="name">'+name+'</div><div class="space2"><span class="addr">'
	                         +'<i class="icon iconfont colo">&#xe639;</i><span 								class="address">'+address+'</span></span><span 								class="distance">'+distance+'</span>'
	                         +'</div><div><i class="icon iconfont colo2">&#xe67f;</i><span 								class="phone">'+phone+'</span></div></div></a></div></div>';
                    }
                    
                    if(type == 1){//滚动加载
						$("#content .goods-display-area").append(html);
					}else{
						$("#content .goods-display-area").html(html);
					}
            		                 
                 //    $("#content .get-more").attr("data-num",10); //还原pageSize  
                 //    if(myList.length < num ){//判断数据是否加载完
                 //   	    stop = false;
                 //   	    $("#content .get-more").attr("data-start",parseInt(start)+1);
               		// 	//$("#content .get-more").text("没有更多咯");
	               	// }else{
	               	// 	get_more_top = $("#content .get-more").offset().top;
	               	// 	$("#content .get-more").attr("data-start",parseInt(start)+1);	               		
	               	// 	//$("#content .get-more").text("下拉加载更多");
	               	// 	stop = true;
	               	// }
                    
//                  if(myList.length == 0){
//                      html = '<p class="text-danger">查询结果为空。</p>';
//                  }
//                  $("#content .goods-display-area").html(html);
                }else{
                    $("#content .goods-display-area").html("<p class='text-danger'>"+result.errorInfo+"</p>");

                }

            },
            error:function(){
                $("#content .goods-display-area").html("<p class='text-danger'>请求失败，网络异常！</p>");
            }
        });
    }

    init();
});