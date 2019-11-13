$(function(){
	//sui显示
	$("#sex").picker({
		toolbarTemplate: '<header class="bar bar-nav">\<button class="button button-link pull-left">取消</button>\<button class="button button-link pull-right close-picker">确定</button>\<h1 class="title">请选择性别</h1>\</header>',
		cols: [{
		      textAlign: 'center',
		      values: ['男', '女']
		}]
	});
    /**
     * 初始化
     */
    function init(){
        bindEvent();
    }

    /**
     * 绑定事件
     */
    function bindEvent(){
    	//取消按钮
    	$(document).on("click",".bar-nav .pull-left",function(){
    		$("#sex").picker("close");
    		$("#sex").val("");
    	})
    	
        /**
         * 添加
         */
        $(".send").click(function(){
        	/*
            //手机号验证
            var pReg = /^(\d){11}$/ ;
            var phone = $.trim($("input[name=phone]").val());
            if(phone !=""){
                if(!pReg.test(phone)){
                	showDialog('#errorDialog','','手机号不合法！','');
                    return false;
                }
            }else{
            	showDialog('#errorDialog','','手机号必填！','');
                return false;
            }
            //身份证验证
            var pReg2 = /(^d{15}$)|(^d{17}([0-9]|X)$)/ ;
            var idCard = $.trim($("input[name=idCard]").val());
            if(idCard !=""){
                if(!pReg2.test(idCard)){
                	showDialog('#errorDialog','','身份证号码不合法！','');
                    return false;
                }
            }else{
            	showDialog('#errorDialog','','身份证号码必填！','');
                return false;
            }
            */
           	var phone = $.trim($("input[name=phone]").val());
           	var name = $.trim($("input[name=name]").val());
           	var sex = $.trim($("input[name=sex]").val());
           	var idCard = $.trim($("input[name=idCard]").val());
            //发送请求
            $.ajax({
                url:"./index.php?c=store&a=insertMember",
                type:"post",
                data:{"phone":phone,"name":name,"idCard":idCard,'sex':sex},
                dataType:"json",
                success:function(json,statusText){
                    if(json.errorCode == 0){
                        window.location.href = "./index.php?c=store&a=checkMember";
                    }else{
                    	showDialog('#errorDialog','',"操作失败，"+json.errorInfo+"！",'');
                    }
                },
                error:function(){
                    //请求失败
                	showDialog('#errorDialog','',"请求失败，请稍后再试！",'');
                }
            });
            return false;//阻止浏览器默认行为
        });
    }
    init();
});