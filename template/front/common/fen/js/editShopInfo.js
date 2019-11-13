/**
 * 修改店铺信息页面
 * @since 2015-11-11
 * @author jjhu
 */
$(function(){
    /**
     * 初始化
     */
    function init(){
        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $("#myForm").validate(validateInfo);
        bindEvent();
    }

    /**
     * 绑定事件
     */
    function bindEvent(){
	    //表单提交
	    $(".myShop-info .submit").click(function(){
	        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,myFormOptions));
	    });
    }
    
    var  myFormOptions={
        url:'./index.php?c=fen&a=updateShopInfo',
        success:successResponse,
        error:errorResponse
    };
    function successResponse(json,statusText){
        if(json.errorCode == 0){
            alert("提交成功");
            window.location.reload();
        }else{//操作异常
            alert(json.errorInfo);
        }
    }
    function errorResponse(){
        alert("网络异常，请求失败！");
    }
    //表单验证信息
    var validateInfo ={
        rules:{
            shop_name:{//店铺名称
                required:true
            }
        },
        messages:{
            shop_name:{//店铺名称
                required:"请设置店铺名称"
            }
        },
        errorPlacement:function(error,element){
            //var name = element.attr("name");
            $("#myForm .error-info").append(error);
        }
    };

    init();
});