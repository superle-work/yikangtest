$(function(){
    var total = 1;//分页总页面数
    var currentPage = 1;//当前页
    var pageSize = pageOption.pageSize;//每页显示的记录数
    var idList = [];//被选中的商品主键集合
    
    function init(){
        bindEvent();
    }
	
	var CAN_CLICK = true;//是否可以点击
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
            var ids = idList.join(',');
            if(ids == ""){
        		$("#myModal .modal-body").html("<p class='text-danger'>您尚未选择要删除的选项，请先选择！</p>");
                $("#myModal").modal('show');
                //定时器，1.5秒后模态框自动关闭
                setTimeout(function(){
                    $("#myModal").modal('hide');
                },1500);
        	}else{
	            myConfirmModal("确定要批量删除分销商提现申请吗？",function(){
		            $.ajax({
		                url:"./admin.php?c=store_cash_apply&a=deleteCashApplyBatch",
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
		                            //window.location.href = "./admin.php?c=userManage&a=applyList";
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
     * 删除单条记录
     */
    function deleteOne(){
	     var id = $(this).attr("data_id");
	     var parent = $(this).parent();
	     var isCheck = parent.prev().attr("ischeck");
	     if(isCheck != 0){
	    	myConfirmModal("确定要删除分销商提现申请吗？",function(){
	            $.ajax({
	                url:"./admin.php?c=store_cash_apply&a=deleteCashApply",
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
	                        render(false,currentPage,pageSize);
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
    
//  自定义修改打款状态对话框，返回true或false,改变打款状态（完成）
     function cashStaticModal(alertInfo,callback){//参数，当前操作提示文本
        $("#cashStaticModal .modal-body .text-danger").html(alertInfo);
        $("#cashStaticModal").modal('show');//对话框显现
        $("#cashStaticModal .cash").unbind();
        $("#cashStaticModal .cashed").unbind();
        
    	//打款中
        $("#cashStaticModal .cashing").one('click',function(){
            $("#cashStaticModal").modal('hide');//对话框显现
            callback(2);//调用回调函数
        });
        //打款成功
        $("#cashStaticModal .cashed").one('click',function(){
            $("#cashStaticModal").modal('hide');//对话框显现
            callback(3);//调用回调函数
        });
    }
    
    /**
     * 更改打款状态（完成）
     */
    function editStatic(){
    	var id = $(this).attr("data_id");
    	
    	//判断打款中是否显示
    	var cashing_display = ($(this).attr("cash_static") == 1) ? 'inline' :'none';
    	$("#cashStaticModal .modal-body .cashing").css('display',cashing_display);
    	
    	cashStaticModal("请选择打款状态",function(cash_static){
            $.ajax({
                url:"./admin.php?c=store_cash_apply&a=editCashStaticApply",
                type:"post",
                data:{"id":id,"cash_static":cash_static},
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
    	
        
    }
    
    /**
     * 操作修改状态(未用到)
     */
    function updateStatic(cash_static){
        $.ajax({
            url:"./admin.php?c=store_cash_apply&a=editCashStaticApply",
            type:"post",
            data:{"id":id,"cash_static":cash_static},
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
    }
    
    /**
     * 打钱（未用到）
     */
    function payCash(){
    	if(CAN_CLICK == false){
    		return false;
    	}
    	var id = $(this).attr("data_id");
        $.ajax({
            url:"./admin.php?c=store_cash_apply&a=sendRedPacket",
            type:"post",
            data:{"id":id},
            dataType:"json",
            beforeSend:function(xhr){
            	CAN_CLICK = false;
                //显示“加载中。。。”
                $("#loading").modal('show');
            },
            complete:function(){
            	CAN_CLICK = true;
                //隐藏“加载中。。。”
                $("#loading").modal('hide');
            },
            success:function(json,statusText){
                if(json.errorCode == 0){
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
    }
    
  //自定义审核确认对话框，返回true或false,通过或不通过审核操作(完成)
    function myCheckModal(alertInfo,callback){//参数，当前操作提示文本
    	 $("#myCheckModal .modal-body .text-danger").html(alertInfo);
         $("#myCheckModal").modal('show');//对话框显现
         $("#myCheckModal .btn-success").unbind();
         $("#myCheckModal .btn-error").unbind();
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
     * 审核判断(完成)
     */
    function check(){
    	$("#myCheckModal .modal-body .info").val('');//清空输入框的值
    	if($(this).attr("data-model") == 1){//判断付款模式（线下付款）
    		var noticeText = "请注意,审核通过表示自动发零钱!";
    	}else{//什么通过线下手动打款
    		var noticeText = "请注意,审核通过表示即将打款!";
    	}
		$("#myCheckModal .modal-body .text-info").css('display','none');//隐藏输入框
    	var id = $(this).attr("data_id");
        myCheckModal(noticeText,function(isCheck,info){
        	if(CAN_CLICK == false){return false;}
	        $.ajax({
	            url:"./admin.php?c=store_cash_apply&a=checkCashApply",
	            type:"post",
	            data:{"id":id,"isCheck":isCheck,"info":info},
	            dataType:"json",
	            beforeSend:function(xhr){
	            	CAN_CLICK = false;
	                //显示“加载中。。。”
	                $("#loading").modal('show');
	            },
	            complete:function(){
	            	CAN_CLICK = true;
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
	                    },1500);
	                }else{
	                    //alert("添加失败，请稍后再试！");
	                    $("#myModal .modal-body").html("<p class='text-danger'>"+json.errorInfo+"</p>");
	                    $("#myModal").modal('show');
	                    //定时器，1.5秒后模态框自动关闭
	                    setTimeout(function(){
	                        $("#myModal").modal('hide');
	                        render(true,currentPage,pageSize);
	                    },3000);
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
        var tx_type = $.trim($("#tx_type").val());
        var is_check = $("#isCheck").val();
        var from = $('#startTime').val();
        var to = $('#endTime').val();
        var cash_static = $("#cash_static").val();
        var low_money = $.trim($("#low_money").val());
        var up_money = $.trim($("#up_money").val());
        var selectInfo = {
            "tx_type":tx_type,
            "is_check":is_check,
            "from_add_time":from,
            "to_add_time":to,
            "cash_static":cash_static,
            "low_money":low_money,
            "up_money":up_money
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
            url:'./admin.php?c=store_cash_apply&a=pagingCashApply',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                	total = result.data.pageInfo.total_page;
                    $("#page-selection").bootpag({total:total});//重新计算总页数
                    currentPage = result.data.pageInfo.current_page;
                    var applyList = result.data.dataList;
                    
                    html+='<tr><th class="th1"><input type="checkbox" class="my-icheckbox select-all"></th><th class="th2">序号</th><th class="th7">提现申请人</th><th class="th6">被提现账户名称</th><th class="th4">佣金总量</th><th class="th5">提现金额</th><th class="th8">申请时间</th><th class="th9">审核时间</th><th class="th10">审核状态</th><th class="th11">提现状态</th><th class="th12">审核人</th><th class="th13">操作</th></tr>';
                    //$(".inner-section #list-table tbody").html(html);
                    var thLength = 12;
                    for(var i = 0; i < applyList.length;i++){
                        var obj = applyList[i];
                        var number = (pageIndex - 1)*pageSize + i + 1;//序号
                        var id = obj.id;
                        var distributor_id = obj.distributor_id;
                        var account_id = obj.account_id;
                        
                        //提现者名称
                        var distributor_name = obj.distributor_name ? obj.distributor_name : "--";
                        //被提现账户名称
                        var name=obj.name;
                        
                        var money = obj.money ? obj.money : "--";
                        var total_money = obj.total_money ? obj.total_money : "--";
                        var checker = obj.checker ? obj.checker : "--";
                        var add_time = obj.add_time ? obj.add_time : "--";
                        var check_time = obj.check_time ? obj.check_time : "--";
                        var model = obj.model;
                        var error_info = obj.error_info;
                        var cash_static = (obj.cash_static == 0) ? "<span style='color:#969696'>未打款</span>" : (obj.cash_static == 1 ? "<span class='text-warning'>即将打款</span>" :(obj.cash_static == 4 ? "<span style='color:#C50023'>打款失败</span>" :(obj.cash_static == 2?"<span style='color:blue'>打款中</span>": "<span style='color:green'>打款成功</span>")));
                        var state = obj.cash_static;
                        var is_check = obj.is_check;
                        var is_check_text = (obj.is_check == 1) ? "审核通过" : (obj.is_check == 2 ? "审核不通过" : "未审核");//1审核通过 2审核不通过 0未审核
                        var is_check_color = (obj.is_check == 1) ? "green" : (obj.is_check == 2 ? "#969696" : "#C50023");//1绿色 2灰色 0红色
                        var checked = (idList.indexOf(id) >= 0)  ? "checked" : "";//判断当前记录先前有没有被选中
//                        var static_display = (obj.cash_static != 1 && obj.cash_static != 2) ? 'none' : 'inline';//是否显示打款状态按钮
                        var viewAccount = "";//查看账户
                        var operateText = "";//总的操作
                        var select_able = "disabled";//复选是否禁用
                        if(model == 0){//1：零钱模式  0：线下打款
                        	viewAccount = '<a class="btn btn-xs btn-primary view" href="./admin.php?c=store_cash_apply&a=cashAccountDetail&account_id='+account_id+'">查看账户</a>';
                        }
                        //is_check 0未审核1审核通过2审核不通过
                        //state 打款状态0未打款1即将打款2打款中3打款成功（4打款失败，再次打款，零钱状态下）
                        if(is_check == 0 ){//未审核
                        	operateText = viewAccount + '<a class="btn btn-xs btn-danger check" href="javascript:;" data-model="'+model+'" data_id="'+id+'">审核</a>';
                        	select_able = ' class="my-icheckbox select-single" ';
                        }else if(is_check == 2 || state == 3){//审核不通过 或者 打款成功
                        	operateText = viewAccount + '<a href="javascript:;" class="btn btn-default btn-xs delete" data_id="'+id+'">删除</a>';
                        	select_able = ' class="my-icheckbox select-single" ';
                        }else if(is_check == 1 && state == 4){//审核通过 同时 打款失败
                        	operateText =viewAccount + '<a class="btn btn-xs btn-danger pay-cash" href="javascript:;"  data_id="'+id+'" >重发零钱</a><a href="javascript:;" class="btn btn-default btn-xs delete" data_id="'+id+'">删除</a>';
                        	select_able = ' class="my-icheckbox select-single" ';
                        }else{
                        	if(model == 0){//线下打款
                        		operateText =viewAccount + '<a class="btn btn-xs btn-danger cash-static" href="javascript:;"  data_id="'+id+'"  cash_static="'+state+'">打款状态</a>';	
                        	}
//                        	select_able = ' class="my-icheckbox" ';
                        }
	                	html+='<tr>'
	                			+'<td><input type="checkbox"  '+select_able+' value="'+id+'" '+checked+'></td>'
	                			+'<td>'+number+'</td>'
	                			+'<td><font class="limitName" color="blue">'+distributor_name+'</font></td>'
	                			+'<td style="color:red;">'+name+'</td>'
	                			+'<td>'+total_money+'</td>'
	                			+'<td>'+money+'</td>'
	                			+'<td>'+add_time+'</td>'
	                			+'<td>'+check_time+'</td>'
	                			+'<td ischeck="'+is_check+'" style="color:'+is_check_color+'"'+(error_info?' title="'+error_info+'"':'')+' >'+is_check_text+'</td>'
	                			+'<td>'+cash_static+'</td>'
	                			+'<td>'+checker+'</td>'
	                			+'<td>'+operateText+'</td>'
	            			+'</tr>';
                    }
                    if(applyList.length == 0){
                        html += '<tr><td colspan="'+thLength+'"><p class="text-danger">查询结果为空。</p></td></tr>';
                    }
                    $(".inner-section #list-table tbody").html(html);
                   //打款成功隐藏打款状态
                  //全选事件
                    myCheck();
                    batchSelect(idList,".inner-section #list-table .select-all",".inner-section #list-table .select-single");
//                  //全选事件
//                    $(".inner-section #list-table .select-all").click(selectAll);
//                    $(".inner-section #list-table .select-single").click(selectSingle);
                  //单条删除
                    $(".inner-section #list-table .delete").click(deleteOne);
                  //审核操作
                    $(".inner-section #list-table .check").click(check);
                  //修改提现状态操作
                    $(".inner-section #list-table .cash-static").click(editStatic);
                    //重发零钱
                    $(".inner-section #list-table .pay-cash").click(payCash);
                }else{
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
       
    }
    init();
});