$(function(){
	//动态设置左边栏高度
	var winH = $(window).height();
	var barHeight = winH - 40 - 53 - 50;
	$('.left-bar').css('height',barHeight+'px');
	$('.main-cont').css('height',barHeight+'px');
	
	/**
	 * 初始化
	 */
	function init(){
		
		bindEvent();
	};
	
	/**
	 * 绑定事件
	 */
	function bindEvent(){
		//点击切换一级分类
		$('.left-bar .bar-item').bind('click',changeCate);
	};
	
	/**
	 * 切换分类
	 */
	function changeCate(){
		var _this = $(this);
		var index = _this.index();
		_this.addClass('cur').siblings().removeClass('cur');
		var sTop = index*51;//次元素相对于父级顶部的偏移量
//		var tH = 51*$('.bar-item').length;//左边栏总高度
//		var remainHeight = tH - sTop;
		$('.left-bar').animate({'scrollTop': sTop+'px'},500);
	}
	
	init();
});
