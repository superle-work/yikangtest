$(function(){
    var stop = true;
    var state = $("#content #state").val();//订单状态,无值则为所有
    var payType = $("#content #payType").val();//订单支付类型 0仅货到付款1仅微信支付2货到付款和微信支付
    var is_refund = $("#content #is_refund").val();//是否开启订单退款0不开启1开启
    
    /***
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
            spaceBetween : 20, //slide之间的距离
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
        
        render(1);
        bindEvent();
    }

    function bindEvent(){
        //用户变化屏幕方向时调用
        $(window).bind( 'orientationchange resize', function(e){
            width = $("#container").width();//计算当前窗口的宽度
            $("#container #myBottomNav").width(width);//将底部导航的宽度设置与移动设备相同
        });

        //订单状态分类导航click事件
        $("#myBottomNav .loan-list").click(function(){
            var _this = $(this);
            state = _this.attr("data-state");//订单状态：0待付款 1待发货 2已发货 3 已收货 4交易关闭 5交易成功
            _this.siblings().removeClass("cur");
            _this.addClass("cur");
            $("#content #state").val(state);
            render(0);
        });
        
        //滚动实现加载
        $(window).scroll(function(){
            if(stop == true){
                var scroll_top = $(window).height()+$(window).scrollTop();
                if(scroll_top > get_more_top){
                    //异步请求，渲染商品列表
                    render(1);
                }
            }
        });
    }
    
    //查看报告
    function orderReport(){
        var oid = $(this).attr("data-oid");
        location.href ="index.php?c=store&a=showReport&oid="+oid;
    }
    
    
    /**
     * 分页模糊参数
     */
    function getSelect(){
        var selectInfo = {
            state : $("#content #state").val(),
            start : $("#content .get-more").attr("data-start"),
            num : $("#content .get-more").attr("data-num")
        };
        return selectInfo;
    }
    /*
     * 搜索按钮
     **/
    $("#search").click(function(){
        render();
    });
    /**
     * 下拉加载数据
     */
    function render(type){
        if(type != 1){
            $("#content .get-more").attr('data-start',1);
        }
        // 查询条件
        keywords=$("#keywords").val();

        if(keywords==undefined){
            keywords='';    
        }

        var start = $("#content .get-more").attr("data-start");
        var num = $("#content .get-more").attr("data-num");
        var selectInfo = getSelect();
        selectInfo['keywords']=keywords;

        $.ajax({
            type:'post',
            url:'./index.php?c=store&a=pagingOrder',
            data:selectInfo,
            dataType:'json',
            beforeSend:function(){
                stop = false;
                $("#content .get-more").text("正在加载中");
            },
            success:function(result){
                var html ='';
                console.log(result)
                if(result.errorCode == 0){
                    console.log(result)
                    var orderList = result.data.orderList;
                    for(var i = 0; i < orderList.length; i++){
                        var order = orderList[i];
                        var myState = order.state;//订单状态
                        var is_comment = order.is_comment;
                        var pay_method = order.pay_method;
                        var points_money = order.points_money;
                        var express_code = order.express_code;
                        var express_company = order.express_company || '';//快递公司
                        var express_number = order.express_number || '';//快递单号
                        var goods_count = order.goods_count;
                        var min = order.min;
                        var balance_money = order.balance_money;                       
                        var add_time = order.add_time;
                        
                        var stateText = "";
                        var orderOper = "";
                        if(myState == '0'){
                            stateText = '<td class="stateText0">待付款</td>';
                            orderOper = '<tr><td colspan="3"><span class="right"><a href="javascript:;" data-oid="'+order.id+'" class="go-buy" id="go-buy">支付</a><a href="javascript:;" data-oid="'+order.id+'" id="userOrderDetail" class="userOrderDetail">删除订单</a></span></td><tr>';
                        }else if(myState == '5'){
                            stateText = '<td class="stateText5">已完成</td>';                            
                            orderOper = '<tr><td colspan="3"><span class="right"><a href="javascript:;" data-oid="'+order.id+'" class="btn btn-xs btn-default order-report">查看报告</a></span></td><tr>';
                        }
                        else if(myState == '1'){
                            stateText = '<td class="stateText1">待采样</td>';                            
                        }
                        else if(myState == '2'){
                            stateText = '<td class="stateText2">待送检</td>';                            
                        }
                        else if(myState == '3'){
                            stateText = '<td class="stateText3">送检中</td>';                            
                        }
                        else if(myState == '4'){
                            stateText = '<td class="stateText4">检测中</td>';                            
                        }
                        
                        //判断订单
                        if(myState=='0'){
                            html+="<div class='order-goods-list'><a href='./pay/request/balancePay.php?oid="+order.id+"'>";
                        }
                        else{
                            html+="<div class='order-goods-list'><a href='index.php?c=store&a=orderDetail&from=1&oid="+order.id+"'>";
                        }
                        
                        html+='<table>'
                                +'<thead>'
//                                  +'<tr>'
//                                      +'<td class="order-title" colspan="3"><span class="add-time">'+add_time+'</span>'+help_name+'<a class="orderDetail" href="./index.php?c=store&a=orderDetail&oid='+order.id+'">订单详情</a></td>'
//                                  +'</tr>
                                    +'<tr class="order-num">'
                                        +'<td colspan="2"><i class="icon iconfont">&#xe62e;</i>订单号：'+order.order_num+'</td>'
                                        +stateText
                                    +'</tr>'
                                +'</thead>'
                                +'<tbody>';
                                
                        var goodsList = order.goods_list;//商品集
                        for(var j = 0 ;j < goodsList.length; j++){
                            var goods = goodsList[j];
                            var goods_name = goods.goods_name;
                            var sample_vessel = goods.sample_vessel ? goods.sample_vessel :'--';
                            html+="<tr class='order-goods'>";
                                html+='<td class="col-1"></td>';
                                html+='<td class="col-2"><div class="name"><a>'+goods_name+'</a></div><div class="price" style="display:flex;flex-direction:row;justify-content:space-between;"><span>￥'+goods.price+'</span><span style="color:black;font-size:13px;">采样容器:'+sample_vessel+'</span></div></td>';
                                html+='<td class="col-3"><div class="quantity">x<span>'+goods.count+'</span></div></td>';
                            html+="</tr>";
                        }
                        
                        html+='</tbody>'
                             +'<tfoot>'
                                +'<tr>'
                                    +'<td class="total-goods" colspan="3">'
                                    +'<span class="total-money col-xs-4">病人姓名&nbsp;:&nbsp;'+order.member_info.name+'</span>'
                                    +'<span class="total-money col-xs-8">下单时间&nbsp;:&nbsp;'+order.add_time+'</span>'
                                    +'<span class="total-money col-xs-12">合计&nbsp;:&nbsp;￥'+order.total_price+'</span>'
                                    
                                    +'</td>'
                                +'</tr>'
                                +orderOper
                             +'<tfoot>'
                             +'</table>'
                        html+='</a></div><div class="space2"></div>';
                    }
                    
                    if(type == 1){//滚动加载
                        $("#content .order-list").append(html);
                    }else{//点击切换状态
                       $("#content .order-list").html(html);
                    }

                    if(orderList.length < num || result.data.pageInfo.total_page == 1){//判断数据是否加载完
                       stop = false;
                       $("#content .get-more").text("没有更多了");
                    }else{
                       get_more_top = $("#content .get-more").offset().top;
                       $("#content .get-more").attr("data-start",parseInt(start)+1);
                       $("#content .get-more").text("下拉加载更多");
                       stop = true;
                    }
                }
            },
            error:function(){
                showDialog('#errorDialog',' ','请求失败，网络异常！');
            }
        });
    }
    
    $("#content").on('click','.order-report',orderReport);

    $("#content").on('click','.go-buy',function(){//查看待付款订单详情信息--直接付款
        var id = $(this).attr("data-oid");
        window.location.href = "./pay/request/balancePay.php?oid="+id;
        
        /*
        if(payType == 0){
            //跳转到仅货到付款的页面
            window.location.href = "./index.php?c=store&a=daofuOrderPage&id="+id;
        }else if(payType == 1 || payType == 2){
            //跳转到支持货到付款加微信支付的页面
            window.location.href = "./pay/request/code.php?&oid="+id+"&module=storePay";
        }
        */
    });
      $("#content").on('click','.userOrderDetail',function(){//查看待付款订单详情信息--直接付款
        var id = $(this).attr("data-oid");
        $.ajax({
            url:'index.php?c=store&a=deleteUserOrder',//请求地址，html页面传过来的
                type: "post",
                data: {id: id
                      },
                    success:function(res){
    
                            showDialog('#errorDialog', '删除成功','加载成功~' , '&#xe683;', 1500,function(){
                                window.location.reload();
                            });
                        

                    }
        });
    });
    
    init();
});