$(function(){
    var total = 1;//分页总页面数
    var currentPage = 1;//当前页
    var pageSize = pageOption.pageSize;//每页显示的记录数
    var idList = [];//被选中的商品主键集合
   
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
        $(".deletebatch").click(function(){
            var ids = idList.join(",");
            if(ids == ""){
        		$("#myModal .modal-body").html("<p class='text-danger'>您尚未选择要删除的选项，请先选择！</p>");
                $("#myModal").modal('show');
                //定时器，1.5秒后模态框自动关闭
                setTimeout(function(){
                    $("#myModal").modal('hide');
                },1500);
        	}else{
	            myConfirmModal("确定要批量删除分销商申请吗？",function(){
		            $.ajax({
		                url:"./admin.php?c=fen_distributor&a=deleteApplyBatch",
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
	                            render(true,currentPage,pageSize);
		//                          window.location.reload();
		                            //window.location.href = "./admin.php?c=base_user&a=applyList";
		                    }else{
		                        //alert("添加失败，请稍后再试！");
		                        $("#myModal .modal-body").html("<p class='text-danger'>"+json.errorInfo+"</p>");
		                        $("#myModal").modal('show');
		                        //定时器，1.5秒后模态框自动关闭
		                        setTimeout(function(){
		                            $("#myModal").modal('hide');
		                        },1500);
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
        pagination("#page-selection",{total:total},render);

    }
    
    /**
     * 全选
     */
    function selectAll(){
        var boxs = $("input.select-single");//所有商品记录
        //被选中
        if($(this).prop("checked")){
            boxs.prop("checked",true);//复选框全部选中
            boxs.each(function(){
                if(idList.indexOf($(this).val()) < 0){//idList中不包含当前id值，则加入
                    idList.push($(this).val());
                }
            });
        }else{
            //全部取消
            boxs.prop("checked",false);//复选框全部取消选中
            //从idList数组中删除当前id
            boxs.each(function(){
                var index = idList.indexOf($(this).val());
                if(index >= 0){//idList中包含当前id值，则删除
                    idList.splice(index,1);
                }
            });
        }
        $("#ids").val(idList.join(","));//将当前选中的商品主键写入隐藏域gid中
    }
    
    /***
     * 单选事件
     */
    function selectSingle(){
        if($(this).prop("checked")){//单选选中时
            if(idList.indexOf($(this).val()) < 0){//idList中不包含当前id值，则加入
                idList.push($(this).val());
            }
            if($(this).parents("#list-table").find(".select-single").length == $(this).parents("#list-table").find(".select-single:checked").length){
                //所有复选框都选中时，将全选复选框置为选中状态
                $(this).parents("#list-table").find(".select-all").prop("checked",true);
            }
        }else{//单选复选框取消选中时
            //从idList数组中删除当前id
            var index = idList.indexOf($(this).val());
            if(index >= 0){//idList中包含当前id值，则删除
                idList.splice(index,1);
            }
            $(this).parents("#list-table").find(".select-all").prop("checked",false);
        }
        $("#ids").val(idList.join(","));//将当前选中的商品主键写入隐藏域id中
    }
    
    /**
     * 删除单条记录
     */
    function deleteOne(){
	     var id = $(this).attr("data_id");
	     var parent = $(this).parent();
	     var isCheck = parent.prev().attr("ischeck");
	     if(isCheck != 0){
	    	myConfirmModal("确定要删除分销商申请吗？",function(){
	            $.ajax({
	                url:"./admin.php?c=fen_distributor&a=deleteApply",
	                type:"post",
	                data:{"id":id},
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
	                        //alert("添加失败，请稍后再试！");
	                        $("#myModal .modal-body").html("<p class='text-danger'>"+json.errorInfo+"</p>");
	                        $("#myModal").modal('show');
	                        //定时器，1.5秒后模态框自动关闭
	                        setTimeout(function(){
	                            $("#myModal").modal('hide');
	                        },1500);
	                    }
	                },
	                error:errorResponse
	            });
	    	});
	     }else{
	     	$("#myModal .modal-body").html("<p class='text-danger'>很抱歉，尚未审核，不能删除！</p>");
	         $("#myModal").modal('show');
	         //定时器，1.5秒后模态框自动关闭
	         setTimeout(function(){
	             $("#myModal").modal('hide');
	         },1500);
	     }
	 }
	
  //自定义审核确认对话框，返回true或false,通过或不通过审核操作
    function myCheckModal(alertInfo,callback){//参数，当前操作提示文本
        $("#myCheckModal .modal-body .text-danger").html(alertInfo);
        $("#myCheckModal").modal('show');//对话框显现

        //审核通过对话框--通过操作
        $("#myCheckModal .btn-success").one('click',function(){//一次点击
            $("#myCheckModal").modal('hide');//对话框显现
            var info =""
            callback(1,info);//调用回调函数
        });
        //审核不通过对话框--不通过操作
        $("#myCheckModal .btn-error").on('click',function(){//多次点击
        	$("#myCheckModal .modal-body .text-info").css('display','inline');//显示输入框
        	var info = $("#myCheckModal .modal-body .info").focus().val();//获取输入框的值
        	if(info != ""){
        		$("#myCheckModal").modal('hide');//对话框显现
                callback(2,info);//调用回调函数
        	}
        });
    }
    /**
     * 审核判断
     */
    function check(){
    	$("#myCheckModal .modal-body .info").val('');//清空输入框的值
    	$("#myCheckModal .modal-body .text-info").css('display','none');//隐藏输入框
    	var id = $(this).attr("data_id");
//        var isCheck = $(this).attr("data");
        myCheckModal("请注意！审核通过将生成分销商",function(isCheck,info){
	        $.ajax({
	            url:"./admin.php?c=fen_distributor&a=checkApply",
	            type:"post",
	            data:{"id":id,"isCheck":isCheck,"info":info},
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
	                    $("#myModal .modal-body").html("<p class='text-success'><b>恭喜您，操作成功！</b></p>");
	                    $("#myModal").modal('show');
	                    //定时器，1.5秒后模态框自动关闭
	                    setTimeout(function(){
	                        $("#myModal").modal('hide');
	                        var length = $(".inner-section #list-table tbody tr").length - 1;
                            if(currentPage != 1 && length % pageSize == 1){
                                currentPage = currentPage - 1;
                            }
                            render(true,currentPage,pageSize);
//	                      window.location.reload();
	                        //window.location.href = "./admin.php?c=base_user&a=applyList";
	                    },1500);
	                }else{
	                    //alert("添加失败，请稍后再试！");
	                    $("#myModal .modal-body").html("<p class='text-danger'>"+json.errorInfo+"</p>");
	                    $("#myModal").modal('show');
	                    //定时器，1.5秒后模态框自动关闭
	                    setTimeout(function(){
	                        $("#myModal").modal('hide');
	                    },1500);
	                    render(true,currentPage,pageSize);
	                }
	            },
	            error:errorResponse
	        });
        })
    }

    /**
     * 获取模糊参数
     */
    function getSelectInfo(){
        var name = $.trim($("#name").val());
        var phone = $.trim($("#telephone").val());
        var is_check = $("#isCheck").val();
        var from = $('#startTime').val();
        var to = $('#endTime').val();
        var selectInfo = {
            "name":name,
            "phone":phone,
            "is_check":is_check,
            "from":from,
            "to":to
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
            url:'./admin.php?c=fen_distributor&a=pagingApply',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                	
                	total = result.data.pageInfo.total_page;
                    $("#page-selection").bootpag({total:total});//重新计算总页数
                    currentPage = result.data.pageInfo.current_page;
                    var applyList = result.data.dataList;
                    
                    html+='<tr><th class="th1"><input type="checkbox" class="select-all my-icheckbox"></th><th class="th2">序号</th><th class="th4">昵称</th><th class="th3">姓名</th><th class="th4">电话</th><th class="th9">分销级别</th><th class="th5">申请时间</th><th class="th6">审核时间</th><th class="th7">审核状态</th><th class="th8">操作</th></tr>';
                    $(".inner-section #list-table tbody").html(html);
                    var thLength = $("#list-table tr th").length;
                    for(var i = 0; i < applyList.length;i++){
                        var obj = applyList[i];
                        var number = (pageIndex - 1)*pageSize + i + 1;//序号
                        var name = obj.name;
                        var rank = obj.rank+'级';
                        var nick_name = obj.nick_name;
                        var user_id = obj.user_id;
                        var phone = obj.phone?obj.phone:"--";
                        var add_time = obj.add_time?obj.add_time:"--";
                        var check_time = obj.check_time?obj.check_time:"--";
                        var is_check = (obj.is_check == 1)?"审核通过":(obj.is_check == 2?"审核不通过":"未审核");//1男 2女 0未知
                        var is_button_able = (obj.is_check == 0)?"":"disabled";//1男 2女 0未知
                        var is_check_color = (obj.is_check == 1)?"green":(obj.is_check == 2?"#969696":"#C50023");//1男 2女 0未知
                        var id = obj.id;
                        var checked = (idList.indexOf(id) >= 0) ? "checked":"";//判断当前记录先前有没有被选中
                        if(obj.is_check == 1){
                        	html+='<tr>'
                        			+'<td><input type="checkbox my-icheckbox" class="select-single" value="'+id+'" '+checked+'></td>'
                        			+'<td>'+number+'</td>'
                        			+'<td><a href="./admin.php?c=base_user&a=userDetail&id='+user_id+'" class="limitName" title="查看用户详情">'+nick_name+'</a></td>'
                        			+'<td><span class="limitName" title="'+name+'">'+name+'</span></td>'
                        			+'<td>'+phone+'</td>'
                        			+'<td>'+rank+'</td>'
                        			+'<td>'+add_time+'</td>'
                        			+'<td>'+check_time+'</td>'
                        			+'<td ischeck="'+obj.is_check+'" style="color:'+is_check_color+'">'+is_check+'</td>'
                        			+'<td><a class="btn btn-xs btn-primary view" href="./admin.php?c=fen_distributor&a=applyDetail&id='+id+'">查看详情</a><a href="javascript:;" class="btn btn-default btn-xs delete" data_id="'+id+'">删除</a></td>'
                        		+'</tr>';
                        }else if(obj.is_check == 2){
                        	html+='<tr>'
	                    			+'<td><input type="checkbox my-icheckbox" class="select-single" value="'+id+'" '+checked+'></td>'
	                    			+'<td>'+number+'</td>'
	                    			+'<td><a href="./admin.php?c=base_user&a=userDetail&id='+user_id+'" class="limitName" title="查看用户详情">'+nick_name+'</a></td>'
	                    			+'<td><span class="limitName" title="'+name+'">'+name+'</span></td>'
	                    			+'<td>'+phone+'</td>'
	                    			+'<td>'+rank+'</td>'
	                    			+'<td>'+add_time+'</td>'
	                    			+'<td>'+check_time+'</td>'
	                    			+'<td ischeck="'+obj.is_check+'" style="color:'+is_check_color+'">'+is_check+'</td>'
	                    			+'<td><a class="btn btn-xs btn-primary view" href="./admin.php?c=fen_distributor&a=applyDetail&id='+id+'">查看详情</a><a href="javascript:;" class="btn btn-default btn-xs delete" data_id="'+id+'">删除</a></td>'
	                    		+'</tr>';
                        }else{
                        	html+='<tr>'
	                    			+'<td><input type="checkbox" disabled></td>'
	                    			+'<td>'+number+'</td>'
	                    			+'<td><a href="./admin.php?c=baseuser&a=userDetail&id='+user_id+'" class="limitName" title="查看用户详情">'+nick_name+'</a></td>'
	                    			+'<td><span class="limitName" title="'+name+'">'+name+'</span></td>'
	                    			+'<td>'+phone+'</td>'
	                    			+'<td>'+rank+'</td>'
	                    			+'<td>'+add_time+'</td>'
	                    			+'<td>'+check_time+'</td>'
	                    			+'<td ischeck="'+obj.is_check+'" style="color:'+is_check_color+'">'+is_check+'</td>'
	                    			+'<td><a class="btn btn-xs btn-primary view" href="./admin.php?c=fen_distributor&a=applyDetail&id='+id+'">查看详情</a><a class="btn btn-xs btn-danger check" href="javascript:;" data_id="'+id+'"'+is_button_able+'>审核</a></td>'
	                    		+'</tr>';
                        }
                    }
                    if(applyList.length == 0){
                        html += '<tr><td colspan="'+thLength+'"><p class="text-danger">查询结果为空。</p></td></tr>';
                    }
                    $(".inner-section #list-table tbody").html(html);
                    
                  //全选事件
                    myCheck();
                    batchSelect(idList,".inner-section #list-table .select-all",".inner-section #list-table .select-single");
                  //单条删除
                    $(".delete").click(deleteOne);
                  //审核操作
                    $(".check").click(check);
                }else{
                    $("#myModal .modal-body").html("<p class='text-danger'>"+result.errorInfo+"</p>");
                    $("#myModal").modal('show');
                    //定时器，1.5秒后模态框自动关闭
                    setTimeout(function(){
                        $("#myModal").modal('hide');
                    },1500);
                }
            },
            error:errorResponse
        });
       
    }
    init();
});