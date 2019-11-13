$(function(){
	var stop = true;//控制是否出发滚动加载
	
//    var total = 1;//分页总页面数
//    var currentPage = 1;//当前页
//    var pageSize = 20;//每页显示的记录数

    function init(){
    	render(true,1);
    	
//    	myPagination();
        bindEvent();
    }

    function bindEvent(){
    	//点击排序导航按钮事件
    	$("#content .goods-sort-area .sort").click(function(){
    		field = $(this).attr('sort-label');
    		sort = $(this).attr('sort');
    		$(this).siblings().removeClass('sort-selected');
    		$(this).addClass('sort-selected');
    		if( $(this).attr('sort') == 'desc'){
    			$(this).attr('sort','asc');
    			$(this).find('i').html('&#xe65b;');
    		}else{
    			$(this).attr('sort','desc');
    			$(this).find('i').html('&#xe65c;');
    		}
    		$("#content .get-more").attr("data-start",1);
    		render(true,2);
//    		myPagination();	
    	});
    	
    	//滚动实现加载
    	$(window).scroll(function(){
    		if(stop == true){
	    		var scroll_top = $(window).height()+$(window).scrollTop();
	    		if(scroll_top > get_more_top){
	    			//异步请求，渲染记录列表
			        render(true,1);
	    		}
    		}
    	});
    }
    
//    /**
//     * 分页显示方法
//     */
//    function myPagination(){
//        render(true,1,pageSize);
//        //调用公共分页方法
//        pagination("#page-selection",{total:total,pageSize:pageSize},render);
//
//    }
    
    /**
     * 获取模糊参数
     */
    function getSelectInfo(){
    	var field = $("#content .goods-sort-area .sort-selected").attr('sort-field');
    	var sort = $("#content .goods-sort-area .sort-selected").attr('sort');
    	var f_sort = field+" "+sort;
    	var label_id = $("#content .label-id").val();
        var selectInfo = {
        	'sort': f_sort,
        	'id':label_id,
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
    	if(type == 0){//点击
			$("#content .get-more").attr({"data-start":0});
			stop = true;
    	}
    	var pageIndex = $("#content .get-more").attr("data-start");
    	var pageSize = $("#content .get-more").attr("data-num");
    	var selectInfo = getSelectInfo();
        $.ajax({
        	async:async,
            type:'post',
            url:'./index.php?c=store&a=pagingLabelGoods',
            data:selectInfo,//从1开始计数
            dataType:'json',
            beforeSend:function(xhr){
	               //显示“加载中。。。”
	            	//showDialog("#loadingDialog");
	            	stop = false;
	            	$("#content .get-more").text("正在加载中");
	        },
	        complete:function(){
	               //隐藏“加载中。。。”
	            	hideDialog("#loadingDialog");
	        },
            success:function(result){
            	//console.log(result);
                var html ='';
                if(result.errorCode == 0){
                	total = result.data.pageInfo.total_page;
                    if(async){
                        $("#page-selection").bootpag({total:total});//重新计算总页数
                    }
                    currentPage = result.data.pageInfo.current_page;
                    var myList = result.data.dataList;
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var id = obj.id;
                        var thumb = obj.thumb;
                        var name = obj.name;
                        var simple_desc = obj.simple_desc;
                        var price = obj.price;
                        var ori_price = obj.ori_price;
                        var add_time = obj.add_time;
                        var sale_quantity = obj.sale_quantity?'<em class="sale_quantity">'+ obj.sale_quantity +'</em>':'';
                        html+='<div class="item clearfix">'
                            	+'<a class="goods-detail" href="./index.php?c=store&a=goodsDetail&id='+ id +'">'
                            		+'<div class="goods-image">'
                            			+'<img src="'+thumb+'">'
                            		+'</div>'
                            		+'<div class="goods-info">'
                            			+'<p class="name">'+ name +'</p>'
                            			+'<p class="desc">'+simple_desc+'</p>'
                            			+'<p class="add-time">上架时间：'+ add_time +'</p>'
                            			+'<p class="price"><i class="icon iconfont">&#xe604;</i><span>'+ price +'</span>'+
                            			'<span class="ori-price"><i class="icon iconfont">&#xe604;</i>'+ ori_price +'</span>'
                            			+'<span class="sale-quantity">已售：'+ sale_quantity +'</span></p>'
                            		+'</div>'
                            	+'</a>'
                            	/*
                            	+'<div class="add-cart-area">'
                            	    +'<span class="add-cart" title="加入购物车" data-id="<!--{$goods.id}-->">'
                            		+'<i class="icon iconfont">&#xe609;</i>'
                            	    +'</span>'
                            	+'</div>'
                            	*/
                            +'</div>';
                    }
                    if(type == 1){//滚动
                    	 $("#content .goods-display-area").append(html);
                    }else{
                    	 $("#content .goods-display-area").html(html);
                    }
                    if(myList.length < pageSize){//判断数据是否加载完
                 	     $("#content .get-more").text("没有更多咯");
                	}else{
                		  get_more_top = $("#content .get-more").offset().top;
                		  $("#content .get-more").attr("data-start",parseInt(pageIndex)+1);
                		  stop = true;
                		  $("#content .get-more").text("下拉加载更多");
                	}
                    if(myList.length == 0 && pageIndex == 1){//没有数据
                        html = '<p class="text-danger">商品正在加速更新中，敬请期待</p>';
                        $("#content .goods-display-area").html(html);
                        $("#content .get-more").text("");
                    }
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