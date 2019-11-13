$(function(){
    /**
     * 初始化
     */
    function init(){
    	
    	/**
    	 * 控制弹出框大小位置
    	 */
    	$("#addr").click(function(){
    		var width=$("#container").width();
    		var window_width = $(window).width();
        	//$('.picker-modal').css({'max-width': width, left: (window_width - width)/2});
    	});

		//账户类型下拉事件
        $(".account_type").change(function(){
            var value = $(this).val();
            if(value == 2){
                //银行账户
                $(this).parents().find(".account_bank-info").show();
            }else{
                $(this).parents().find(".account_bank-info").hide();
                $(this).parents().find(".account_bank-info #account_bank").val("");
            }

        });
		
        bindEvent();
    }

    /**
     * 绑定事件
     */
    function bindEvent(){
        //保存修改
        $("#content .save-account .save-btn").click(function(){

            var id = $(this).attr('data-account-id');
            var name = $.trim($("#name").val());
            var account = $.trim($("#account").val());
            var account_type = $.trim($(".account_type").val());
            var account_bank = $.trim($("#account_bank").val());

            //发送请求，修改我的个人信息
            $.ajax({
                url:"./index.php?c=base_user&a=addOrUpdateAccount",
                type:"post",
                data:{
                	id:id,
                	name:name,
                	account:account,
                	account_type:account_type,
                	account_bank:account_bank
                },
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
                    	showDialog('#successDialog','保存成功',' ','','1500',function(){window.history.go(-1);})
//                      window.history.go(-1);
                    }else{
                        //注册失败
                    	showDialog('#errorDialog','保存失败',"操作失败，"+json.errorInfo+"！",'');
                    }
                },
                error:function(){
                    //请求失败
                	showDialog('#errorDialog','抱歉！网络异常',"网络异常，请稍后再试！",'');
                }
            });
        });
    }
    init();
});