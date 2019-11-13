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
        /**
         * 获取验证码
         */
        $("#content form .getCode").click(function(){
            //手机号验证
            var _this = $(this);
            if(_this.hasClass("time")){
                //倒计时中，不能进行操作
                return false;
            }
            $("#content .tip-info").hide();
            var pReg = /^(\d){11}$/ ;
            var phone = $.trim($("#content form #phone").val());
            if(phone !=""){
                //手机验证不合法
                if(!pReg.test(phone)){
                	showDialog('#errorDialog','','手机号不合法','');
                    return false;
                }
            }else{
            	showDialog('#errorDialog','','手机号必填！','');
                return false;
            }

            $.ajax({
                url:"./index.php?c=user&a=sendSMS",
                type:"post",
                data:{"phone":phone},
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
                    	showDialog('#errorDialog','','操作失败','');
                    }
                },
                error:function(){
                    //请求失败
                	showDialog('#errorDialog','','请求失败，请稍后再试！','');
                }
            });
        });
        /**
         * 注册
         */
        $("#content form .register").click(function(){
            $("#content .tip-info").hide();
            //邮箱验证，手机号验证
            var eReg =  /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
            var email = $.trim($("#content form #email").val());
            if(email !="" && !eReg.test(email)){
                //邮箱验证不合法
            	showDialog('#errorDialog','','邮箱不合法！','');
                return false;
            }
            //手机号验证
            var pReg = /^(\d){11}$/ ;
            var phone = $.trim($("#content form #phone").val());
            if(phone !=""){
                if(!pReg.test(phone)){
                	showDialog('#errorDialog','','手机号不合法！','');
                    return false;
                }
            }else{
            	showDialog('#errorDialog','','手机号必填！','');
                return false;
            }
            //获取验证码
            var code = "";
            var verifyPhone = $("#content #verifyPhone").val();
            if(verifyPhone == 1){//需要进行手机验证码注册时
                code = $.trim($("#content form #phoneCode").val());
                if(code == ""){
                    showDialog('#errorDialog','','请输入验证码！','');
                    return false;
                }
            }

            //发送请求，进行注册验证
            $.ajax({
                url:"./index.php?c=user&a=registerUser",
                type:"post",
                data:{"email":email,"phone":phone,"phoneCode":code},
                dataType:"json",
                success:function(json,statusText){
                    if(json.errorCode == 0){
                        //注册成功
                        window.location.href = "./index.php?c=store&a=index";
                    }else{
                        //注册失败
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