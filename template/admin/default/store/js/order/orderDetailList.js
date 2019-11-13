$(function(){
    /**
     * 页面初始化
     */
    function init(){
    	bindEvent()
    }

    /**
     * 事件绑定
     */
    function bindEvent(){
        //导出订单详情
        $(".right-section .operation-div #export_btn").click(function(){
            var id = $(this).attr("data-oid");
            window.location.href = "./admin.php?c=store_order&a=exportOrderDetail&oid="+id;
        });
        
        //小票打印
        $(".print-button .send-ticket-notify").click(function(){
            var oid = $(this).attr("data-oid");
            var key = $(this).attr('data-key');
            $.ajax({
            	type:"post",
            	url:"./index.php?c=server&a=sendTicketNotify",
            	data:{oid:oid,key:key,module:'store'},
            	async:true,
            	beforeSend:function(xhr){
                    //显示“加载中。。。”
                    $("#loading").modal('show');
                },
                complete:function(){
                    //隐藏“加载中。。。”
                    $("#loading").modal('hide');
                }
            });
        });
    }
    init();
});