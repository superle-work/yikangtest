/***
 * 商品详情页面
 */
$(function() {
	var firstImage = true;
	var stop = true; //控制是否出发滚动加载
	var idList = [];
	var width = $(".container-fluid").width(); //计算当前窗口的宽度
	$(".goodsDetail-info-nav").width(width); //将的宽度设置与移动设备相同


	var total_count = 1; //分页总记录数
	var total = 1; //分页总页面数
	var currentPage = 1; //当前页
	var pageSize = pageOption.pageSize; //每页显示的记录数
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

	init();
});