$(function(){	
    /**
     * 页面初始化
     */
    function init(){
		var province = $(".area-info").attr("data-province");
    	var city = $(".area-info").attr("data-city");
    	var area = $(".area-info").attr("data-area");
    	addressInit('province', 'city', 'area',province,city,area);
    	bindEvent();
        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $("#myForm").validate(validateInfo);
        
    }

    /**
     * 事件绑定
     */
    function bindEvent(){
    	//修改商品信息
    	$('#save').click(function() {
    		updateGoods();
    	});
        
    	//返回
    	$('#back').click(function() {			 
            window.history.go(-1);
    	});
    }

    /**
     * 修改商品
     */
    function updateGoods(){
        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,goodsFormOptions));
    }
    /**
     * 提交添加商品信息的表单配置
     */
    var  goodsFormOptions={
        url:'./admin.php?c=store_agent&a=updateAgent',
        success:successResponse,
        error:errorResponse
    };
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