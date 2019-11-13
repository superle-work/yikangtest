/**
 * 登录
 **/
$(function(){


    /**
     * 初始化
     */
    function init(){
        bindEvent();
    }

    /**
     * 绑定事件
     */
    function bindEvent(){
       //清除缓存
        $(".right-section .clear-cache").click(function(){
            $.ajax({
                url:"./admin.php?c=base_index&a=clearCache",
                type:"post",
                data:{},
                dataType:"json",
                beforeSend:function(xhr){
                    //显示“加载中。。。”
                    $("#loading").modal('show');
                },
                complete:function(){
                    //隐藏“加载中。。。”
                    $("#loading").modal('hide');
                },
                success:function(json,statusText){
                    if(json.errorCode == 0){
                        responseTip(json.errorCode,"恭喜您，操作成功！",1500);

                    }else{
                        responseTip(json.errorCode,json.errorInfo,1500);
                    }
                },
                error:errorResponse
            });
        });

    }
    init();
});