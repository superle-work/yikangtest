$(function(){
	/*
     * 初始化
     */
    function init(){
        bindEvent();
    }

    /**
     * 绑定事件
     */
    function bindEvent(){
    	//删除
    	$(".delete").click(function(){
    		var id=$(this).attr("data-id");
    		var obj=this;
    		if(id){
    			$.ajax({
    				type:'post',
    				url:'index.php?c=store&a=deleteMember',
    				data:{
    					'id':id,
    				},
    				dataType:'json',
    				success:function(res){
    					if(res.errorCode == 0){
	                    	$(obj).parents(".member-list").remove();
	                    	$(obj).parents(".member-list").prev().remove();
		                }else{
		                	showDialog('#errorDialog','',json.errorInfo)
		                }
    				},
    				error:function(){
		            	showDialog("#errorDialog",'','请求失败，网络异常！','',1500);
		            }
    			})
    		}
    	})
    	
    	//设置为默认选择
    	$(".setDefault").click(function(){
    		var id=$(this).attr("data-id");
    		var obj=this;
    		if($(this).find("i").hasClass("cur")){
    			$(this).find("i").removeClass('cur');
    			$(this).find("i").html("&#xe623;");
    			$.ajax({
    				type:'post',
    				url:'index.php?c=store&a=changeMemState',
    				data:{
    					'id':id,
    					'state':0,
    				},
    				dataType:'json',
    				success:function(res){
    					if(res.errorCode == 0){
		                    //showDialog("#successDialog",'恭喜您','操作成功','&#xe604;',1000,function(){
		                    	if(window.localStorage.url){
		                    		location.href=window.localStorage.url;
		                    	}
		                    	else{
		                    		window.location.reload();
		                    	}
		                    //});
		                }else{
		                	showDialog('#errorDialog','',json.errorInfo)
		                }
    				},
    				error:function(){
		            	showDialog("#errorDialog",'','请求失败，网络异常！','',1500);
		            }
    			})
    		}
    		else{
    			$(this).parents(".member-area").find("i").each(function(){
    				if($(this).hasClass('cur')){
    					$(this).removeClass("cur");
    					$(this).html("&#xe623;");
    				}
    			})
    			$(obj).find("i").addClass('cur');
    			$(obj).find("i").html("&#xe648;");
    			$.ajax({
    				type:'post',
    				url:'index.php?c=store&a=changeMemState',
    				data:{
    					'id':id,
    					'state':1,
    				},
    				dataType:'json',
    				success:function(res){
    					if(res.errorCode == 0){
		                    //showDialog("#successDialog",'恭喜您','操作成功','&#xe604;',1000,function(){
		                    	if(window.localStorage.url){
		                    		location.href=window.localStorage.url;
		                    	}
		                    	else{
		                    		window.location.reload();
		                    	}
		                    //});
		                }else{
		                	showDialog('#errorDialog','',json.errorInfo)
		                }
    				},
    				error:function(){
		            	showDialog("#errorDialog",'','请求失败，网络异常！','',1500);
		            }
    			})
    		}
    	})
    }
    init();
});