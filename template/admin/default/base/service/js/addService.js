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
        //添加商品
        $('#myForm #save').click(function() {
        	//保证存在客服联系号码或二维码
        	//客服类型  1：qq  2：微信
    		var type = $("#myForm .service-type-wrap .service-type:checked").val();
    		var weichat_number = $('#weichat_number').val();
    		var qq = $.trim($("#myForm #qq_number").val());
    		var phone = $.trim($("#myForm #phone_number").val());
        	if((type == 1 && qq == '') || (type ==2 &&  weichat_number == '') || (type == 3 && phone == '')){
        		var text = "";
        		if(type == 1){
        			text = "请填写客服QQ！";
        		}else if(type == 2){
        			text = "请上传微信二维码！";
        		}else if(type == 3){
        			text = "请填写客服电话！";
        		}
        		responseTip(1,text,2000);
        		return false;
        	}
            addService();
        });
        
    	//返回
    	$('#myForm #back').click(function() {			 
            window.history.go(-1);
    	});
    
        //添加图片事件
        $(".content .right-section #weichat_number").change(function(){
            var filepath=$(this).val();
            if(filepath == ""){
                return false;
            }
            var extStart=filepath.lastIndexOf(".");
            var ext=filepath.substring(extStart,filepath.length).toUpperCase();
            if(ext.toLowerCase()!=".jpg" && ext.toLowerCase()!=".jpeg"
                && ext.toLowerCase()!=".png" && ext.toLowerCase()!=".gif"){
                $(this).val("");
                responseTip(1,"文件格式不正确，仅支持jpg、jpeg、gif、png格式，文件小于5M！",2000);
            }

        });
        
        //改变客服类型 1QQ客服 2微信客服 3 电话
        $('.content .right-section #myForm .service-type').change(function(){
        	var type = $(this).val();
        	if(type == 1){
        		$('.content .right-section #myForm .qq_number').css('display', 'block');
        		$('.content .right-section #myForm .qq_number').siblings().css('display', 'none');
        	}else if(type == 2){
        		$('.content .right-section #myForm .weichat_number').siblings().css('display', 'none');
        		$('.content .right-section #myForm .weichat_number').css('display', 'block');
        	}else if(type == 3){
        		$('.content .right-section #myForm .phone_number').siblings().css('display', 'none');
        		$('.content .right-section #myForm .phone_number').css('display', 'block');
        	}
        	
        });
    }

    /**
     * 添加商品
     */
    function addService(){
        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,serviceFormOptions));
    }

    /**
     * 提交添加商品信息的表单配置
     */
    var  serviceFormOptions={
        url:'./admin.php?c=base_service&a=insertService',
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
            name:{//商品名称
                required:true
            },
            weichat_number:{
                accept:"jpg,jpeg,gif,png"
            },
            sort:{
                required:true
            }
        },
        messages:{
            name:{//商品名称
                required:"必填项"
            },
            weichat_number:{
                accept:"仅支持jpg、jpeg、gif、png格式"
            },
            sort:{
                required:"必填项"
            }
            
        },
        errorPlacement:function(error,element){
            //var name = element.attr("name");
            element.parent().next().append(error);
        }
    };
    init();
});

