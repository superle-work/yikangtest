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
        //编辑
        $(".inner-section .edit").click(function(){
            var id = $(this).attr("aid");
            window.location.href = "./admin.php?c=base_admin&a=editAdmin&id="+id;
        });
        //修改密码
        $(".inner-section .editPwd").click(function(){
            var id = $(this).attr("aid");
            window.location.href = "./admin.php?c=base_admin&a=editPwd&id="+id;
        });
        //删除
        $(".inner-section .delete").click(function(){
            var id = $(this).attr("aid");
            var account = $(this).attr("account");
            myConfirmModal("确定删除吗？",function(){
                $.ajax({
                    url:"./admin.php?c=base_admin&a=deleteAdmin",
                    type:"post",
                    data:{"id":id,"account":account},
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
                            responseTip(json.errorCode,"恭喜您，操作成功！",1500,function(){window.location.reload();});

                        }else{
                            responseTip(json.errorCode,json.errorInfo,1500);
                        }
                    },
                    error:errorResponse
                });
            });
        });
    }
    init();
});