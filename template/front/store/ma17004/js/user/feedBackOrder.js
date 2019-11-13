/**
 * 微商城--订单返回界面
 */
$(function(){
    /**
     * 初始化
     */
    function init(){
    	//第一次打开页面显示管理员按钮
    	var first_state = parseInt($('.state').val());
    	var first_oid = $('.oid').val();
    	orderOperatoin(first_state, first_oid);
        bindEvent();
    }

    /**
     * 绑定事件
     */
    function bindEvent(){
    	//关闭订单
        $(".close-order").click(function(){
            var oid = $(this).attr('oid');
            showConfirmDialog('#dangerConfirmDialog','','确认关闭该订单吗？','','',function(){
            	setOrderState(oid,4);
            });
        });

        //发货
        $(".send-goods").click(function(){
            var oid = $(this).attr('oid');
            setOrderState(oid,2);
        });

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

        //删除订单
        $(".delete-order").click(deleteOrder);

    }
    
    /**
     * 设置订单状态
     */
    function setOrderState(oid,state){
        $.post("./index.php?c=store&a=setOrderState",{
                'oid':oid,
                'state':state
            },function(){
            	if( $('.admin-operate').length > 0 ){
            		orderOperatoin(state,oid);
            	}
                $('.order-state').html(setState(state));
            }
        );
    }
    
    /**
     * 设置订单的中文状态
     * //0待付款  1待发货 2已发货 3已收货 4交易关闭 5交易完成
     */
    function setState(state){
        var html = "";
        switch (state)
        {
            case 0:
                html ="<span class='text-info'>待付款</span>";
                break;
            case 1:
                html ="<span style='color:#EE0000'>待发货</span>";
                break;
            case 2:
                html ="<span class='text-primary'>已发货</span>";
                break;
            case 3:
                html ="<span class='text-primary'>已收货</span>";
                break;
            case 4:
                html ="<span style='color:gray'>交易关闭</span>";
                break;
            case 5:
                html ="<span class='text-success'>交易完成</span>";
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
        switch (state)
        {
            case 0://待付款
            	$('.operate-button').hide();
            	$('.close-order').show();
                break;
            case 1://待发货
            	$('.operate-button').hide();
            	$('.send-goods').show();
            	$('.close-order').show();
                break;
            case 2://已发货
            	$('.operate-button').hide();
            	$('.accept-goods').show();
            	$('.close-order').show();
                break;
            case 3://已收货
            	$('.operate-button').hide();
            	$('.complete-order').show();
            	$('.close-order').show();
                break;
            case 4://交易关闭
            	$('.operate-button').hide();
            	$('.close-order').show();
                break;
            case 5://交易成功
            	$('.operate-button').hide();
            	$('.delete-order').show();
                break;
            //默认状态是已删除  ——安全性要求
            default:
            	if( $('.admin-operate').length > 0 ){
            		$('.admin-operate').html('--');
            	}; //已删除

        }
    }
    
    /**
     * 删除订单
     * @param oid
     * @param state
     */
    function deleteOrder(){
        var oid = $(this).attr('oid');
        showConfirmDialog('#alertDialog',' ','确定删除吗？','','',function(){
        	$.ajax(
                    {
                        type:"post",
                        url:"./index.php?c=store&a=deleteOrder",
                        data:{"id":oid},
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
                            	$('.order-state').html('已删除');
                            	$('.admin-operate').html('已删除');
                            }else{
                            	showDialog('#errorDialog','','删除失败！','');
                            }
                        },
                        error:errorResponse
                    }
                );
        	});

    }
    init();
});