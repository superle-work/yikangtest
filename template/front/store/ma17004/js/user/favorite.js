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
            url:'./index.php?c=store&a=pagingSearchFavorite',
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
//                  total = result.data.pageInfo.total_page;
//                  if(async){
//                      $("#page-selection").bootpag({total:total});//重新计算总页数
//                  }
//                  currentPage = result.data.pageInfo.current_page;
                    var myList = result.data.goodsList;

                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var id = obj.id;
                        var img_url = obj.img_url;//商品缩略图
                        var name = obj.name;//商品名称
                        var price = obj.price;//商品标准价格
                        html+='<div class="goods-area">'
            				+'<div class="goods">'
                    		+'<a href="index.php?c=store&a=goodsDetail&id='+id+'" class="goods-info" title="'+name+'">'
                        	+'<div class="goods-descript-info">'
                        	+'<div class="name">'+name+'</div>'
	                        +'<div class="detail"><span class="ori-price">'
	                        +'<i class="icon iconfont">&#xe645;</i>'+price+'</span>'
	                        +'</div></div></a><div class="action">'
                    		+'<button data-id="'+id+'" class="delete">删除</button></div>'
            				+'</div></div><div class="space2"></div>';
                    }
                    
                    if(type == 1){//滚动加载
						$("#content .goods-display-area").append(html);
					}else{
						$("#content .goods-display-area").html(html);
					}
					
            	  	//删除收藏
            	  	$(document).on('click',".delete",deleteFavorite);
            	  	
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
                        html = '<p class="text-danger">无数据。</p>';
                    }
                    $("#content .goods-display-area").html(html);


                }else{
                    $("#content .goods-display-area").html("<p class='text-danger'>"+result.errorInfo+"</p>");

                }

            },
            error:function(){
                $("#content .goods-display-area").html("<p class='text-danger'>请求失败，网络异常！</p>");
            }
        });
    }
    
    //删除收藏
    function deleteFavorite(){
    	var gid = $(this).attr('data-id');
    	$.ajax({
            type:"post",
            url:"./index.php?c=store&a=deleteFavorite",
            data:{"gid":gid},
            dataType:"json",
            beforeSend:function(xhr){
                //显示“加载中。。。”
                $("#loading").modal('show');
            },
            complete:function(){
                //隐藏“加载中。。。”
                $("#loading").modal('hide');
            },
            success:function(json,statusText){
                if(json.errorCode == 0){
                	//showDialog("#successDialog",'恭喜您','操作成功','&#xe604;',1000);
                    render(true);
                }else{
                    showDialog('#errorDialog','',json.errorInfo);
                }
            },
            error:errorResponse
        });
    }

    init();
});