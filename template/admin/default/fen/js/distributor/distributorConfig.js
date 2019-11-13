/***
 * 分销商系数配置页面
 * @since 2015-11-15
 * @author jjhu
 */
$(function(){
    function init(){
        bindEvent();
    }

    function bindEvent(){
        
        //保存配置
        $(".item .submit").click(function(){
            $(".item form").ajaxSubmit($.extend(true,{},formOptions,options,{url:'./admin.php?c=fen_distributor&a=setFeeRatioConfig',success:successResponse,error:errorResponse}));
            return false;
        });

    }
    function successResponse(json,statusText){
        if(json.errorCode == 0){
            $("#myModal .modal-body").html("<p class='text-success'><b>恭喜您，操作成功！</b></p>");
            $("#myModal").modal('show');
            //定时器，1.5秒后模态框自动关闭
            setTimeout(function(){
                $("#myModal").modal('hide');
            },1500);
        }else{
            //alert("添加失败，请稍后再试！");
            $("#myModal .modal-body").html("<p class='text-danger'>"+json.errorInfo+"</p>");
            $("#myModal").modal('show');
            //定时器，1.5秒后模态框自动关闭
            setTimeout(function(){
                $("#myModal").modal('hide');
            },1500);
        }
    }

    /**
     *请求添加失败时（如网络不通畅、超时等）的回调方法
     */
    function errorResponse(XMLHttpRequest,textStatus,errorThrown){
        $("#myModal .modal-body").html("<p class='text-danger'>很抱歉，请求失败,网络异常！</p>");
        $("#myModal").modal('show');
        //定时器，1.5秒后模态框自动关闭
        setTimeout(function(){
            $("#myModal").modal('hide');
        },1500);
    }
    init();

});