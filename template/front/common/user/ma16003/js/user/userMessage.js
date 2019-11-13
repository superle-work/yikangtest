$(function(){
    /**
     * 我的佣金
     */
    function init(){
        bindEvent();
    }
    function bindEvent(){
        //消息点击事件
        $("#content .message-info").click(function(){

            var href = $(this).attr("data-href");//链接地址
            if(href !=""){
                window.locaton.href = href;
            }else{
                return false;
            }
        });

        //消息删除事件
        $("#content .message-info .delete").click(function(event){
            event = event || window.event;
            event.stopPropagation();//阻止事件冒泡
            var _this = $(this);
            var id = _this.attr("data-id");
            $.ajax({
                type:"post",
                url:"./index.php?c=base_user&a=deleteMessage",
                data:{"ids":id},
                dataType:"json",
                success:function(json,statusText){
                    if(json.errorCode == 0){//操作成功
                        _this.parents(".message-info").remove();
                    }else{//操作异常
                        alert(json.errorInfo)
                    }
                },
                error:function(){
                    alert("网络异常，请求失败！")
                }
            });
        });
    }
    init();
});