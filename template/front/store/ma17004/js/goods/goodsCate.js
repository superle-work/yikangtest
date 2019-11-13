$(function(){
	//动态设置左边栏高度
	var winH = $(window).height();
	var serH = $('.search-area').outerHeight(); //搜索框高度
	var botH = $('.bottom-nav').height(); //顶部导航高度
	var barHeight = winH - serH - botH;
	var stop = true; ////是否能下拉加载标志  true 能   false 否
	var cateLevel = $('#cate-level').val(); //分类等级 1 一级分类 2 二级分类 3三级分类
	var t_img; //定时器
	var isLoad = true; //图片判断变量控制变量

	/**
	 * 初始化
	 */
	function init(){
		//幻灯片Swiper插件
        var mySwiper = new Swiper ('.swiper-container', {
            loop: false,
//          speed:400,
            autoplayDisableOnInteraction:true,
            effect:"slide",//fade,cube,coverflow
            observer:true,
            observeParents:true,
            freeMode:true,
            freeModeMomentum:false,
            slidesPerView:'auto',
            spaceBetween : 10, //slide之间的距离
//          slidesPerGroup:'4',
    
            // 如果需要分页器
//          pagination: '.swiper-pagination',
//          paginationClickable:true

            // 如果需要前进后退按钮
            //nextButton: '.swiper-button-next',
            //prevButton: '.swiper-button-prev',

            // 如果需要滚动条
            //scrollbar: '.swiper-scrollbar',
        });
        
		bindEvent();
		render(1);
	};
	
	/**
	 * 绑定事件
	 */
	function bindEvent(){
		//筛选
		var isTrue=true;
		$(".search-area .screening .iconfont,.search-area .screening .screen").click(function(){
			if(isTrue){
				var search=$("#content .search-cont input").val();
				$(".modal-header .search-cont input").val(search)
				$("#sortModal").modal({backdrop:'static'});
				$("#sortModal").modal("show");
				isTrue=!isTrue;
			}
			else{
				$("#sortModal").modal("hide");
				isTrue=!isTrue;
			}
		})
		
		//点击时间排序按钮
		$(".time_but button").click(function(){
			if($(this).hasClass('click_cur')){
				$(this).removeClass("click_cur");
			}
			else{
				$(this).siblings().removeClass('click_cur');
    			$(this).addClass('click_cur');
			}
		})
		
		//点击价格排序按钮
		$(".price_but button").click(function(){
			if($(this).hasClass('click_cur')){
				$(this).removeClass("click_cur");
			}
			else{
				$(this).siblings().removeClass('click_cur');
    			$(this).addClass('click_cur');
			}
		})
		
		//重置按钮
		$(".modal-footer .reset").click(function(){
			//$(".sort-area button:even").addClass('click_cur');  //单数个
			//$(".sort-area button:odd").removeClass('click_cur'); //双数个
			$(".sort-area button").removeClass('click_cur');
			$("input[name=sort]").val('');
			$("#sortModal").modal("hide");
			isTrue=true;
		})
		
		//完成按钮
		$(".modal-footer .finish").click(function(){
			var time=$(".time_but .click_cur").attr("data-sort");
			var price=$(".price_but .click_cur").attr("data-sort");
			var str="";
			if(time){
				str+=time+',';
			}
			
			if(price){
				str+=price+',';
			}
			str=str.slice(0,-1);
			if(str){
				$("input[name=sort]").val(str);
				$("#sortModal").modal("hide");
				isTrue=true;
				render(3);
			}
		})
		
		
		//点击选择分类
    	$('.swiper-wrapper .loan-list').click(function(){
    		$(this).siblings().removeClass('cur');
    		$(this).addClass('cur');
    		render(2);
    	});
		
		//点击搜索的icon
		$('.search-cont .iconfont').click(function(){
			render(3);
		});
		
		
		$(document).keydown(function(e) {  
	    // 回车键事件  
	       if(e.keyCode == 13) {
	   			render(3);
	       }  
	    }); 
    
	    //用户变化屏幕方向时调用
        $('.recommend-goods-diplay-area').bind( 'orientationchange resize', function(e){
            width = $("#wrapper").width();//计算当前窗口的宽度
            $("#wrapper #myBottomNav").width(width);//将底部导航的宽度设置与移动设备相同
        });
        
        //滚动实现加载
    	$(window).scroll(function(){
    		if(stop == true){
	    		var scroll_top = $(window).height()+$(window).scrollTop();
	    		if(scroll_top > get_more_top){
	    			//异步请求，渲染记录列表
			        render(1);
	    		}
    		}
    	});

    	/**
		 * 点击购物车图标
		 */
		$(document).on('click','.cart',function(event) {
			event.stopPropagation();  //阻止事件冒泡
			event.preventDefault();   //阻止链接默认行为
			var gid=$(this).attr("data-id");
			var account=$("#account").val();
			if(gid && account){
				$.ajax({
					type:'post',
					url:'index.php?c=store&a=addCartGoods',
					data:{
						'gid':gid,
						'account':account,
					},
					dataType:'json',
					success:function(res){
						if(res.errorCode==0){
							showDialog('#errorDialog', '添加成功', res.errorInfo, '&#xe683;', 1500,function(){
								//window.location.reload();
							});
						}
						else{
							showDialog('#errorDialog', '添加失败', res.errorInfo, '&#xe6a9;', 2000);
						}
					}
				})
			}
		});
	};
	
	/**
     * 分页模糊参数
     */
    function getSelect(){
        var selectInfo = {
      		pageIndex : $("#content .get-more").attr("data-start"),
      		pageSize : $("#content .get-more").attr("data-num"),
			cid : $('.swiper-wrapper .loan-list.cur').attr('data-id').trim(),
			keywords:$('.search-cont input').val(),
			sort:$('.screening input[name=sort]').val(),
        };
        return selectInfo;
    }
    	/**
    * 分页动态渲染数据 下拉加载商品信息
    * type 1滚动 2切换 3搜索
    */
   	function render(type){
	  	if(type != 1){
	  		$("#content .get-more").attr('data-start',1);
	 	}
	  	var start = $("#content .get-more").attr("data-start");
	  	var num = $("#content .get-more").attr("data-num");
	    var selectInfo = getSelect();
		$.ajax({
	        type:'post',
	        url:'./index.php?c=store&a=getCateInfo',
	        data:selectInfo,//从1开始计数
	        beforeSend:function(xhr){
	            stop = false;
	          	$("#content .get-more").text("正在加载中");
	        },
	        dataType:"json",
	        success:function(result){
	            if(result.errorCode == 0){ 
	            	var html="";
	           		if(result.data.level == 1){
	           			var dataList = result.data.goodsList;              			
	           			for(var i = 0; i < dataList.length; i++){
		                	var obj = dataList[i];
		                	var id = obj.id;
		               		var name = obj.name;
		                    var img_url = obj.img_url;
		                    var price = obj.price;
		                    var ori_price = obj.ori_price;
		                    var time_length = obj.time_length;
		                    var apply = obj.apply;
		                    var sale_quantity = obj.sale_quantity;
		                  	html+='<div class="recommend-goods-area"><div class="goods">'
	                    		+'<a href="./index.php?c=store&a=goodsDetail&id='+id+'" class="goods-info" title="商品详情">'
	                    		+'<div class="goods-descript-info">'
	                    		+'<div class="name">'+name+'</div>'
	                    		+'<div class="quantity">已售 '+sale_quantity+'</div>'
	                            +'<div class="time_length">时长: '+time_length+'</div>'
	                            +'<div class="time_length">适用: '+apply+'</div>'
	                    		+'</div><div class="price-num">'
	                    		+'<div class="price"><strike class="ori_price">￥'+ori_price+'</strike><span class="real_price">￥'+price+'</span></div>'
	                    		+'<div class="quantity cart" style="cursor:pointer;margin-left:60%;" data-id="'+id+'"><i class="icon iconfont" style="font-size:24px;color:black;">&#xe646;</i></div>'
	                    		+'</div></a></div></div><div class="space2"></div>';
	                	}
	            		if(type == 1){//滚动加载
	            		   $("#content .recommend-goods-diplay-area").append(html);   	            		    
	            	    }else{//更换分类或搜索
	            		   $("#content .recommend-goods-diplay-area").html(html);
	            		}

		            	$("#content .get-more").attr("data-num",10);
	            	    if(dataList.length < num || result.data.pageInfo.total_page == 1){//判断数据是否加载完
		                	stop = false;
		                	$("#content .get-more").text("没有更多了");
		                	$("#content .get-more").attr("data-start",parseInt(start)+1);
		            	}else{	               
		               		get_more_top = $("#content .get-more").offset().top;
		               		$("#content .get-more").attr("data-start",parseInt(start)+1);
		               		$("#content .get-more").text("下拉加载更多");
		               		stop = true;
		             	}
              		}                                         	            
	            }else{
	            	$("#content .recommend-goods-diplay-area").html('<div class="no-cate">暂无数据</div>');
	            	$("#content .get-more").text("");
	            }
            },
            error:function(){
            	showDialog('#errorDialog',' ','请求失败，网络异常！');
            }
   		});
   	}
	
	init();
});