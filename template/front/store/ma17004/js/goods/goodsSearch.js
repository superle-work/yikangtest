$(function(){
//  var total = 1;//分页总页面数
//  var currentPage = 1;//当前页
//  var pageSize = 20;//每页显示的记录数
    var stop = true;//控制是否出发滚动加载

    function init(){
    	
    	if($.cookie('seachSTop')){//判断cookie 是否存在
			var start = $.cookie('seachStartT'); //页数 
			start = start-1;
			$("#content .get-more").attr("data-num",start*10);//查询总数			
			
//	    	$("#cid").val($.cookie('sortCid')); //设置cid
	    	$(".search-goods-area .search-text").val($.cookie('keyWords'));  //设置搜索关键字 
	    	var sortIndex = $.cookie('sortIndex');
	    	if(sortIndex != null){
	    		$('#content .goods-sort-area .sort').eq(sortIndex).addClass('sort-selected').siblings().removeClass('sort-selected');//设置排序index
	    		priceIndex = $.cookie('priceIndex');
	    		if(priceIndex != null){
	    			$('#content .goods-sort-area .sort-selected i').eq(priceIndex).addClass('cur').siblings().removeClass('cur');//设置价格排序\
	    			if(priceIndex == 0){
	    				$('#content .goods-sort-area .sort-selected.price').attr('sort','asc');	    	
	    			}else{
	    				$('#content .goods-sort-area .sort-selected.price').attr('sort','desc');
	    			}
	    		}
	    	}
	    	    	
			render(true);
			
			//清楚cookie
//			$.cookie('seachStartT',null);
//	        $.cookie('sortCid',null);
	    	$.cookie('keyWords',null);
	    	$.cookie('sortIndex',null);
	    	$.cookie('priceIndex',null);
	    }else{
       		 myPagination();	    	
	    }
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
    	
        /**
         * 模糊查询事件
         *
         */
        $(".search-goods-area .search-goods-right").click(function(){
            render(true);
        });
        //enter事件
        $(".search-goods-area input").keydown(function(event){
            event = event ? event:window.event;
            if(event.keyCode == 13){
                render(true);
            }
        });
        //条件选择
        $("#content .goods-sort-area .sort").click(function(){
        	$.cookie('seachSTop',null);
        	//判断是否再次点击的是价格
        	if($(this).children('.iconfont').hasClass('cur')){
        		if($(this).children('.icon-top').hasClass('cur')){
        			$(this).children('.icon-top').removeClass('cur');
        			$(this).children('.icon-bottom').addClass('cur');
        			$(this).attr('sort','desc');
        		}else{
        			$(this).children('.icon-bottom').removeClass('cur');
        			$(this).children('.icon-top').addClass('cur');
        			$(this).attr('sort','asc');
        		}
        		
        	}else{
	    		$(this).siblings().removeClass('sort-selected');
	    		$(this).addClass('sort-selected');
	    		//判断是否是价格
	    		if($(this).hasClass('price')){
	    			$(this).children('.icon-top').addClass('cur');
	    			$(this).attr('sort','asc');
	    		}else{
	    			if($('#content .goods-sort-area .sort.price .iconfont').hasClass('cur')){
	    				$('#content .goods-sort-area .sort.price .iconfont').removeClass('cur');
	    			}
	    		}
	    	}
    		render(true);	
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
            url:'./index.php?c=store&a=pagingSearchGoods',
            data:selectInfo,//从1开始计数
            dataType:'json',
            beforeSend:function(xhr){
	            stop = false;
            	$("#content .get-more").text("正在加载中");
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
                        var thumb = obj.thumb;//商品缩略图
                        var name = obj.name;//商品名称
//                      name = name.substr(0,30)+'...';
                        var simple_desc = obj.simple_desc;//商品描述
                        var price = obj.price;//商品标准价格
                        var good_comment = obj.good_comment;//商品好评率
                        var sale_quantity = obj.sale_quantity?'<span class="sale_quantity">'+ obj.sale_quantity +'</span>':'';
                        html +='<div class="item clearfix">'
//                           +'<a class="goods-detail" href="./index.php?c=store&a=goodsDetail&id='+id+'">'
							 +'<a class="goods-detail" data-id="'+id+'">'
                             +'<div class="goods-image"><img src="'+thumb+'"></div><div class="goods-info"><p class="name"><span class="goods-name-info">'+name+'<span></p><p class="price"><i class="icon iconfont">&#xe604;</i><span>'+price+'</span></p><p class="sale_quantity_title">总售'+ sale_quantity +'份<span class="good_comment">'+ good_comment +'%好评</span></p></div>'
                             +'</a>'
                           //+'<div class="add-cart-area"><span class="add-cart" title="加入购物车"  data-id="'+id+'"><i class="icon iconfont">&#xe609;</i></span></div>'
                             +'</div>';
                    }
                    
                    if(type == 1){//滚动加载
						$("#content .goods-display-area").append(html);
					}else{
						$("#content .goods-display-area").html(html);
					}
					//判断滚动高度cookie是否存在
                    if($.cookie('seachSTop')){
            	    	start = parseInt($.cookie('seachStartT')) - 1; //将分页参数减一 因为后面会增加  	    	
            		   	$(window).scrollTop($.cookie('seachSTop'));
            		   	$.cookie('seachSTop',null);
            		   	$.cookie('seachStartT',null);
            		}
            		$('.goods-display-area .goods-detail').click(function(){
            			var id = $(this).attr('data-id');
            	    	$.cookie('seachSTop',$(window).scrollTop()); //记录滑动高度
            	    	var _start = $("#content .get-more").attr("data-start");
            	    	$.cookie('seachStartT',_start); //记录分页参数
            	    	var sortIndex,cid,keywords,priceIndex;
            	    	sortIndex = $('#content .goods-sort-area .sort-selected').attr('data-index');
            	    	if($('#content .goods-sort-area .sort-selected i').hasClass('cur')){
            	    		priceIndex = $('#content .goods-sort-area .sort-selected i.cur').attr('data-index');
            	    		$.cookie('priceIndex',priceIndex);//记录价格排序参数
            	    	}
//          	    	sortCid = $.trim($("#cid").val()); 
            	    	keyWords = $.trim($(".search-goods-area .search-text").val());           	
//          	    	$.cookie('sortCid',sortCid); //记录分类id
            	    	$.cookie('sortIndex',sortIndex); //记录排序index
         	    		$.cookie('keyWords',keyWords);   //记录搜索关键字 		
            	    	window.location.href='./index.php?c=store&a=goodsDetail&id='+id;
            	  	});                  
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