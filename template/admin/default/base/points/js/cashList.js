$(function(){
	var total_count = 1;//总记录数
    var total = 1;//分页总页面数
    var currentPage = 1;//当前页
    var pageSize = pageOption.pageSize;//每页显示的记录数
    var idList = [];//批量选择id所存的数组
    
    var isDelete = true;//isOperExist("delete");//删除权限 true：有，false：无
    if(!isDelete){
        //隐藏添加按钮
        $("list-title .delete-batch").hide();
    }
    
    function init(){
        bindEvent();
    }

    function bindEvent(){
    	
        myPagination();
        /**
         * 模糊查询事件
         *
         */
        $(".search-button").click(function(){
        	render(true,1,pageSize);
        });
        //enter事件
        $(".search-param-panel input").keydown(function(event){
            event = event ? event:window.event;
            if(event.keyCode == 13){
            	render(true,1,pageSize);
            }
        });
 
        //批量删除
        $(".delete-batch").click(function(){
        	var ids = idList.join(',');
            if(ids == ""){
                responseTip(1,"您尚未选择要删除的选项，请先选择！",1500);
        	}else{
	            myConfirmModal("确定要批量删除提现记录吗？",function(){
		            $.ajax({
		                url:"./admin.php?c=base_user&a=deleteCash",
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
		                    	var length = $(".inner-section #list-table tbody tr").length - idList.length;
	                            if(currentPage != 1 && length % pageSize == 1){
	                                currentPage = currentPage - 1;
	                            }
	                            idList = [];//初始化idList的值
	                            render(true,currentPage,pageSize);
		//                          window.location.reload();
		                            //window.location.href = "./admin.php?c=userManage&a=list";
		                    }else{
		                    	responseTip(json.errorCode,json.errorInfo,1500);
		                    }
		                },
		                error:errorResponse
		            });
	            });
        	}
        });
    }

    /**
     * 分页显示方法
     */
    function myPagination(){
        render(true,1,pageSize);
        //调用公共分页方法
        pagination("#page-selection",{total:total,pageSize:pageSize},render);

    }
    
    
    /**
     * 删除单条记录
     */
    function deleteOne(){
	     var ids = $(this).attr("data_id");
	     var parent = $(this).parent();
    	myConfirmModal("确定要删除该提现记录吗？",function(){
            $.ajax({
                url:"./admin.php?c=base_user&a=deleteCash",
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
                    	var length = $(".inner-section #list-table tbody tr").length - 1;
                        if(currentPage !=1 && length % pageSize == 1){
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
	 }
	
    /**
     * 获取模糊参数
     */
    function getSelectInfo(){
        var nickname = $.trim($("#nickname").val());
        var type = $("#type").val();
        var from_add_time = $('#startTime').val();
        var to_add_time = $('#endTime').val();
        var selectInfo = {
            "nick_name":nickname,
            "type":type,
            "from_add_time":from_add_time,
            "to_add_time":to_add_time
        };
        return selectInfo;
    }
    
    /**
     * 分页动态渲染数据
     * @param async ajax请求是否异步
     * @param pageIndex 当前显示页
     * @param pageSize 每页显示记录数
     */
    function render(async,pageIndex,pageSize){
        var selectInfo = getSelectInfo();
        selectInfo.pageIndex = pageIndex;
        selectInfo.pageSize = pageSize;
        $.ajax({
            async:async,
            type:'post',
            url:'./admin.php?c=base_user&a=pagingCash',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                	total_count = result.data.pageInfo.total_count;
                	total = result.data.pageInfo.total_page;
                    $("#page-selection").bootpag({total:total,total_count:total_count});//重新计算总页数
                    currentPage = result.data.pageInfo.current_page;
                    var list = result.data.dataList;
                    html+='<tr><th class="th1"><input type="checkbox" class="select-all my-icheckbox"></th><th class="th2">序号</th><th class="th4">提现人</th><th class="th5">提现积分</th><th class="th3">提现金额</th><th class="th7">提现时间</th><th class="th2">提现模块</th><th class="th8">操作</th></tr>';
                    $(".inner-section #list-table tbody").html(html);
                    var thLength = $("#list-table tr th").length;
                    for(var i = 0; i < list.length;i++){
                        var obj = list[i];
                        var number = (pageIndex - 1)*pageSize + i + 1;//序号
                        var nick_name = obj.nick_name ? obj.nick_name : '--';
                        var points = obj.points ? obj.points : '--';
                        var money = obj.money ? obj.money : "--";
                        var add_time = obj.add_time?obj.add_time:'--';
                        var type = obj.type;
                        if(type == 'store'){
                        	type = '百货商城';
                        }else if(type == 'dining'){
                        	type = '微订餐';
                        }else if(type == 'hotel'){
                        	type = '微酒店';
                        }else if(type == 'rob'){
                        	type = '抢购商城';
                        }else if(type == 'group'){
                        	type = '拼团商城';
                        }else if(type == 'bargain'){
                        	type = '砍价商城';
                        }else{
                        	type = '--';
                        }
                        var id = obj.id;
                        var checked = (idList.indexOf(id) >= 0) ? "checked":"";//判断当前记录先前有没有被选中
                    	html+='<tr>'
                    			+'<td><input type="checkbox" class="select-single my-icheckbox" value="'+id+'" '+checked+'></td>'
                    			+'<td>'+number+'</td>'
                    			+'<td>'+nick_name+'</td>'
                    			+'<td>'+points+'</td>'
                    			+'<td>'+money+'</td>'
                    			+'<td>'+add_time+'</td>'
                    			+'<td>'+type+'</td>'
                    			+'<td><a class="btn btn-xs btn-primary" href="./admin.php?c=base_user&a=cashDetail&id='+id+'">详情</a>'+(isDelete ?'<a href="javascript:;" class="btn btn-default btn-xs delete" data_id="'+id+'">删除</a>':'')+'</td>'
                    		+'</tr>';
                    }
                    if(list.length == 0){
                        html += '<tr><td colspan="'+thLength+'"><p class="text-danger">查询结果为空。</p></td></tr>';
                    }
                    $(".inner-section #list-table tbody").html(html);
                  //全选事件
                    myCheck();
                    batchSelect(idList,".content .inner-section #list-table .select-all",".inner-section #list-table .select-single");
                    //修改删除
                    $(".content .inner-section #list-table .delete").click(deleteOne);
                }else{
                	responseTip(result.errorCode,result.errorInfo,1500);
                }
            },
            error:errorResponse
        });
    }
    init();
});