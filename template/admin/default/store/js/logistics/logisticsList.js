$(function(){
	/**
     * 分页初始条件
     */
    var total_count = 1;//分页总记录数
    var total = 1;//分页总页面数
    var currentPage = 1;//当前页
    var pageSize = pageOption.pageSize;//每页显示的记录数
    var idList = [];//批量选择id所存的数组
	
    /**
     * 页面初始化
     */
    function init(){
        myPagination();
        bindEvent();
    }

    /**
     * 事件绑定
     */
    function bindEvent(){
    	//根据条件查询商品信息
    	$('.inner-section .search-button').click(function() {
            render(true,1,pageSize);
            return false;
		});
        //enter键盘事件
        $(".inner-section .search-param-form input").keydown(function(event){
            event = event ? event: window.event;
            if(event.keyCode == 13){
                render(true,1,pageSize);
                return false;
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
	            myConfirmModal("确定要批量删除工作人员吗？",function(){
		            $.ajax({
		                url:"./admin.php?c=store_logistics&a=batchDelete",
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
    /**
     * 获取模糊参数
     */
    function getSelectInfo(){
        var selectInfo = {
            nick_name : $('.inner-section .search-param-form #nick_name').val(),
            phone : $('.inner-section .search-param-form #phone').val(),
            from_add_time : $('.inner-section .search-param-form #startTime').val(),
            to_add_time : $('.inner-section .search-param-form #endTime').val()
        };
        return selectInfo;
    }
    /**
     * 分页显示方法
     */
    function myPagination(){
        render(true,1,pageSize);
        //调用公共分页方法
        pagination("#page-selection",{pageSize:pageSize,total:total},render);

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
            url:'./admin.php?c=store_logistics&a=pagingLogistics',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(json){
                var html ='';
                if(json.errorCode == 0){
                    total_count = json.data.pageInfo.total_count;
                    total = json.data.pageInfo.total_page;
                    $("#page-selection").bootpag({total:total,total_count:total_count});//重新计算总页数,总记录数

                    currentPage = json.data.pageInfo.current_page;
                    var myList = json.data.dataList;

                    html+='<tr><th class="th1"><input type="checkbox" class="select-all my-icheckbox"></th><th class="th1">序号</th><th class="th3">头像</th><th class="th8">工作人员名称</th><th class="th2">电话</th><th class="th2">创建日期</th><th class="th2">操作</th></tr>';
                    var colspan = $(html).find("th").length;
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var num = (pageIndex-1)*pageSize + i+1;
                        var nick_name = obj.nick_name;
                        var createtime = obj.add_time;
                        var phone = obj.phone;
                        var head_img = obj.head_img;
                        var lid = obj.id;
                        var uid = obj.user_id;
                        var checked = (idList.indexOf(lid) >= 0) ? "checked":"";//判断当前记录先前有没有被选中

                        html+='<tr>'
                        		+'<td><input type="checkbox" class="select-single my-icheckbox" value="'+lid+'" '+checked+'></td>'
                                 +'<td>'+num+'</td>'
                                 +'<td><img height="50" src="'+head_img+'"></td>'
                                 +'<td><a href="admin.php?c=base_user&a=userDetail&id='+uid+'" class="limit-text">'+ nick_name +'</a></td>'
                                 +'<td>'+phone+'</td>'
                                 +'<td>'+createtime+'</td>'
                                 +'<td>'
                                 	+'<a href="admin.php?c=store_logistics&a=cancelOrder&uid='+uid+'" class="btn btn-success btn-xs">查看核销订单</a>'
                                    +'<a class="btn btn-default btn-xs delete" id = "'+lid+'">删除</a>'
                                 +'</td>'
                               +'</tr>';

                    }

                    if(myList.length == 0){
                        html += '<tr><td colspan="'+colspan+'"><p class="text-danger">暂无数据。</p></td></tr>';
                    }
                    $("#list-table tbody").html(html);
                    //全选事件
                    myCheck();
                    batchSelect(idList,".inner-section #list-table .select-all",".inner-section #list-table .select-single");
                    //单个操作
                    $("#list-table .delete").click(deleteClinic);
                }else{
                    responseTip(json.errorCode,json.errorInfo,1500);
                }

            },
            error:errorResponse
        });
    }

	/**
     * 真删
     */
	function deleteClinic() {
        var id = $(this).attr("id");
        myConfirmModal("确定要删除该工作人员吗？",function(){
            $.ajax(
                {
                    type:"post",
                    url:"./admin.php?c=store_logistics&a=deleteLogistics",
                    data:{'id':id},
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
                }
            );
        });
	}
	
    init();
});