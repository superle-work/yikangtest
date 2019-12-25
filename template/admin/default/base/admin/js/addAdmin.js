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
        //判断点击的用户类型是子账户，则显示代理
        $("input[name=type]").click(function(){
            var type=$(this).val();
            if(type==2){
                $(this).parents('tr').next().removeClass("hidden");
            }
            else{
                $(this).parents('tr').next().addClass("hidden");
            }
        })

        $("#myForm .submit").click(function(){
            if($("input[name=type]:checked").val()=='2'){
                if($("select[name=agent_id]").val()=='' || $("select[name=agent_id]").val()==null ){
                    responseTip('','子账户必须选择代理',2000);
                    return;
                } 
            }
            addAdmin();
            return false;
        });
    }
    /**
     * 添加普通管理员
     */
    function addAdmin(){
        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,options,myFormOptions));
    }

    /**
     * 提交添加信息的表单配置
     */
    var  myFormOptions={
        url:'./admin.php?c=base_admin&a=insertAdmin',
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
            account:{
                required:true,
                remote:{
                    url:"./admin.php?c=base_admin&a=isAdminExist",
                    type:'post',
                    dataType:'json',
                    data:{
                        account:function(){
                            return $.trim($("#myForm #account").val());}
                    }
                }
            },
            password:{
                required:true
            },
            confirmPwd:{
                required:true,
                confirmPwd:true
            },
            email:{
                email:true
            },
            type:{
                required:true,
            }
        },
        messages:{
            account:{
                required:"必填项",
                remote:"该账号已被占用，请更换"
            },
            password:{
                required:"必填项"
            },
            confirmPwd:{
                required:"必填项",
                confirmPwd:"两次密码输入不一致"
            },
            email:{
                email:"邮箱格式不正确"
            },
            type:{
                required:"必填选择类型"
            }
        },
        errorPlacement:function(error,element){
            element.parent().next().append(error);
        }
    };
    
    init();
});