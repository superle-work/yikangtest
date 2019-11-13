$(function(){

    /**
     * 页面初始化
     */
    function init(){
        bindEvent();
    }

    function bindEvent(){

        $("#list-table .delete").click(function(){
            var _this = $(this);
            var id = $(this).attr('data-id');
            myConfirmModal("确认删除吗？",function(){
                $.ajax({
                    url:'./admin.php?c=store_label&a=delLabel',
                    type:'post',
                    data:{id:id},
                    dataType:'json',
                    beforeSend:function(xhr){
                        //显示“加载中。。。”
                        $("#loading").modal('show');
                    },
                    complete:function(){
                        //隐藏“加载中。。。”
                        $("#loading").modal('hide');
                    },
                    success:function(json){
                        if(json.errorCode == 0){
                            _this.parents("tr").remove();
                            //window.location.reload();
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