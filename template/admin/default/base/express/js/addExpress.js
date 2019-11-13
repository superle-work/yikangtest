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
        //添加快递信息
        $('#myForm #save').click(function() {
            addService();
        });
        
    	//返回
    	$('#myForm #back').click(function() {			 
            window.history.go(-1);
    	});
    }

    /**
     * 添加快递信息
     */
    function addService(){
        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,unitsFormOptions));
    }

    /**
     * 提交添加快递信息的表单配置
     */
    var  unitsFormOptions={
        url:'./admin.php?c=base_express&a=insertExpress',
        success:successResponse,
        error:errorResponse
    };
    /**
     * 添加快递信息得到服务器响应的回调方法
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
            name:{//快递名称
                required:true
            },
            code:{
                required:true
            },
			sort:{
			    required:true,
			    digits:true
			}
        },
        messages:{
            name:{//快递名称
                required:"必填项"
            },
            code:{
                required:"必填项"
            },
            sort:{
                required:"必填项",
                digits:"排序必须为整数"
            }
        },
        errorPlacement:function(error,element){
            element.parent().next().append(error);
        }
    };
    init();
});
