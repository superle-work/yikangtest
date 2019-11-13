$(function(){
    var total = 1;//分页总页面数
    var currentPage = 1;//当前页
    var pageSize = pageOption.pageSize;//每页显示的记录数
    
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
     * 获取模糊参数
     */
    function getSelectInfo(){
    	 var nick_name = $.trim($("#nick_name").val());
         var order_num = $.trim($("#order_num").val());
         var from = $('#startTime').val();
         var to = $('#endTime').val();
         var low_money =$.trim($("#low_money").val());
         var up_money =$.trim($("#up_money").val());
         var selectInfo = {
             "nick_name":nick_name,
             "from":from,
             "to":to,
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
            url:'./admin.php?c=fen_distributor&a=pagingDealAccount',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                    total = result.data.pageInfo.total_page;
                    $("#page-selection").bootpag({total:total});//重新计算总页数
                    currentPage = result.data.pageInfo.current_page;//获得当前页数
                    var dealList = result.data.dataList;//获得满足条件的结果集
                    html += '<tr><th class="th1">排序</th><th class="th1">排行</th><th class="th3">用户昵称</th><th class="th5">交易次数</th><th class="th6">交易总额</th><th class="th7">操作</th></tr>';
                    $(".inner-section #list-table tbody").html(html);
                    var thLength = $("#list-table tr th").length;//th标签数量
                    for(var i = 0; i < dealList.length;i++){
                        var obj = dealList[i];
                        var number = (pageIndex - 1)*pageSize + i + 1;//序号
                        var rownum = obj.rownum;//排序
                        var account = obj.account?obj.account:"--";
                        var nick_name = obj.nick_name?obj.nick_name:"--";
                        var times = obj.times+'次';
                        var total_money = obj.total_money+'元';
                        html+='<tr>'
                        		+'<td>'+number+'</td>'
                        		+'<td>'+rownum+'</td>'
	                        	+'<td><a href="./admin.php?c=base_user&a=userDetail&amp;account='+account+'" class="limitName" title="查看用户详情">'+nick_name+'</a></td>'
	                    		+'<td>'+times+'</a></td>'
	                    		+'<td>'+total_money+'</a></td>'
	                        	+'<td><a class="btn btn-xs btn-primary view" href="./admin.php?c=fen_distributor&amp;a=dealList&amp;account='+account+'">交易信息</a><a class="btn btn-xs btn-primary view" href="./admin.php?c=base_user&amp;a=userDetail&amp;account='+account+'">用户信息</a></td>'
		                     +'</tr>';
                    }
                    if(dealList.length == 0){
                        html += '<tr><td colspan="'+thLength+'"><p class="text-danger">查询结果为空。</p></td></tr>';
                    }
                    $(".inner-section #list-table tbody").html(html);
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