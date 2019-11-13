$(function(){

    /**
     * 页面初始化
     */
    function init(){
		bindEvent();
        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $("#myForm").validate(validateInfo);

    }
    /**
     * 事件绑定
     */
    function bindEvent(){
    	//添加
    	$('#save').click(function() {
			addNews();
		});
    }

    /**
     * 添加消息
     */
    function addNews(){
        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,myFormOptions));
    }

    /**
     * 提交信息的表单配置
     */
    var  myFormOptions={
        url:'./admin.php?c=store_news&a=insertNews',
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
            title:{
                required:true
            },
            sort:{
            	required:true
            }
        },
        messages:{
            title:{//名称
                required:"请输入名称"
            },
            sort:{
            	required:"请输入排序系数"
            }
        },
        errorPlacement:function(error,element){

            element.parent().next().append(error);
        }
    };
    init();
});