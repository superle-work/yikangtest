/**
 * 订单中心
 * @since 2015-11-13
 * @author jjhu
 */
$(function(){

    var total_count = 1;//分页总记录数
    var total = 1;//分页总页面数
    var currentPage = 1;//当前页
    var pageSize = pageOption.pageSize;//每页显示的记录数
    var state = $("#myBottomNav a.current").attr("data-state");//订单状态,无值则为所有
    var distributor_id = $("#myBottomNav .distributor-id").val();//获取该分销商id
    function init(){
        var width = $("#container").width();//计算当前窗口的宽度
        $("#container #myBottomNav").width(width);//将底部导航的宽度设置与移动设备相同
        $("#container #myBottomNav").css("display","block");
        myPagination();
        bindEvent();
    }
    function bindEvent(){

        //用户变化屏幕方向时调用
        $(window).bind( 'orientationchange resize', function(e){
            width = $("#container").width();//计算当前窗口的宽度
            $("#container #myBottomNav").width(width);//将底部导航的宽度设置与移动设备相同
        });

        //底部导航click事件
        $("#myBottomNav .nav-item").click(function(){
            var _this = $(this);
            state = _this.attr("data-state");//订单状态：0待付款 1待发货 2已发货 3 已收货 4交易关闭 5交易成功
            _this.parent().find(".nav-item").removeClass("current");
            _this.addClass("current");
            myPagination();

        });
    }
    /**
     * 订单分页显示方法
     */
    function myPagination(){
        render(true,1,pageSize);
        //调用公共分页方法
        pagination("#page-selection",{total:total},render);

    }
    /**
     * 分页动态渲染数据
     * @param async ajax请求是否异步
     * @param pageIndex 当前显示页
     * @param pageSize 每页显示记录数
     */
    function render(async,pageIndex,pageSize){
        $.ajax({
            async:async,
            type:'post',
            url:'./index.php?c=distributor&a=pagingDistributorTripOrder',
            data:{"pageIndex":pageIndex,"pageSize":pageSize,"state":state,"distributor_id":distributor_id},//从1开始计数
            dataType:'json',
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                    total_count = result.data.pageInfo.total_count;
                    total = result.data.pageInfo.total_page;
                    $("#page-selection").bootpag({total:total});//重新计算总页数

                    currentPage = result.data.pageInfo.current_page;
                    var orderList = result.data.dataList;
                    for(var i = 0; i < orderList.length; i++){
                        var order = orderList[i];
                        var myState = order.state;
                        var add_time = order.add_time;
                        var order_num = order.order_num;
                        var total_price = order.total_price;
                        var stateText = "";
                        if(myState == '0'){
                            stateText = "待付款";
                        }else if(myState == '1'){
                            stateText = "已付款";
                        }else if(myState == '2'){
                            stateText = "已确认";
                        }else if(myState == '3'){
                            stateText = "申请退款";
                        }else if(myState == '4'){
                            stateText = "退款成功";
                        }else if(myState == '5'){
                            stateText = "已取消";
                        }else if(myState == '6'){
                            stateText = "交易完成";
                        }
                        var pay_method = order.pay_method ? order.pay_method : 0;
                        var pay_method_text = "";
                        if(pay_method == 1){
                            pay_method_text = "<span class='paymethod-text'>微信支付</span>";
                        }else if(pay_method == 2){
                            pay_method_text = "<span class='paymethod-text'>支付宝支付</span>";
                        }else if(pay_method == 3){
                            pay_method_text = "<span class='paymethod-text'>货到付款</span>";
                        }else{

                        }
                        
                        html+='<a class="order" href="./index.php?c=trip&a=orderDetail&id='+order.id+'">'
                        		+'<div class="add-time">'+add_time+'</div>'
                        			+'<div class="order-info clearfix">'
                        				+'<span class="order-num">订单号：'+order_num+'</span>'
                        				+'(<span class="state">'+stateText+'</span>)'
                        				+'<span class="total-price-info">总价：<i class="icon iconfont">&#xe604;</i><span class="total-price">'+total_price+'</span></span>'
                					+'</div>'
                				+'</a>'
                    }
                    if(orderList.length == 0){
                        html = '<div class="order"><p class="bg-danger no-data">查询结果为空。</p></div>';
                        $("#content .order-list").html(html);
                    }else{
                        $("#content .order-list").html(html);
                    }

                }else{
                    $("#content .order-list").html('<p class="bg-danger no-data">'+json.errorInfo+'</p>');
                }

            },
            error:function(){
                $("#content .order-list").html('<p class="bg-danger no-data">请求失败，网络异常!</p>');
            }
        });
    }

    init();
});