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
    	//修改商品信息
    	$('.save').click(function() {
    		modifyOrder();
    	});
    }

    /**
     * 修改订单
     */
    function modifyOrder(){
        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,orderFormOptions));
    }
    /**
     * 提交添加商品信息的表单配置
     */
    var  orderFormOptions={
        url:'./index.php?c=store&a=modifyOrder',
        success:successResponse,
        error:errorResponse
    };
    /**
     * 添加商品信息得到服务器响应的回调方法
     */
    function successResponse(json,statusText){
        if(json.errorCode == 0){
        	showDialog("#successDialog",'操作成功',"恭喜您，操作成功！",'',1500,function(){window.history.go(-1);});
        }else{
        	showDialog("#errorDialog",'操作失败',json.errorInfo,'',1500);
        }
    }

    //表单验证信息
    var validateInfo ={
        rules:{
            total_price:{//订单总价
                required:true,
                number: true
            },
            call_name:{//联系人
                required:true
            },
            phone:{//联系电话
                required:true,
                digits:true 
            },
            address:{//送货地址
                required:true
            }
        },
        messages:{
        	 total_price:{//订单总价
                 required:"请填写订单总价",
                 number:"必须输入正常的数组"
             },
             call_name:{//联系人
                 required:"请填写联系人"
             },
             phone:{//联系电话
                 required:"请填写联系电话",
                 digits:"电话格式不合法"
             },
             address:{//送货地址
                 required:"请填写送货地址"
             }
        },
        errorPlacement:function(error,element){
            //var name = element.attr("name");
        	showDialog("#errorDialog",'输入错误',error.text(),'',1500);
        }
    };
    init();
});