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
    	//修改模板
    	$('#save').click(function() {
            updateTemplate();
		});
    }

    /**
     * 添加模板
     */
    function updateTemplate(){
        //判断是否有勾选属性
        var ptids = "";
        var checkedList = $(".property .value:checked");
        checkedList.each(function(index){
            //去除最后一个逗号
            if(index < checkedList.length){
                ptids +=$(this).val() +",";
            }else{
                ptids +=$(this).val();
            }
        });
        $("#myForm #ptids").val(ptids);
        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,myFormOptions));
    }

    /**
     * 提交信息的表单配置
     */
    var  myFormOptions={
        url:'./admin.php?c=store_template&a=updateTemplate',
        success:successResponse,
        error:errorResponse
    };
    /**
     * 添加商品信息得到服务器响应的回调方法
     */
    function successResponse(json,statusText){
        if(json.errorCode == 0){
            responseTip(json.errorCode,"恭喜您，操作成功！",1500,function(){window.location.reload();});

        }else{
            responseTip(json.errorCode,json.errorInfo,1500);
        }
    }

    //表单验证信息
    var validateInfo ={
        rules:{
            name:{//模板名称
                required:true,
                remote:{
                    url:"./admin.php?c=store_template&a=isTemplateNameExist",
                    type:'post',
                    dataType:'json',
                    data:{
                        preName:function(){
                            return $("#myForm #name").attr("prevName");},
                        curName:function(){
                            return $.trim($("#myForm #name").val());}
                    }
                }
            }
        },
        messages:{
            name:{//名称
                required:"请输入名称",
                remote:"该名称被占用，请更换"
            }
        },
        errorPlacement:function(error,element){
            element.parent().next().append(error);
        }
    };
    init();
});