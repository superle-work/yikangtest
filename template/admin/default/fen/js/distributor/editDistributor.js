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
    	//修改分销商
    	$('#save').click(function() {
            updateDistributor();
		});
    }

    /**
     * 提交修改信息
     */
    function updateDistributor(){
        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,myFormOptions));
    }

    /**
     * 提交信息的表单配置
     */
    var  myFormOptions={
        url:'./admin.php?c=fen_distributor&a=updateDistributor',
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
            name:{//标签名称
                required:true
            },
            phone:{
                required:true,
                phone:true
            },
            level:{
            	required:true,
            },
            fir_fee:{
            	required:true,
            },
            sec_fee:{
            	required:true,
            },
            thr_fee:{
            	required:true,
            },
            my_fee:{
            	required:true,
            },
            freeze_fee:{
            	required:true,
            },
        },
        messages:{
            name:{//商品名称
                required:"请输入分销商名称"
            },
            phone:{
                required:"请输入手机号码",
            	phone:"号码格式不正确"
            },
            level:{
            	required:"必填项",
            },
            fir_fee:{
            	required:"必填项",
            },
            sec_fee:{
            	required:"必填项",
            },
            thr_fee:{
            	required:"必填项",
            },
            my_fee:{
            	required:"必填项",
            },
            freeze_fee:{
            	required:"必填项",
            },
        },
        errorPlacement:function(error,element){

            element.parent().next().append(error);
        }
    };
    init();
});