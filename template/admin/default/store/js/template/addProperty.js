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
			addProperty();
		});

        /**
         * 属性的enter事件，-->添加新属性值
         */
        $("#myForm .value-info .value").keydown(function(event){
            event = event?event:window.event;
            if(event.keyCode == 13){
                $("#myForm .append-value").click();
            }
        });

        //为属性添加多个“属性值”输入框
        $("#myForm .append-value").click(function(){
            var clone = $(this).prev().clone(true);
            clone.find(".value").val("");//新值置为空
            if(clone.find(".value-delete").length == 0){
                clone.append('<span class="value-delete" title="删除"></span>');
                $(this).before(clone);
                $("#myForm .value-delete").click(function(){ $(this).parent().remove();});
            }else{
                $(this).before(clone);
            }
			$(this).prev().find(".value").focus();
        });
    }

    /**
     * 添加
     */
    function addProperty(){
        //先判断 属性值是否在在至少一个
        var values = "";
        $("#myForm .value").each(function(){
            var value = $(this).val().trim();
            if(value !=""){
                values +=value+",";
            }else{

            }
        });
        if(values !=""){
            //存在属性值时，可以提交
            $("#myForm .append-value").parent().next().html("");
            $("#myForm #values").val(values);
        }else{//<label for="name" generated="true" class="error">请输入名称</label>
            $("#myForm .append-value").parent().next().html('<label for="name" generated="true" class="error">请设置至少一个属性值</label>');
            return false;
        }

        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,myFormOptions));
    }

    /**
     * 提交添加商品信息的表单配置
     */
    var  myFormOptions={
        url:'./admin.php?c=store_template&a=insertProperty',
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
            name:{//属性名称
                required:true,
                remote:{
                    url:"./admin.php?c=store_template&a=isPropertyExist",
                    type:'post',
                    dataType:'json',
                    data:{
                        name:function(){
                            return $.trim($("#myForm #name").val());}
                    }
                }
            }
        },
        messages:{
            name:{//商品名称
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