/***
 * 商品详情页面
 */
$(function() {
	var firstImage = true;
	var stop = true; //控制是否出发滚动加载
	var idList = [];
	var width = $(".container-fluid").width(); //计算当前窗口的宽度
	$(".goodsDetail-info-nav").width(width); //将的宽度设置与移动设备相同
	
	var gid = $(".bottom-nav .buy-now").attr("data-id"); //商品主键

	var screenWidth = $(window).width(); //屏幕宽度
	var width = $("#container").width(); //获取当前主体宽度
	var accessCart = $(".access-cart");
	var right = (screenWidth - width) / 2; //居右距离
	//accessCart.width(width);
	accessCart.css("right", right + 30);

	/**
	 * 初始化
	 */
	function init() {
		//幻灯片Swiper插件
		autoSlideHeight(firstImage);
		bindEvent();
	}


/**
 * 幻灯片自适应高度函数
 */
function autoSlideHeight() {
	window.onload = function() {
		initSlide();
	}
}

/**
 * 初始化幻灯片函数
 */
function initSlide() {
	var mySwiper = new Swiper('.swiper-container', {
		//direction: 'vertical',
		loop: true,
		speed: 400,
		autoplay: 3000,
		effect: "fade", //fade,cube,coverflow
		observer: true,
		// 如果需要分页器
		pagination: '.swiper-pagination',
		paginationClickable: true,
		onSlideChangeStart: function(swiper) {
			var index = swiper.activeIndex + 1; //切换结束时，告诉我现在是第几个slide
			var A_H = $(".swiper-slide:nth-child(" + index + ") .slide-image").height(); //当前的image
			$(".swiper-container").css("height", A_H + "px");
		}

	});
}

/**
 * 绑定事件
 */
function bindEvent() {
	//用户变化屏幕方向时、尺寸时调用
	$(window).bind('orientationchange resize', function(e) {
		screenWidth = $(window).width(); //屏幕宽度
		width = $("#container").width(); //计算当前窗口的宽度
		var right = (screenWidth - width) / 2; //居右距离
		accessCart.css("right", right + 30); //将底部导航的宽度设置与移动设备相同
	});
	
	/*
	//购物车弹出框显示时调整 弹框位置及大小
	$("#addCartModal").on('show.bs.modal', function() {
		$(this).addClass("modal-show"); //标识当前弹出框显示
		var screenWidth = $(window).width(); //屏幕宽度
		var width = $("#container").width(); //获取当前主体宽度
		var cartModal = $(this).find(".modal-dialog");
		var left = (screenWidth - width) / 2; //居左距离
		cartModal.width(width);
		cartModal.css("left", left);
		
		
		//发送异步请求
		if(idList == '' && stan == 1) {
			//异步发送请求，获取选定套餐的记录，同步调整价格(提示用户可选的属性组合)
			var gid = $("#container .bottom-nav .buy-now").attr("data-id");
			$.ajax({
				type: 'post',
				url: "./index.php?c=store&a=getPropertyData",
				data: {
					"gid": gid
				},
				dataType: 'json',
				beforeSend: function(xhr) {
					showDialog("#loadingDialog"); //显示“加载中。。。”
				},
				complete: function() {
					hideDialog("#loadingDialog"); //隐藏“加载中。。。”
				},
				success: function(json) {
					if(json.errorCode == 0) {
						idList = json.data;
					} else {
						//查找失败
						showDialog("#alertDialog", '多规格数据失败', '啊哦，未接受到多规格数据！', '', 1500);
					}
				},
				error: function() {
					showDialog("#errorDialog", '', '啊哦，请求失败！', '', 1500);
				}
			});
		}
		
	});
	*/
	
	/*
	//弹出框隐藏事件
	$("#addCartModal").on('hidden.bs.modal', function() {
		$(this).removeClass("modal-show"); //标识当前弹出框隐藏
	});
	*/

	//点击购物车
	$(".bottom-nav .cartList").click(function() {
		window.location.href = "./index.php?c=store&a=cartList&type=1";
	});
	
	//点击添加或删除收藏
	$('.bottom-nav .favorite').click(function() {
		var gid = $(this).attr('data-id');
		if($(this).children().hasClass('selected')) {
			deleteFavorite(gid);
		} else {
			addFavorite(gid);
		}
	});
	
	//点击分享
	$('.bottom-nav .share').click(function() {
		$("#loadingDialog").html("");
        $("#loadingDialog").addClass("bg");
        // $("#loadingDialog").modal({backdrop:'static'});
        $("#loadingDialog").modal("show");
	});
	
	
	/**
	 * 立即购买,跳到提交订单页面，确认提交
	 
	$("#addCartModal .buy-now").click(function() {
		if(!CAN_BUY) {
			if($(this).parents(".modal-footer").length > 0) {
				$("#addCartModal .property-group-area").css("border", "1px solid #c00");
			}
			showCartModal(); //若不满足条件，引导购买
			return false;
		} else {
			var count = parseInt($("#addCartModal .quantity-area .quantity-value").val()); //商品数量
			
			//生成待付款订单,跳转到待付款订单页面，进行支付
			var gid = $(this).attr("data-id");

			//跳到到提交订单界面，确认提交将生成订单--》确认支付
			window.location.href = "./index.php?c=store&a=confirmOrder&gids=" + gid + "&counts=" + count;
		}
	});
	*/
	
	/**
	 * 弹出框，引导用户将商品加入购物车
	 */
	$(".bottom-nav .add-cart").click(function() {
		var gid=$(this).attr("data-id");
		var account=$(this).attr("data-account");
		var type=$(this).attr("data-type");
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
							window.location.reload();
						});
					}
					else{
						showDialog('#errorDialog', '添加失败', res.errorInfo, '&#xe6a9;', 2000);
					}
				}
			})
		}
	});
	
	/*
	 * 立即购买，跳转到确认订单页面
	 * */
	$(".bottom-nav .buy-now").click(function(){
		var cgids='';
		var gids=$(this).attr("data-id");
		var counts=1;
		var uid=$(this).attr("data-user");
		window.location.href = "./pay/request/balancePay.php?cgids="+cgids+"&gids="+gids+"&counts="+counts+"&uid="+uid;
	})
	
	/**
	 * 将商品添加至购物车
	 
	$("#addCartModal .add-cart").click(function() {
		if(!CAN_BUY) {
			if($("#addCartModal").hasClass("modal-show")) { //购物车弹框已出现时，点击“加入购物车”提示用户
				$("#addCartModal .property-group-area").css("border", "1px solid #c00");
			}
		} else { //加入购物车
			$("#addCartModal .property-group-area").css("border", "none");
			var quantity = $("#addCartModal .quantity-area .quantity-value").val(); //商品数量
			var cartList = $('.bottom-nav .cartList-num .number').html(); //购物车数量
			
			var gid = $(this).attr("data-id"); //商品主键id
			if(gid){
				//加入购物车
				$.ajax({
					url: "./index.php?c=store&a=addCartGoods",
					type: "post",
					data: {
						"gid": gid,
						"count": quantity
					},
					dataType: "json",
					beforeSend: function(xhr) {
						//显示“加载中。。。”
						//showDialog("#loadingDialog");
					},
					complete: function() {
						//隐藏“加载中。。。”
						//hideDialog("#loadingDialog");
					},
					success: function(json, statusText) {
						if(json.errorCode == 0) {
							//操作成功,跳转到购物车页面
							//window.location.href = "./index.php?c=store&a=cartList";
							//	                                $('.bottom-nav .cartList-num .number').html(cartList_total)
							$("#addCartModal").modal('hide');
							$(".access-cart").show();
						} else if(json.errorCode == 2) {
							cartList++;
							$('.bottom-nav .cartList-num .number').html(cartList);
							$("#addCartModal").modal('hide');
							$(".access-cart").show();
						} else {
							//操作失败
							showDialog('#errorDialog', '', json.errorInfo, '&#xe6a9;', 1500);
						}
					},
					error: function() {
						//请求出错
						showDialog('#errorDialog', '', '请求失败，网络异常！', '&#xe6a9;', 1500);
					}
				});
			}
		}
	});
	*/
	
	
	/**数量输入框键盘事件--输入非数字无效**/
	$(".quantity-area .quantity-value").keyup(function() {
		var quantity = $(this).val();
		var ereg = /^[0-9][0-9]{0,4}$/;
		if(quantity != "" && ereg.test(quantity)) {
			//非空且合法
		} else if(quantity != "" && !ereg.test(quantity)) {
			//非空且不合法
			$(this).val(1);
		}
	}).blur(function() {
		//失去焦点事件
		var quantity = $(this).val();
		if(quantity == "" || quantity == 0) {
			$(this).val(1);
		}
	});
	/**商品数量添加事件**/
	$('.quantity-area .quantity-plus').click(function() {
		var qElem = $('.quantity-area .quantity-value');
		var value = parseInt(qElem.val());
		var now = value + 1;
		qElem.val(now);
	});
	/**商品数量减少事件**/
	$('.quantity-area .quantity-minus').click(function() {
		var qElem = $('.quantity-area .quantity-value');
		var value = parseInt(qElem.val());
		var now = value - 1;
		if(now < 1) {
			now = 1;
		}
		qElem.val(now);
	});
}

/**
 * 显示弹出框
 
function showCartModal() {
	if(!$("#"+"tModal").hasClass("modal-show")) {
		//当前未显示时，将其显示
		$("#addCartModal .quantity-operation-area .quantity-value").val(1);
		$("#addCartModal").modal('show');
	}
}
*/

//添加收藏
function addFavorite(gid) {
	$.ajax({
		url: "./index.php?c=store&a=addFavorite",
		type: "post",
		data: {
			"gid": gid
		},
		dataType: "json",
		success: function(json, statusText) {
			if(json.errorCode == 0) {
				$('.bottom-nav .favorite .icon').addClass('selected').html("&#xe6b0;");
				$('.bottom-nav .favorite .my-favorite').html("已收藏");
			} else {
				showDialog("#alertDialog", "抱歉！操作异常", json.errorInfo, '&#xe6a9;', 2000);
			}
		},
		error: function() {
			showDialog("#alertDialog", "抱歉！系统繁忙", '您的操作失败咯', '&#xe6a9;', 2000);
		}
	});
}

//取消收藏
function deleteFavorite(gid) {
	$.ajax({
		url: "./index.php?c=store&a=deleteFavorite",
		type: "post",
		data: {
			"gid": gid
		},
		dataType: "json",
		success: function(json, statusText) {
			if(json.errorCode == 0) {
				$('.bottom-nav .favorite .icon').removeClass('selected').html("&#xe61b;");
				$('.bottom-nav .favorite .my-favorite').html("收藏");
			} else {
				showDialog("#alertDialog", "抱歉！操作异常", json.errorInfo, '&#xe6a9;', 2000);
			}
		},
		error: function() {
			showDialog("#alertDialog", "抱歉！系统繁忙", '您的操作失败咯', '&#xe6a9;', 2000);
		}
	});
}

init();
});