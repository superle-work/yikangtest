/**
 * 微店
 * @since 2015-11-09
 * @author jjhu
 */

$(function(){
    var width = $("#container").width();//计算当前窗口的宽度
    $("#container .wechat-dialog").width(width-20);
    /**
     * 初始化
     */
    function init(){
        //幻灯片Swiper插件
        var mySwiper = new Swiper ('.swiper-container', {
            //direction: 'vertical',
            loop: true,
            speed:400,
            autoplay:3000,
            effect:"coverflow",//fade,cube,coverflow
            observer:true,
            // 如果需要分页器
            pagination: '.swiper-pagination',
            paginationClickable:true

            // 如果需要前进后退按钮
            //nextButton: '.swiper-button-next',
            //prevButton: '.swiper-button-prev',

            // 如果需要滚动条
            //scrollbar: '.swiper-scrollbar',
        });
        bindEvent();
    }

    /**
     * 绑定事件
     */
    function bindEvent(){
        //微信联系
        $("#container .top-quick-road .wechat a").click(function(){
            $("#container .wechat-dialog").show();
        });

        $("#container .wechat-dialog .cancel").click(function(){
            $("#container .wechat-dialog").hide();
        });
        /***
         * 商品搜索
         */
        $(".search-goods-area .search-goods-btn").click(function(){
            var txt = $.trim($(".search-goods-area .search-text").val());//查询关键字
            var type = $(".search-goods-area .search-text").attr('search-type');
            var did = $(".search-goods-area .search-text").attr("data-did");
            var href = "./index.php?c="+type+"&a=goodsSearch&keywords="+txt;
            if(did !=null && did !=""){
                href += "&did="+did;
            }
            window.location.href = href;
        });
        //enter事件
        $(".search-goods-area .search-text").keydown(function(event){
            event = event ? event:window.event;
            if(event.keyCode == 13){
                $(".search-goods-area .search-goods-btn").click();
                return;
            }
        });
        
        //选择搜索条件
        $('.search-goods-area .search-goods-select .search-type').click(function(){
        	if(!$(this).hasClass('active')){
        		$(this).addClass('active');
        		$(this).parent().find('ul').each(function(){
        			$(this).css('display', 'block');
        		});
        		$(this).css('color', '#ea6915');
        	}else{
        		$(this).removeClass('active');
        		$(this).parent().find('ul').each(function(){
        			$(this).css('display', 'none');
        		});
        		$(this).css('color', '#757575');
        	}
        })
        
        $('.search-goods-area .search-goods-select .option').click(function(){
        	var _this = $('.search-goods-area .search-goods-select .search-type');
        	if($(this).hasClass('mall-goods')){
        		_this.find('span').text('特产');
        		$(".search-goods-area .search-text").attr('search-type', 'mall');
        		$(".search-goods-area .search-text").attr('placeholder', '请输入想要搜索的特产内容');
        	}else if($(this).hasClass('trip-goods')){
        		_this.find('span').text('旅行');
        		$(".search-goods-area .search-text").attr('search-type', 'trip');
        		$(".search-goods-area .search-text").attr('placeholder', '请输入想要搜索的旅游景点');
        	}
        	_this.removeClass('active');
        	_this.parent().find('ul').each(function(){
        		$(this).css('display', 'none');
    		});
        	_this.css('color', '#757575');
        });

        //设置默认店铺
        $("#container .top-quick-road .default-shop a").click(function(){
            var _this = $(this);
            var current_shop_id = _this.attr("data-current_shop_id");//当前默认店铺值 用户主键值
            var default_shop_id = "0";
            if(_this.hasClass("same-shop")){
                //重置默认店铺
                //确认取消当前店铺为默认店铺
                if(!confirm("确定取消默认店铺？")){
                    return false;
                }
                //重置默认店铺
            }else{
                //设置当前店铺为默认店铺成功
                default_shop_id = current_shop_id;
            }

            $.ajax(
                {
                    type:"post",
                    url:"./index.php?c=distributor&a=setDefaultShop",
                    data:{"default_shop_id":default_shop_id},
                    dataType:"json",
                    success:function(json,statusText){
                        if(json.errorCode == 0){
                            if(default_shop_id > 0){
                                alert("成功设置当前店铺为默认店铺！");
                            }else{
                                alert("成功取消默认店铺！");
                            }
                            window.location.reload();
                        }else{
                            alert("操作失败，发生异常！");
                        }
                    },
                    error:function(){
                        alert("请求失败，网络异常！");
                    }
                }
            );
        });
    }
    init();
});