$(function(){
	 //底部滚动加载参数
    var stop = true;//控制是否出发滚动加载
    /**
     * 初始化
     */
    function init(){
        bindEvent();
        render(true);
    }
    
     /**
     * 绑定事件
     */
    function bindEvent(){   	
    	//滚动实现加载
    	$(window).scroll(function(){
    		if(stop == true){
	    		var scroll_top = $(window).height()+$(window).scrollTop();
	    		if(scroll_top > get_more_top){
	    			//异步请求，渲染记录列表
			        render(true,1);
	    		}
    		}
    	});
    	
    	 //删除账户
        $(" .button .del_account").click(function(){
			if(!confirm("确认删除吗？")){
                return false;
            }
            var ids = [];
            
			 if(!$(".box .tip .select").hasClass("hidden")){
		    		var ids = [];
					$(".box .tip .select").each(function(index){
		                var id = $(this).attr("id");
		                ids.push(id);
		            });
		   }
			 if(ids.length== 0){
			 	showDialog('#alertDialog',' ','请选择要删除的账户！','',1500);
			 	return false;
			 }
            var _this = $(this);
            ids = ids.join(",");
            $.ajax({
                url:"./index.php?c=fen&a=deleteAccount",
                type:"post",
                data:{"ids":ids},
                dataType:"json",
                success:function(json,statusText){
                    if(json.errorCode == 0){
                        window.location.reload();
                    }else{
                        alert(json.errorInfo);
                    }

                },
                error:function(){
                    alert("抱歉，网络异常！");
                }
            });
        });	
    }
    
    /**
     * 获取模糊参数
     */
    function getSelect(){
         var selectInfo = {
         	id:$("#id").val(),
         	type:$("#type").val(),
            pageIndex : $("#content .get-more").attr("data-start"),
            pageSize : $("#content .get-more").attr("data-num"),
        };
        return selectInfo;
    }
    
    
    
     /**
     * 分页动态渲染数据
     * @param async ajax请求是否异步
     * @param pageIndex 当前显示页
     * @param pageSize 每页显示记录数
     */
    function render(async,type){
    	if(type !=1){//点击
			$("#content .get-more").attr('data-start',1);
			//stop = true;
    	}
    	var start = $("#content .get-more").attr("data-start");
    	var num = $("#content .get-more").attr("data-num");
        var selectInfo = getSelect();
    	$.ajax({
            type:'post',
            url:'./index.php?c=fen&a=pagingAccount',
            data:selectInfo,
            beforeSend:function(){
            	stop = false;
            	//$("#content .get-more").text("正在加载中");
            },
            dataType:"json",
            success:function(result){
            	var html ='';
                if(result.errorCode == 0){
            	    var dataList = result.data.dataList;
            	    var html = "";
            	    for(var i = 0; i < dataList.length; i++){
            		    var obj = dataList[i];
            		    var account = obj.account;
            		    var account_type = obj.account_type;
            		    var id = obj.id;
            		    var is_default = obj.is_default;
            		    var account_text = "";
            		    if(account_type == 0){
            		   		account_text ="支付宝";
            		    }else if(account_type == 1){
            		   		account_text ="微信";
            		    }else if(account_type == 2){
            		   		account_text ="银行卡";
            		    }
            		    
            		    var icon_str="";
            		    if(is_default==1){
            		    	icon_str='<i  class="icon iconfont i_cur ">&#xe648;</i>';
            		    }
            		    else{
            		    	icon_str='<i class="icon iconfont ">&#xe623;</i>';
            		    }
            		   
            		   
            		   
            		   html +='<div class="box">'
            		   		+'<div class="tip">'
            		   		+'<span class="ico" data-id="'+id+'">'+icon_str+'</span>'
            		   		+'<span class="account_text">'+account_text+'</span>'
							+'<a href="./index.php?c=fen&a=editAccount&id='+id+'"><span class="account"></span>'
            		   		+'<i class="icon iconfont arrow">&#xe671;</i>'
            		   		+'</a></div>'
            		   		+'</div>'
            		   
            	   	}
            	   	
            	   	if(type == 1){//滚动加载
						$("#content .wrap").append(html);
					}else{
						$("#content .wrap").html(html);
					}
					
                   	if(dataList.length < num || result.data.pageInfo.total_page == 1){//判断数据是否加载完
                   		stop = false;
                	   //$("#content .get-more").text("没有更多咯");
               	   	}else{
               		   get_more_top = $("#content .get-more").offset().top;
               		   $("#content .get-more").attr("data-start",parseInt(start)+1);
               		   stop = true;
               		   $("#content .get-more").text("下拉加载更多");
               	   	}
               	   
               	}else{
					showDialog('#errorDialog',result.errorCode,result.errorInfo,'&#xe63e;',1500);
               	}
            },
            error:function(){
              	showDialog('#errorDialog',' ','请求失败，网络异常！');
            }
        });
    }
    
    //icon选中点击事件
    $(document).on("click",".ico",function(){
    	var uid=$("#id").val();
        var type=$("#type").val();
    	var id=$(this).attr("data-id");
    	if(!$(this).children().hasClass("i_cur")){
    		$(".ico").children().removeClass("i_cur");
    		$(".ico").children().html("&#xe623;");
    		$(this).children().addClass("i_cur");
    		$(this).children().html("&#xe648;");
    		$.ajax({
    			type:"post",
    			url:'index.php?c=fen&a=setDefault',
    			data:{
    				'id':id,
    				'is_default':1,
    				'uid':uid,
    				'type':type,
    			},
    			dataType:"json",
    			success:function(res){
    				if(res.errorCode==0){
						showDialog('#successDialog','设置成功','设置为默认选项成功','&#xe648;',1500,function(){
							location.href="index.php?c=fen&a=withdrawal&id="+uid+"&type="+type;
						});
    				}
    				else{
						showDialog('#errorDialog',res.errorCode,res.errorInfo,'&#xe63e;',1500);
    				}
    			}
    		})
    	}
    })
    
    
   	init(); 
});