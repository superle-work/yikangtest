/****
 * 商品分类
 * @author lynn
 * @since 2016-09-20
 */
$(function(){
    //底部滚动加载参数
    var stop = true;//控制是否出发滚动加载
    
    /**
     * 初始化
     */
    function init(){
	    bindEvent();
    	render(true,1);
    }
    
    /**
     * 绑定事件
     */
    function bindEvent(){
    	//偏移量
    	var box_left = $("#content").offset().left;
    	$(".cate-wrap .cate-list.sec").css("left",box_left + 120);
    	$(".cate-wrap .cate-list.thr").css("left",box_left + 200);
    	
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
    	
    	//点击商品分类
    	$(".top-nav").click(function(){
    		toggleCate(0);
    	});;
    	$("#content").click(function(){
    		toggleCate(1);
    	});
    	//分类列表点击事件
    	$(".cate-wrap .cate-list > span").click(function(){
    		var _this = $(this);
    		if(_this.hasClass("active")){
    			return false;
    		}
    		$(".top-nav .cate-select").text(_this.attr("data-name"));
    		_this.siblings("span.active").removeClass("active");
    		$(".cate-wrap .cate-list > span.current").removeClass("current");
    		_this.addClass("active");
    		_this.addClass("current");
    		var rank = _this.attr("data-rank");//级别
    		//对选中分类进行处理
    		if(rank == 1){
				$(".cate-wrap .cate-list.sec > span.show,.cate-wrap .cate-list.thr > span.show").removeClass("show");
				$(".cate-wrap .cate-list.sec > span.active,.cate-wrap .cate-list.thr > span.active").removeClass("active");
				$(".cate-wrap .cate-list.sec").removeClass("active");
				$(".cate-wrap .cate-list.thr").removeClass("active");
			}else if(rank == 2){
				$(".cate-wrap .cate-list.thr > span.show").removeClass("show");
				$(".cate-wrap .cate-list.thr > span.active").removeClass("active");
				$(".cate-wrap .cate-list.thr").removeClass("active");
			}
    		
    		if(_this.find(".triangle-down").length > 0){//有下一级
    			var this_fir = _this.attr("data-fir");
    			if(rank == 1){
    				$(".cate-wrap .cate-list.sec > span").each(function(){
    					var _item = $(this);
    					var item_fir = _item.attr("data-fir");
    					if(this_fir == item_fir){
    						_item.addClass("show");
    					}
    				})
    				$(".cate-wrap .cate-list.sec").addClass("active");
    			}else if(rank == 2){
    				var this_sec = _this.attr("data-sec");
    				$(".cate-wrap .cate-list.thr > span").each(function(){
    					var _item = $(this);
    					var item_fir = _item.attr("data-fir");
    					var item_sec = _item.attr("data-sec");
    					if(this_fir == item_fir && this_sec == item_sec){
    						_item.addClass("show");
    					}
    				})
    				$(".cate-wrap .cate-list.thr").addClass("active");
    			}
    		}else{
    			$(".cate-wrap").removeClass("active");
    		}
    		render(true,0);
    	})
    }
    
    /**
     * 隐藏、显示分类列表
     */
    function toggleCate(type){
    	var type = type || 0;
    	if(type == 0){//顶部点击
	    	if($(".cate-wrap").is(":visible")){
				$(".cate-wrap,.top-nav .cate-click").removeClass("active");
			}else{
				$(".cate-wrap,.top-nav .cate-click").addClass("active");
			}
    	}else{//点击空白处
    		if($(".cate-wrap").is(":visible")){
				$(".cate-wrap,.top-nav .cate-click").removeClass("active");
			}
    	}
    }
    
    /**
     * 获取模糊参数
     */
    function getSelectInfo(){
        var selectInfo = {
//  		cid : $(".cate-wrap span.current").attr("data-id"),
			cid : $('#cid').val(),
			name : $('#name').val(),
            start : $("#content .get-more").attr("data-start"),
            num : $("#content .get-more").attr("data-num"),
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
    	var start = $("#content .get-more").attr("data-start");
    	var num = $("#content .get-more").attr("data-num");
        var selectInfo = getSelectInfo();
		$.ajax({
            type:'post',
            url:'./index.php?c=store&a=pagingCateGoods',
            data:selectInfo,
            beforeSend:function(){
            	stop = false;
            	$("#content .get-more").text("正在加载中");
            },
            dataType:"json",
            success:function(result){
            	//console.log(result);
                var html ='';
                if(result.errorCode == 0){
            	   var dataList = result.data;
            	   for(var i = 0; i < dataList.length; i++){
            		   var obj = dataList[i];
                       var id = obj.id;
            		   var price = obj.price;
            		   var name = obj.name;
            		   var thumb = obj.thumb;
            		   var desc = obj.simple_desc;
            		   html += '<div class="col-xs-6 col-sm-6 col-md-6 item">'
            			   		+ '<div class="goods">'
            			   		+ '<a href="./index.php?c=store&a=goodsDetail&id='+id+'" class="goods-info" title="商品详情" data-id="'+id+'">'
            			   		+ '<div class="image">'
            			   		+ '<img alt="'+name+'" src="'+thumb+'">'
            			   		+ '</div>'
            			   		+ '<div class="name">'+name+'</div>'
            			   		+ '</a>'
            			   		+ '<div class="detail">'
            			   		+ '<span class="price"><i class="icon iconfont">&#xe604;</i>'+price+'</span>'
            			   		+ '<a href="./index.php?c=store&a=goodsDetail&id='+id+'" class="btn-buy" data-id="'+id+'" data-standard="'+obj.standard+'">查看</a>'
            			   		+ '</div>'
            			   		+ '</div>'
            			   		+ '</div>'
            	   }
            	   if(type == 1){//滚动
            		   $("#content .label-display").append(html);
                   }else{//点击
                	   $("#content .label-display").html(html);
                   }
                   if(dataList.length < num ){//判断数据是否加载完
                   		stop = false;
                	   $("#content .get-more").text("没有更多咯");
               	   }else{
               		   get_more_top = $("#content .get-more").offset().top;
               		   $("#content .get-more").attr("data-start",parseInt(start)+1);
               		   stop = true;
               		   $("#content .get-more").text("下拉加载更多");
               	   }
               }else{
                   showDialog('#errorDialog','result.errorCode','result.errorInfo','',1500);
               }
            },
            error:showDialog('#errorDialog',' ','请求失败，网络异常！')
        });
    }
    
    init();
});