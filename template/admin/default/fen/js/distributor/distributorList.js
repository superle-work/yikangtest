$(function(){
    var total = 1;//分页总页面数
    var currentPage = 1;//当前页
    var pageSize = pageOption.pageSize;//每页显示的记录数
    var idList = [];//被选中的商品主键集合
    
    function init(){
        bindEvent();
    }

    function bindEvent(){
    	//分页显示方法
        myPagination();
        /**
         * 模糊查询事件
         *
         */
        $(".search-button").click(function(){
            myPagination();
        });
        
        //enter事件
        $(".search-param-panel input").keydown(function(event){
            event = event ? event:window.event;//浏览器兼容性判断
            if(event.keyCode == 13){
                myPagination();
            }
        });
        
        //改变状态有效性
        $(".inner-section #list-table").on('click',".update-use",function(){
        	var _this = $(this);
        	var is_use = _this.attr("data-state");
        	var id = _this.attr("data-id")
        	$.ajax({
                url:"./admin.php?c=fen_distributor&a=updateDistributorStatic",
                type:"post",
                data:{"id":id,is_use:is_use},
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
                    	responseTip(1,json.errorInfo);
                    }
                },
                error:errorResponse
            });
        });
        
        //批量删除
        $(".deletebatch").click(function(){
        	var ids = $("#ids").val();
        	if(ids == ""){
        		$("#myModal .modal-body").html("<p class='text-danger'>您尚未选择要删除的选项，请先选择！</p>");
                $("#myModal").modal('show');
                //定时器，1.5秒后模态框自动关闭
                setTimeout(function(){
                    $("#myModal").modal('hide');
                },1500);
        	}else{
	        	myConfirmModal("确定要批量删除经销商吗？",function(){
		            $.ajax({
		                url:"./admin.php?c=fen_distributor&a=deleteDistributorBatch",
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
		                        if(currentPage !=1 && length % pageSize == 1){
		                            currentPage = currentPage - 1;
		                        }
		                        render(true,currentPage,pageSize);
		//                          window.location.reload();
		                        //window.location.href = "./admin.php?c=userManage&a=applyList";
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

    /**
     * 分页显示方法
     */
    function myPagination(){
        render(true,1,pageSize);
        //调用公共分页方法
        pagination("#page-selection",{total:total,pageSize:pageSize},render);

    }
    
    //单条删除
    function deleteOne(){
        var id = $(this).attr("data_id");
        myConfirmModal("确定要删除分销商吗？",function(){
	        $.ajax({
	            url:"./admin.php?c=fen_distributor&a=deleteDistributor",
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
	            success:function(json,jsonText){
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
        $("#ids").val(idList.join(","));//将当前选中的商品主键写入隐藏域ids中
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
     * 获取模糊参数
     */
    function getSelectInfo(){
        var name = $.trim($("#name").val());
        var phone = $.trim($("#telephone").val());
        //var rank = $.trim($("#rank").val());
//      var level = $(".search-param-panel #level").val();
        var from = $('#startTime').val();
        var to = $('#endTime').val();
        var low_fee =$.trim($("#low_fee").val());
        var up_fee =$.trim($("#up_fee").val());
        var is_use = $.trim($("#is_use").val());
        var selectInfo = {
            "name":name,
            "phone":phone,
            "is_use":is_use,
            //"level":level,
            "from_add_time":from,
            "to_add_time":to,
            "from_total_fee":low_fee,
            "to_total_fee":up_fee
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
            url:'./admin.php?c=fen_distributor&a=pagingDistributor',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                    total = result.data.pageInfo.total_page;
                    $("#page-selection").bootpag({total:total});//重新计算总页数
                    currentPage = result.data.pageInfo.current_page;//当前页码
                    var applyList = result.data.dataList;//满足条件的结果集
                    
                    html+='<tr><th class="th2">序号</th><th class="th3">分销商名称</th>'
                    +'<th class="th7">电话</th><th class="th5">累计佣金</th>'
                    +'<th class="th5">可提佣金</th><th class="th5">冻结佣金</th>'
                    //+'<th class="th2">身份</th>'
                    +'<th class="th2">状态</th><th class="th7">添加时间</th><th class="th8">操作</th></tr>';
                    $(".inner-section #list-table tbody").html(html);
                    
                    var thLength = $("#list-table tr th").length;//th标签的数量
                    
                    for(var i = 0; i < applyList.length;i++){
                        var obj = applyList[i];
                        var number = (pageIndex - 1)*pageSize + i + 1;//序号
                        var name = obj.name;
                        
                        var level = obj.level;//分销商等级 0下线，1分销商
                        if(level == 0){
                        	level = '<span class="text-danger">下线<span>';
                        }else if(level == 1){
                        	level = '<span class="text-success">分销商<span>';
                        }
                        
                        var user_id = obj.user_id;
                        var nick_name = obj.nick_name?obj.nick_name:"--";
                        var phone = obj.phone?obj.phone:"--";
                        var total_fee = obj.total_fee?obj.total_fee:"--";
                        var my_fee = obj.my_fee?obj.my_fee:"--";
                        //var sec_fee = obj.sec_fee?obj.sec_fee:"--";
                        //var thr_fee = obj.thr_fee?obj.thr_fee:"--";
                        //var total_sales_fee = obj.total_sales_fee || '--';
                        
                        var is_use = obj.is_use == 1 ?'<span class="text-success">有效<span>' :'<span class="text-danger">无效<span>';
                        
                        //var count_fee = parseFloat(obj.total_fee - sec_fee - thr_fee).toFixed(2);
                        //var fir_fee = (count_fee >= 0)?count_fee:'--';
                        var add_time = obj.add_time?obj.add_time:"--";
                        var freeze_fee = obj.freeze_fee || '--';
                        //var is_employee = (obj.is_employee == 1)?"是":"否";//1是  0否
                        var rank = obj.rank;
                        var id = obj.id;
                        var use_btn = obj.is_use == 1?'<a data-id="'+id+'" data-state="0" class="btn btn-xs btn-danger update-use">停用</a>' : '<a data-id="'+id+'" data-state="1" class="btn btn-xs btn-success update-use">启用</a>';
                        var checked = (idList.indexOf(id) >= 0) ? "checked":"";//判断当前记录先前有没有被选中

                        html+='<tr>'
                        		//+'<td class="checkid"><input class="select-single" type="checkbox" value="'+id+'" '+checked+'></td>'
                        		+'<td>'+number+'</td>'
                        		+'<td><a class="limitName" href="./admin.php?c=fen_distributor&a=distributorInfo&id='+id+'" title="查看分销商详情">'+name+'</></td>'
                        		+'<td>'+phone+'</td>'
                        		//+'<td>'+total_sales_fee+'</td>'
                        		+'<td>'+total_fee+'</td>'
                        		+'<td>'+my_fee+'</td>'
                        		+'<td>'+freeze_fee+'</td>'
                        		//+'<td>'+rank+'级</td>'
                        		//+'<td>'+level+'</td>'
                        		+'<td>'+is_use+'</td>'
		                        +'<td>'+add_time+'</td>'
		                        +'<td>'+use_btn+'<a href="./admin.php?c=fen_distributor&a=UpDownDistributorList&id='+id+'" class="btn btn-xs btn-primary search-parent">查看上下级</a>'
		                        //+'<a class="btn btn-xs btn-primary update" href="./admin.php?c=fen_distributor&a=editDistributor&id='+id+'" data_id="'+id+'" data="1">编辑</a><a href="./admin.php?c=fen_distributor&a=cashRecordList&distributor_id='+id+'" class="btn btn-xs btn-primary search-cash-record">提现记录</a>'
		                        +'<a href="./admin.php?c=fen_distributor&a=feeFromRecord&distributor_id='+id+'" class="btn btn-xs btn-primary search-cash-record">佣金来源</a>'
		                     +'</td></tr>';

                    }
                    if(applyList.length == 0){
                    	total = 1;
                    	
                        html += '<tr><td colspan="'+thLength+'"><p class="text-danger">查询结果为空。</p></td></tr>';
                    }
                    $(".inner-section #list-table tbody").html(html);
                    
                  //全选事件
                    $(".select-all").click(selectAll);
                    $(".select-single").click(selectSingle);
                  //单条删除
                    $(".delete").click(deleteOne);
                  
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