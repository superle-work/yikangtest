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
            editAdmin();
            return false;
        });
    }
    /**
     * 编辑普通管理员
     */
    function editAdmin(){
        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,options,myFormOptions));
    }

    /**
     * 提交添加信息的表单配置
     */
    var  myFormOptions={
        url:'./admin.php?c=base_admin&a=updateAdmin',
        success:successResponse
    };
    function successResponse(json,statusText){
        if(json.errorCode == 0){
            responseTip(json.errorCode,"恭喜您，操作成功！",1500,function(){
            	location.href = './admin.php?c=base_main&a=logout';
//            	window.history.go(-1);
            	});
        }else{
            responseTip(json.errorCode,json.errorInfo,1500);
        }
    }
    //表单验证信息
    var validateInfo ={
        rules:{
            oldPassword:{
                required:true,
                remote:{
                    url:"./admin.php?c=base_admin&a=testPassword",
                    type:'post',
                    dataType:'json',
                    data:{
                        account:function(){
                            return $.trim($("#myForm #account").val());
                        },
                        password:function(){
                            return $.trim($("#myForm #oldPassword").val());
                        }
                    }
                }
            },
            confirmPwd:{
                confirmPwd:true
            }
        },
        messages:{
            oldPassword:{
                required:"请输入密码",
                remote:"密码错误"
            },
            confirmPwd:{
                confirmPwd:"两次密码输入不一致"
            }
        },
        errorPlacement:function(error,element){
            element.parent().next().append(error);
        }
    };

    init();
});