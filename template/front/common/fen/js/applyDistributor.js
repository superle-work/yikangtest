/**
 * 推广页面
 * @since 2015-11-12
 * @author jjhu
 */
$(function(){
    function init(){
        bindEvent();
    }

    function bindEvent(){
        //朋友圈类型下拉事件
        $("#content #friend_type").change(function(){
            var value = $(this).val();
            if(value == "other"){
                $(this).next().show();//其他类型 用户手动输入
            }else{
                $(this).next().hide();
            }
        });

        //是否做过微信营销 单选按钮点击事件
        $("#content .is_weixin_sale").click(function(){
            var value = $(this).val();
            if(value == 1){//选择做过，则显示备注内容让其填写
                $("#content .comment-area").show();
            }else{
                $("#content .comment-area").hide();
            }
        });

        $("#content #save").click(function(){
            var name = $.trim($("#content #name").val());//姓名 必填
            var phone = $.trim($("#content #phone").val());//联系方式 必填
            var sparetime = $.trim($("#content .sparetime:checked").val());//业余时间
            var friend_type = $.trim($("#content #friend_type").val());//朋友圈类型
            if(friend_type == "other"){//其他类型
                friend_type = $.trim($("#content .other-type").val())
            }
            var friend_count = $.trim($("#content #friend_count").val());//朋友圈人数
            var is_weixin_sale = $("#content .is_weixin_sale:checked").val();//是否做过微信销售
            var comment = $.trim($("#content #comment").val());//备注

            if(name !="" && phone !="" && friend_type !="" && friend_count !=""){
                $("#content .error-info").css("visibility",'hidden');
                var pReg = /^(\d){11}$/ ;
                //手机验证不合法
                if(!pReg.test(phone)){
                    $("#content .action-tip").html("<div class='error-info'>手机号不合法！</div>");
                    $("#content .error-info").css("visibility",'visible');
                    return false;
                }
                var numberReg = /^(\d){1,}$/;
                //数字验证
                if(!numberReg.test(friend_count)){
                    $("#content .action-tip").html("<div class='error-info'>朋友圈人数请填写数字</div>");
                    $("#content .error-info").css("visibility",'visible');
                    return false;
                }

                var did = $("#content #did").val();

                $.ajax(
                    {
                        type:"post",
                        url:"./index.php?c=distributor&a=submitApply",
                        data:{
                            "name":name,
                            "phone":phone,
                            "sparetime":sparetime,
                            "friend_type":friend_type,
                            "friend_count":friend_count,
                            "is_weixin_sale":is_weixin_sale,
                            "comment":comment,
                            "did":did
                        },
                        dataType:"json",
                        success:function(json,statusText){
                            if(json.errorCode == 0){
                                alert("提交成功，请等待申请！");
                                //跳转微商城主页
                                window.location.href = "./index.php?c=mall&a=index";

                            }else{
                                $("#content .action-tip").html('<div class="error-info">'+json.errorInfo+'</div>');
                                $("#content .error-info").css("visibility",'visible');
                            }
                        },
                        error:function(){
                            $("#content .action-tip").html('<div class="error-info">网络异常，请求失败!</div>');
                            $("#content .error-info").css("visibility",'visible');
                        }
                    }
                );
            }else{
                $("#content .action-tip").html('<div class="error-info">请填写所有必填项信息!</div>');
                $("#content .error-info").css("visibility",'visible');
            }
            return false;//阻止浏览器默认行为
        });
    }

    init();
});