/*待付款订单*/
$(function(){
	
	 /**
     * 分页初始条件
     */
    var total_count = 1;//分页总记录数
    var total = 1;//分页总页面数
    var currentPage = 1;//当前页
    var pageSize = pageOption.pageSize;//每页显示的记录数

    /**
     * 初始化
     */
    function init(){
    	myPagination();
        bindEvent();
    }
    
    function bindEvent(){
    	
    	//导航弹框
    	$(".screen-nav ul li").click(function(){
    		$(this).siblings().removeClass("active");
    		$(this).addClass("active");
    		$(".screen-nav .display-nav-area").show();
    		var num=$(this).attr("data");
    		$(".screen-nav .display-nav-"+num).siblings().hide();
    		$(".screen-nav .display-nav-"+num).show();
    		$("body").css({"position":"fixed"});
    		//选择条件
    		$(".display-nav-area .display-nav .item").click(function(){
	    		if($(this).hasClass('active')){
	        		$(".screen-nav .display-nav-"+num).hide();
	        	}else{
	        		$(".display-nav-area .display-nav .item").removeClass('active');
	        		$(this).addClass('active');
	        		$(".order-list").html('');
	        		myPagination();
	        	}
	    		$("body").css({"position":"static"});
	    		$(".screen-nav .display-nav-area").hide();
    		});
    	});
    	
    	//阻止冒泡
    	$(".screen-nav .display-nav").click(function(event){
    		return false;
    	})
    	//点击隐藏遮罩层
    	$(".screen-nav").on("click",".display-nav-area",function(){
    		$(this).hide();
    	});
    	
    	
        //enter键盘事件
        $("#container #header .header-wrap input").keydown(function(event){
            event = event ? event: window.event;
            if(event.keyCode == 13){
            	$("#content .order-goods-list").html('');
        		myPagination();
            }
        });
    	
        
        $(window).scroll(function(){
	    	var scrollTop = $(window).scrollTop();
	    	//判断是否显示返回顶部
	    	if(scrollTop > 150){
    			$("#content .back-header").show();
    		}else{
    			$("#content .back-header").hide();
    		}
	    	
	    	//下滑翻页
	    	var doc_height = $(document).height() 
    		var win_height = $(window).height();
    		if($(window).scrollTop() >= doc_height - win_height){
    			$(".pagination .next a").click();
			};
		});
        
        /**
         * 返回顶部
         */
        $("#content .back-header").click(function(){
            $('html,body').animate({'scrollTop':0},500); //返回顶部动画 数值越小时间越短
        });
    }
    
    /**
     * 获取模糊参数
     */
    function getSelectInfo(){

        var selectInfo = {
        	input_screen : $.trim($("#container #header .header-wrap input").val()),//订单编号
            state : $('.display-nav-area .display-nav .item.active').attr('data-state'),//订单状态
            pay_method : $('.display-nav-area .display-nav .item.active').attr('data-method'),//订单状态
        };
        return selectInfo;
    }
    
    /**
     * 分页显示方法
     */
    function myPagination(){
        render(true,1,pageSize);
        //调用公共分页方法
        pagination("#page-selection",{pageSize:pageSize,total:total},render);

    }
    /**
     * 分页动态渲染数据
     * @param async ajax请求是否异步
     * @param pageIndex 当前显示页
     * @param pageSize 每页显示记录数
     */
    function render(async,pageIndex,pageSize){
        var selectInfo = getSelectInfo();
        selectInfo.pageIndex = pageIndex;
        selectInfo.pageSize = pageSize;
        $.ajax({
            async:async,
            type:'post',
            url:'./index.php?c=store&a=pagingAdminOrder',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(result){
                var html ='';
                var html2 ='';
                if(result.errorCode == 0){
                    total = result.data.pageInfo.total_page;
                    total_count = result.data.pageInfo.total_count;
                    $("#page-selection").bootpag({total:total,total_count:total_count});//重新计算总页数,总记录数

                    currentPage = result.data.pageInfo.current_page;
                    var myList = result.data.dataList;

                    //html+='<tr><th class="th1">昵称</th><th class="th2">订单编号</th><th class="th3">金额</th><th class="th4">创建日期</th></tr>';
                    var colspan = $(html).find("th").length;
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var ordernum = obj.order_num;
                        if(ordernum.length > 16){
                        	ordernum = ".." + ordernum.substr(ordernum.length-10)
                        }
                        var nick_name = obj.nick_name;
                        var head_img_url= obj.head_img_url;
                        var totalprice = obj.total_price;
                        totalprice = totalprice.replace(".00", "");
                        var state = obj.state;
                        var oid = obj.id;
                        //var goodsname = obj.goods_info.name;
                        var createtime = obj.add_time;
                        var createdate = new Date(createtime.substring(0, 10).replace("-", "/").replace("-", "/")+' 23:59:59');
                    	var now = new Date();
                    	if(createdate >= now){
                    		createtime = createtime.substring(11, 19);
                    	}else{
                    		createtime = createtime.substring(0, 10);
                    	}

                        var addressInfo = obj.address_text ? obj.address_text:"--";
                        var paymethod = obj.pay_method;
                        var paymethod_text = "";
                        if(paymethod == null || paymethod == "" || paymethod == 0){
                            paymethod_text = "--";
                        }else if(paymethod == 1){
                            paymethod_text = "微信支付";
                        }else if(paymethod == 2){
                            paymethod_text = "支付宝支付";
                        }else if(paymethod == 3){
                            paymethod_text = "货到付款";
                        }

                        var operation = orderOperatoin(state,oid);
                        state = setState(obj.state);
                        var message = obj.message ? obj.message : "--";

						html+='<div class="list">'
									+'<table class="order-simple-list">'
										+'<thead>'
											+'<tr>'
												+'<td class="col-1" pay_method="'+paymethod+'">订单编号：</td>'
												+'<td class="col-2"><span class="limit-text">'+ordernum+'</span></td>'
												+'<td class="col-3"><div class="status status-0 order-state">'+state+'</div></td>'
											+'</tr>'
										+'</thead>'
										+'<tbody>'
											+'<tr>'
												+'<td class="col-1"><a href="./index.php?c=store&a=orderDetail&oid='+oid+'"><img src="'+head_img_url+'"/></a></td>'
												+'<td class="col-2">'
													+'<a href="./index.php?c=store&a=orderDetail&oid='+oid+'">'
														+'<p class="nick-name">'+nick_name+'</p>'
														+'<p class="add_time"> <i class="icon iconfont">&#xe71d;</i>创建时间：'+createtime+'</p>'
														+'<p class="price"><i class="icon iconfont">&#xe71b;</i>订单金额：<i class="icon iconfont"></i>'+totalprice+'</p>'
													+'</a>'
												+'</td>'
												+'<td class="col-3"><a href="./index.php?c=store&a=orderDetail&oid='+oid+'"><i class="icon iconfont">&#xe70e;</i></a></td>'
											+'<tr>'
										+'</tbody>'
										+'<tfoot>'
											+'<tr>'
												+'<td class="col-3" colspan="3">'
													+''+operation+''
												+'</td>'
											+'</tr>'
										+'</tfoot>'
									+"</table>"	
								+"</div>"			
										
                    }

                    if(myList.length == 0){
                        html += '<tr><td colspan="'+colspan+'"><p class="text-danger">暂无数据。</p></td></tr>';
                        $(".order-list").html(html);
                    }else{
                        $(".order-list").html(html);
                        
                        //关闭订单
                        $(".close-order").click(function(){
                            var oid = $(this).attr('oid');
                            showConfirmDialog("#normalConfirmDialog",'确认关闭','是否关闭订单','','',function(){
                            	setOrderState(oid,4);	
                            });
                        });
                        //发货
                        $(".send-goods").click(function(){
                            var oid = $(this).attr('oid');
                            setOrderState(oid,2);
                        });
                        $("[data-toggle='popover']").popover();
                        //确认收货
                        $(".accept-goods").click(function(){
                            var oid = $(this).attr('oid');
                            setOrderState(oid,3);
                        });

                        //完成交易
                        $(".complete-order").click(function(){
                            var oid = $(this).attr('oid');
                            setOrderState(oid,5);
                        });
                        
                      //退款
                        $(".refund").click(function(){
                            var oid = $(this).attr('oid');
                            setOrderState(oid,7);
                        });

                        //修改订单
                        $(".modify-order").click(function(){
                        	var oid = $(this).attr("oid");
                        	window.location.href = "./index.php?c=store&a=orderModify&oid="+oid;
                        });
                        //删除订单
                        $(".delete-order").click(deleteOrder);
                    }

                }else{
                    responseTip(result.errorCode,result.errorInfo,1500);
                }

            },
            error:errorResponse
        });
    }
    
    //关闭订单
    function closeOrder(){
        var id = $(this).attr("oid");
        var state = "4";//交易关闭
        setOrderState(id,state);
    };
    //确认收货
    function confirmReceipt(){
        var id = $(this).attr("oid");
        var state = "3";//确认收货
        setOrderState(id,state);
    }

    //交易完成
    function successOrder(){
        var id = $(this).attr("oid");
        var state = "5";//交易完成
        setOrderState(id,state);
    }
    //交易完成
    function applyOrder(){
    	var id = $(this).attr("oid");
    	var state = "6";//交易完成
    	setOrderState(id,state);
    }

  //删除订单
    function deleteOrder(){
    	var id = $(this).attr("oid");
		showConfirmDialog("#dangerConfirmDialog",'确认删除','是否删除订单','','',function(){
			 $.ajax({
		            url:"./index.php?c=store&a=deleteOrder",
		            type:"post",
		            data:{"id":id},
		            dataType:"json",
		            beforeSend:function(xhr){
		               //显示“加载中。。。”
		            	showDialog("#loadingDialog");
		            },
		            complete:function(){
		               //隐藏“加载中。。。”
		            	hideDialog("#loadingDialog");
		            },
		            success:function(json,statusText){
		                if(json.errorCode == 0){
		                    showDialog("#successDialog",'恭喜您','操作成功','',1500);
		                    if(currentPage !=1 && total_count % pageSize == 1){//非首页且末页记录数为1时
		                        currentPage = currentPage - 1;
		                    }
		                    render(true,currentPage,pageSize);
			                $("#shadow").hide();
		                }else{
		                	showDialog('#errorDialog','',json.errorInfo)
		                }
		            },
		            error:function(){
		            	showDialog("#errorDialog",'','请求失败，网络异常！','',1500);
		            }
		        });
		});
    }
    
    //设置订单状态 3:确认收货 4:交易关闭
    function setOrderState(id,state){
        $.ajax({
            url:"./index.php?c=store&a=setOrderStateCenter",
            type:"post",
            data:{"oid":id,"state":state},
            dataType:"json",
            beforeSend:function(xhr){
               //显示“加载中。。。”
               showDialog('#loadingDialog');
            },
            complete:function(){
               //隐藏“加载中。。。”
               hideDialog('#loadingDialog');
            },
            success:function(json,statusText){
                if(json.errorCode == 0){
                    render(true,currentPage,pageSize);
                    $("#shadow").hide();
                    showDialog("#successDialog",'恭喜您','操作成功','','',function(){
                    	 $(".order-detail-list .order-detail-info").each(function(){
                     		if($(this).attr('oid') == id){
                     			var active = $(this);
                     			active.addClass('active');
                     			$("#shadow").show();
                     		}
                     	});
                    });
                }else{
                	showDialog("#errorDialog",'',json.errorInfo,'',1500);
                }
            },
            error:function(){
            	showDialog("#errorDialog",'','请求失败，网络异常！','',1500);
            }
        });
    }

    /**
     * 设置订单的中文状态
     * //0待付款  1待发货 2已发货 3已收货 4交易关闭 5交易完成
     */
    function setState(state){
        var html = "";
        switch (state)
        {
            case '0':
                html ="<span class='text-info'>待付款</span>";
                break;
            case '1':
                html ="<span style='color:#EE0000'>待发货</span>";
                break;
            case '2':
                html ="<span class='text-primary'>已发货</span>";
                break;
            case '3':
                html ="<span class='text-primary'>已收货</span>";
                break;
            case '4':
                html ="<span style='color:gray'>交易关闭</span>";
                break;
            case '5':
                html ="<span class='text-success'>交易完成</span>";
                break;
            case '6':
	            html ="<span style='color:orange'>申请退款</span>";
	            break;
	        case '7':
	            html ="<span style='color:green'>已退款</span>";
	            break;
            //默认状态是已删除  ——安全性要求
            default :html="--";

        }

        return html;
    }

    /**
     * 获取订单操作
     * @param state 订单状态
     * @param oid 订单id
     */
    function orderOperatoin(state,oid){
        var html = "";
        switch (state)
        {
            case '0'://待付款
            	html +="<a  href='javascript:;' oid ='"+ oid+"' class='btn btn-primary btn-xs modify-order'title='订单修改'>订单修改</a>";
                html +="<a  href='javascript:;' oid ='"+ oid+"' class='btn btn-danger btn-xs close-order'title='关闭订单'>关闭</a>";
                break;
            case '1'://待发货
            	html +="<a  href='javascript:;' oid ='"+ oid+"' class='btn btn-primary btn-xs modify-order'title='设置'>设置</a>";
            	html +="<a  href='javascript:;'  oid ='"+oid+"'  class='btn btn-primary btn-xs send-goods'>发货</a>";
                html +="<a  href='javascript:;'  oid ='"+oid+"'  class='btn btn-danger btn-xs close-order title='关闭订单'>关闭</a>";
                break;
            case '2'://已发货
                html+="<a  href='javascript:;'  oid ='"+oid+"' class='btn btn-success btn-xs accept-goods' title='确认收货'>确认</a>";
                html+="<a  href='javascript:;'  oid ='"+oid+"' class='btn btn-danger btn-xs close-order' title='关闭订单'>关闭</a>";
                break;
            case '3'://已收货
                html+="<a  href='javascript:;'  oid ='"+oid+ "' class='btn btn-success btn-xs complete-order' title='交易完成'>完成</a>";
                html+="<a  href='javascript:;'  oid ='"+oid+"'  class='btn btn-danger btn-xs close-order' title='关闭订单'>关闭</a>";
                break;

            case '4'://交易关闭
                html+="<a  href='javascript:;'  oid ='"+oid+"' class='btn btn-default btn-xs delete-order'>删除</a>";
                break;
            case '5'://交易成功
                html+="<a  href='javascript:;'  oid ='"+oid+"' class='btn btn-default btn-xs delete-order'>删除</a>";
                break;
            case '6'://交易成功
                html+="<a  href='javascript:;'  oid ='"+oid+"' class='btn btn-warning btn-xs refund'>退款</a>";
                break;
            case '7'://交易成功
                html+="<a  href='javascript:;'  oid ='"+oid+"' class='btn btn-default btn-xs delete-order'>删除</a>";
                break;
            //默认状态是已删除  ——安全性要求
            default:html+="--"; //已删除

        }
        return html ;
    }
    
    init();
});
