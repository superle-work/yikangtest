$(function(){
    var total_count = 1;//分页总记录数
    var total = 1;//分页总页面数
    var currentPage = 1;//当前页
    var pageSize = pageOption.pageSize;//每页显示的记录数
    var idList = [];//批量选择id所存的数组

    /*
     * 初始化
     */
    function init(){
        myPagination();
        bindEvent();
    }

    /*
     * 绑定事件
     */
    function bindEvent(){
        /**
         * 模糊查询事件
         */
        $(".inner-section .search-button").click(function(){
            render(true,1,pageSize);
        });
        //enter事件
        $(".inner-section .search-param-panel input").keydown(function(event){
            event = event ? event:window.event;
            if(event.keyCode == 13){
                render(true,1,pageSize);
            }
        });
        
        //批量删除
        $(".content .right-section .delete-batch").click(function(){
        	var ids = idList.join(',');
            if(ids == ""){
        		$("#myModal .modal-body").html("<p class='text-danger'>您尚未选择要删除的选项，请先选择！</p>");
                $("#myModal").modal('show');
                //定时器，1.5秒后模态框自动关闭
                setTimeout(function(){
                    $("#myModal").modal('hide');
                },1500);
        	}else{
	            myConfirmModal("确定要批量删除通知吗？",function(){
		            $.ajax({
		                url:"./admin.php?c=base_admin&a=deleteMessageBatch",
		                type:"post",
		                data:{"ids":ids},
		                dataType:"json",
		                beforeSend:function(xhr){
		                    //显示“加载中。。。”
		                    $("#loading").modal('show');
		                },
		                complete:function(){
		                    //隐藏“加载中。。。”
		                    $("#loading").modal('hide');
		                },
		                success:function(json,statusText){
		                    if(json.errorCode == 0){
		                    	if(currentPage != 1 && (total_count - idList.length) % pageSize == 0){
	                                currentPage = currentPage - 1;
	                            }
	                            idList = [];//初始化idList的值
	                            render(true,currentPage,pageSize);
		                    }else{
		                        responseTip(1,json.errorInfo);
		                    }
		                },
		                error:errorResponse
		            });
	            });
        	}
        });
    }

    //删除产品
    function deleteMessage(){
        var id = $(this).attr("messageid");
        myConfirmModal("确定删除吗？",function(){
            $.ajax({
                url:"./admin.php?c=base_admin&a=deleteMessageBatch",
                type:"post",
                data:{"ids":id},
                dataType:"json",
                beforeSend:function(xhr){
                    //显示“加载中。。。”
                    $("#loading").modal('show');
                },
                complete:function(){
                    //隐藏“加载中。。。”
                    $("#loading").modal('hide');
                },
                success:function(json,statusText){
                    if(json.errorCode == 0){
                        if(currentPage !=1 && total_count % pageSize == 1){//非首页且末页记录数为1时
                            currentPage = currentPage - 1;
                        }
                        render(true,currentPage,pageSize);
                    }else{
                        responseTip(json.errorCode,json.errorInfo,1500);
                    }
                },
                error:errorResponse
            });
        });

    };
    /**
     * 案例分页显示方法
     */
    function myPagination(){
        render(true,1,pageSize);
        //调用公共分页方法
        pagination("#page-selection",{pageSize:pageSize,total:total},render);
    }

    /**
     * 获取模糊参数
     */
    function getSelectInfo(){
        var title = $(".search-param-panel #title").val();//标题
        var source = $(".search-param-panel #source").val();//来源
        var is_read = $('.search-param-panel #is_read').val();//是否已读
        var type = $.trim($(".search-param-panel #type").val());//类型
        var from_add_time = $(".search-param-panel #startTime").val();
        var to_add_time = $(".search-param-panel #endTime").val();
        var selectInfo = {
            "title":title,
            "source":source,
            "is_read":is_read,
            "type":type,
            "from_add_time":from_add_time,
            "to_add_time":to_add_time
        };
        return selectInfo;
    }
    
    /**
     * 置为已读
     */
    function getRead(){
    	var id = $(this).attr('messageid');
    	$.ajax({
    		async:'async',
    		type:'post',
    		url:'./admin.php?c=base_admin&a=getRead',
    		data:{id:id},
    		dataType:'json',
    		success:function(json){
    			if(json.errorCode == 0){
    				//置换成功
    				render(true,1,pageSize);
    			}else{
    				responseTip(json.errorCode,json.errorInfo,1500);
    			}
    		},
    		//error:errorResponse
    	});
    }
    
    /**
     * 分页动态渲染数据
     * @param async ajax请求是否异步
     * @param pageIndex 当前显示页
     * @param pageSize 每页显示记录数
     */
    function render(async,pageIndex,pageSize){
        //var selectInfo = getSelectInfo();
        var title = $(".search-param-panel #title").val();//标题
        var source = $.trim($(".search-param-panel #source").val());//来源
        var is_read = $('.search-param-panel #is_read').val();//是否已读
        var type = $.trim($(".search-param-panel #type").val());//类型
        var from_add_time = $(".search-param-panel #startTime").val();
        var to_add_time = $(".search-param-panel #endTime").val();
        $.ajax({
            async:async,
            type:'post',
            url:'./admin.php?c=base_admin&a=pagingMessage',
            data:{
            	pageIndex:pageIndex,
            	pageSize:pageSize,
            	"title":title,
                "source":source,
                "is_read":is_read,
                "type":type,
                "from_add_time":from_add_time,
                "to_add_time":to_add_time
            	},
            dataType:'json',
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                    total_count = result.data.pageInfo.total_count;
                    total = result.data.pageInfo.total_page;
                    $("#page-selection").bootpag({total:total,total_count:total_count});//重新计算总页数,总记录数

                    currentPage = result.data.pageInfo.current_page;
                    var messageList = result.data.dataList;
                    
                    html+='<tr><th width="40px"><input type="checkbox" class="select-all my-icheckbox"></th><th width="50px">序号</th><th width="120px">消息标题</th><th>消息内容</th><th width="80px">是否已读</th><th width="80px">消息类型</th><th width="100px">来源</th><th width="150px">添加时间</th><th width="200px">操作</th></tr>';
                    var colspan = $(html).find("th").length;
                    for(var i = 0; i < messageList.length;i++){
                    	var num = (pageIndex-1)*pageSize + i+1;
                    	var obj = messageList[i];
                    	var id = obj.id;
                        var title = obj.title;
                        var content = obj.content;
                        var is_read = obj.is_read==0?'<td style="color:red;">未读</td>':'<td style="color:green;">已读</td>';
                        var type = obj.type;
                        var add_time = obj.add_time;
                        var source = obj.source;
                        var for_read = obj.is_read==0?'<a href="javascript:;" class="edit btn btn-primary btn-xs" messageid="'+id+'">置为已读</a>':'';
                        
                        if(type == 0){
                        	type = '未知';
                        }else if(type == 1){
                        	type = '订单通知';
                        }else if(type == 2){
                        	type = '分销通知';
                        }
                        
                        var checked = (idList.indexOf(id) >= 0) ? "checked":"";//判断当前记录先前有没有被选中
                        html+='<tr><td><input type="checkbox" class="select-single my-icheckbox" value="'+id+'" '+checked+'>'
                        + '</td><td>'+ num +'</td>'
                        + '<td>'+ title +'</td>'
                        + '<td><span class="limit-text" data-toggle="popover" data-trigger="click" data-placement="top" title data-original-title="'+ content +'">'+ content +'</span></td>'
                        + is_read
                        + '<td>'+ type +'</td>'
                        + '<td>'+ source +'</td>'
                        + '<td>'+ add_time +'</td>'
                        + '<td>'
                        + for_read
                        + '<a href="javascript:;" messageid="'+id+'" class="delete btn btn-default btn-xs">删除</a></td></tr>';
                    }
                    if(messageList.length == 0){
                        html += '<tr><td colspan="'+colspan+'"><p class="text-danger">暂无数据。</p></td></tr>';
                    }
                    $(".inner-section #list-table tbody").html(html);
                    myCheck();
                    batchSelect(idList,".inner-section #list-table .select-all",".inner-section #list-table .select-single");
                    $(".inner-section table .delete").click(deleteMessage);
                    $(".inner-section table .edit").click(getRead);
                    $("[data-toggle='popover']").popover();

                }else{
                    responseTip(result.errorCode,result.errorInfo,1500);
                }

            },
            error:errorResponse
        });
    }
    init();
});