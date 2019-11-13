/**
 * 佣金提现
 * @since 2015-11-14
 * @author jjhu
 */

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

    	//显示隐藏开户行
    	$("#content .type select").change(function(){
    		var type = $("#content .type select").val().trim();
    		if(type == 2){
    			$("#content .account_bank").show();
    			$("#content .account").show();
    			$("#content .accounts").hide();
    			
    		}else{
    			$("#content .account_bank").hide();
    			$("#content .account").hide();
    			$("#content .accounts").show();
    		}
    	});
    		
    	
        //添加账户
        $(".button .comfirm").click(function(){
        	var type = $("#content .type select").val().trim();
        	var name = $("#content .name input").val().trim();
        	var id=$("#id").val();
        	var utype=$("#type").val();
        	var account = "";
        	var account_bank = "";

        	if(type == 2 ){
        		account = $("#content .account input").val().trim();
        		account_bank = $("#content .account_bank input").val();
        	}else{
        		account = $("#content .accounts input").val().trim();
        	}
        	
        	if(name =="" || name==null){
        		showDialog("#alertDialog","信息不完善","姓名不能为空","&#xe63e;",2000);
                return false;
        	}
        	if(account =="" || account==null){
	        		showDialog("#alertDialog","信息不完善","账号不能为空","&#xe63e;",2000);
	                return false;
	        	}
        	if(type == 2){
    			if(account_bank =="" || account_bank==null){
	        		showDialog("#alertDialog","信息不完善","开户行不能为空","&#xe63e;",2000);
	                return false;
	        	}
    		}
            $.ajax({
                type:"post",
                url:"./index.php?c=fen&a=insertAccount",               				
                data:{
					"account_type":type,
					"name":name,
					"account":account,
					"account_bank":account_bank,
					"tx_uid":id,
					"tx_type":utype,
                },
                dataType:"json",
                beforeSend:function(xhr){
                    //显示“加载中。。。”
                    showDialog('#loadingDialog');
                },
                complete:function(){
                    //隐藏“加载中。。。”
                	hideDialog('#loadingDialog');
                },
                success:function(json,statusText){
                	
                    if(json.errorCode == 0){
                        showDialog('#successDialog','添加成功','账户添加成功','&#xe604;',1000,function(){
                        	window.history.go(-1);
                        });
                    }else{
                        showDialog('#errorDialog','添加失败','账户添加失败','&#xe63e;',2000);
                    }
                },
                error:function(){
                	showDialog('#errorDialog','提示','请求失败，网络异常！','',2000);
                }
            });
        });
    }

    init();
});