$(function(){
	
    /**
     * 页面初始化
     */
    function init(){
    	addressInit('province', 'city', 'area','安徽');
        bindEvent();
        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $("#myForm").validate(validateInfo);
    }
    /**
     * 事件绑定
     */
    function bindEvent(){
        //添加商品
        $('#save').click(function() {
        	$("#myForm").validate(validateInfo);
            addGoods();
        });
    }

    /**
     * 添加商品
     */
    function addGoods(){
        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,goodsFormOptions));
    }

    /**
     * 添加商品信息得到服务器响应的回调方法
     */
    function successResponse(json,statusText){
        if(json.errorCode == 0){
            responseTip(json.errorCode,"恭喜您，操作成功！",1500,function(){window.history.go(-1);});
        }else{
            responseTip(json.errorCode,json.errorInfo,1500);
        }
    }
    
    /**
     * 提交添加商品信息的表单配置
     */
    var  goodsFormOptions={
        url:'admin.php?c=store_agent&a=insertAgent',
        success:successResponse,
        error:errorResponse
    };
    

    //表单验证信息
    var validateInfo ={
        rules:{
            name:{//商品名称
                required:true
            },
        },
        messages:{
            name:{//商品名称
                required:"必填项"
            },
        },
        errorPlacement:function(error,element){
            //var name = element.attr("name");
            element.parent().next().append(error);
        }
    };
    
    init();
});

