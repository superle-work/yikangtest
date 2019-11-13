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
    	//返回
    	$('.back').click(function() {			 
            window.history.go(-1);
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
        url:'./admin.php?c=store_order&a=modifyOrder',
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
                 required:"必填",
                 number:"必须输入正常的数组"
             },
             call_name:{//联系人
                 required:"必填"
             },
             phone:{//联系电话
                 required:"必填",
                 digits:"电话格式不合法"
             },
             address:{//送货地址
                 required:"必填"
             }
        },
        errorPlacement:function(error,element){
            //var name = element.attr("name");
            element.parent().next().append(error);
        }
    };
    init();
});