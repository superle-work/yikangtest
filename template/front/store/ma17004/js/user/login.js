$(function(){
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
    	/*
    	 * 刷新图片验证码
    	 * */
    	$(".captcha-img").click(function(){
    		var url=$(this).attr("src");
    		var num=Math.random(0,1);
    		var url=url+'&'+num;
    		$(this).attr("src",url);
    	})
    	
        /**
         * 获取验证码
         */
        $("#content form .getYzm").click(function(){
            //手机号验证
            var _this = $(this);
            if(_this.hasClass("time")){
                //倒计时中，不能进行操作
                return false;
            }
            var pReg = /^1[34578]{1}\d{9}$/;
            var phone = $.trim($("#content form #phone").val());
            if(phone !=""){
                //手机验证不合法
                if(!pReg.test(phone)){
                	showDialog('#errorDialog','错误','手机号不合法','&#xe6a9;');
                    return false;
                }
            }else{
            	showDialog('#errorDialog','错误','手机号必填！','&#xe6a9;');
                return false;
            }
			
			var img_yzm=$("#img_yzm").val();
            
            $.ajax({
                url:"index.php?c=base_user&a=sendSMS",
                type:"post",
                data:{"phone":phone,'captcha':img_yzm},
                dataType:"json",
                beforeSend:function(xhr){
    	               //显示“加载中。。。”
    	            	showDialog("#loadingDialog");
                },
                complete:function(){
    	               //隐藏“加载中。。。”
    	            	hideDialog("#loadingDialog");
                },
                success:function(json,statusText){
                    if(json.errorCode == 0){
                        //获取验证码成功
                        //一分钟内不让重新点击 获取验证码

                        _this.addClass("time");//添加class类，标识进入倒计时 60秒
                        _this.html("60");
                        var intervalID = setInterval(function(){
                            var s = parseInt(_this.html());//获取当前秒数，然后再减1
                            if(s == 0){
                                //倒计时结束
                                _this.removeClass("time");//去除倒计时标识，可以重新获取验证码
                                _this.html("重新获取");
                                clearInterval(intervalID);
                            }else{
                                s = s - 1;
                                _this.html(s);
                            }
                        },1000);//60秒，开始倒计时
                    }else{
                        //获取验证码失败
                    	showDialog('#errorDialog','错误',json.errorInfo,'&#xe6a9;');
                    }
                },
                error:function(){
                    //请求失败
                	showDialog('#errorDialog','','请求失败，请稍后再试！','&#xe6a9;');
                }
            });
        });
        
        /**
         * 提交
         */
        $("#content #send").click(function(){
            //手机号验证
            var pReg = /^(\d){11}$/ ;
            var phone = $.trim($("#content form #phone").val());
            if(phone !=""){
                if(!pReg.test(phone)){
                	showDialog('#errorDialog','','手机号不合法！','&#xe6a9;');
                    return false;
                }
            }else{
            	showDialog('#errorDialog','','手机号必填！','&#xe6a9;');
                return false;
            }
            
            var img_str=$("#img_yzm").val();
            if(img_str == ""){
            	showDialog('#errorDialog','','请输入图片验证码！','&#xe6a9;');
                return false;
            }
            
            //获取验证码
            var verifyPhone = $("#content #yzm").val();
            if(verifyPhone == ""){
                showDialog('#errorDialog','','请输入手机验证码！','&#xe6a9;');
                return false;
            }

            //发送请求，进行验证
            $.ajax({
                url:"./index.php?c=base_user&a=userLogin",
                type:"post",
                data:{"captcha":img_str,"phone":phone,"phoneCode":verifyPhone},
                dataType:"json",
                success:function(json,statusText){
                    if(json.errorCode == 0){
                        window.location.href = "index.php?c=store&a=index";
                    }else{
                    	showDialog('#errorDialog','',"操作失败，"+json.errorInfo+"！",'&#xe6a9;');
                    }
                },
                error:function(){
                    //请求失败
                	showDialog('#errorDialog','',"请求失败，请稍后再试！",'&#xe6a9;');
                }
            });
            return false;//阻止浏览器默认行为
        });
    }
    init();
});