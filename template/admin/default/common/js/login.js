/**
 * 登录
 **/
$(function(){


    /**
     * 初始化
     */
    function init(){
        $("[data-toggle='popover']").popover();
        bindEvent();
    }

    /**
     * 绑定事件
     */
    function bindEvent(){
        //logo的悬浮效果
        /*$("#header .logo img").hover(function(){
            $(this).attr("src","./themes/image/change2.png");
        },function(){
            $(this).attr("src","./themes/image/change.png");
        });*/

        $("#container .login-area input").focus(function(){
            $("#container .login-area .login-info").removeClass("active");
            $(this).parent().addClass("active");

        });
        //enter 事件
        $("#container .login-area input").keydown(function(event){
            event = event? event:window.event;
            if(event.keyCode == 13){
                $("#container .login-area .btn-login").click();
            }
        });
        $("#container .login-area .btn-login").click(function(){
            //管理员登录
            var account = $.trim($("#container .login-area  #account").val());//账号
            var password = $.trim($("#container .login-area  #password").val());//密码
			var remember = 0;//记住密码
            if($("#container .login-area  .remember-me :checked").length > 0){
                remember = 1;//记住密码
            }else{

            }
			if(account == "" || password == ""){
                $("#myModal .modal-body").html("<p class='text-danger'>请输入账号名和密码！</p>");
                $("#myModal").modal('show');
                //定时器，1.5秒后模态框自动关闭
                setTimeout(function(){
                    $("#myModal").modal('hide');
                },3000);
                return false;
            }
			
            $.ajax(
                {
                    type:"post",
                    url:"./admin.php?c=base_main&a=adminLogin",
                    data:{"account":account,"password":password,"remember":remember},
                    dataType:"json",
                    success:function(json,statusText){
                        if(json.errorCode == 0){
                            //登录成功,跳转页面
                            if(json.data.type=='1'){
                                window.location.href = "./admin.php?c=base_index&a=index";
                            }
                            else{
                                window.location.href = "./admin.php?c=store_order&a=orderList&mid=29";
                            }
                        }else if(json.errorCode == 1){
                            //登录失败
                            $("#myModal .modal-body").html("<p class='text-danger'>登录失败，用户名或密码错误！</p>");
                            $("#myModal").modal('show');
                            //定时器，1.5秒后模态框自动关闭
                            setTimeout(function(){
                                $("#myModal").modal('hide');
                            },1500);
                        }
                        return false;
                    },
                    error:function(){
                        $("#myModal .modal-body").html("<p class='text-danger'>很抱歉，操作失败,发生异常！</p>");
                        $("#myModal").modal('show');
                        //定时器，1.5秒后模态框自动关闭
                        setTimeout(function(){
                            $("#myModal").modal('hide');
                        },1500);
                        return false;
                    }
                }
            );
            return false;
        });
    }
    init();
});