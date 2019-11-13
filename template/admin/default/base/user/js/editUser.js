$(function(){

    /**
     * 初始化
     */
    function init(){
        bindEvent();
        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $("#myForm").validate(validateInfo);
    }

    /**
     * 绑定事件
     */
    function bindEvent(){
        $("#myForm .submit").click(function(){
            editUser();
            return false;
        });
    }
    /**
     * 编辑
     */
    function editUser(){
        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,options,myFormOptions));
    }

    /**
     * 提交添加信息的表单配置
     */
    var  myFormOptions={
        url:'./admin.php?c=base_user&a=updateUser',
        success:successResponse
    };
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
            nick_name:{
                required:true
            }
        },
        messages:{
            nick_name:{
                required:"必填项"
            }
        },
        errorPlacement:function(error,element){
            element.parent().next().append(error);
        }
    };
    
    init();
});