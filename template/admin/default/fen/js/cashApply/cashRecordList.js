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
        	$('#distributor_id').val('');
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
        var distributor_name = $.trim($("#distributor_name").val());
        var distributor_id = $('#distributor_id').val();
        var from = $('#startTime').val();
        var to = $('#endTime').val();
        var low_money =$.trim($("#low_money").val());
        var up_money =$.trim($("#up_money").val());
        var selectInfo = {
        	"distributor_id":distributor_id,
            "distributor_name":distributor_name,
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
            url:'./admin.php?c=fen_distributor&a=pagingCashRecord',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                    total = result.data.pageInfo.total_page;
                    $("#page-selection").bootpag({total:total});//重新计算总页数
                    currentPage = result.data.pageInfo.current_page;//获取当前页数
                    var dealList = result.data.dataList;//符合条件的结果集
                    html += '<tr><th class="th1">序号</th><th class="th2">分销商名称</th><th class="th3">提现金额</th><th class="th4">审核人</th><th class="th5">提现时间</th></tr>';
                    $(".inner-section #list-table tbody").html(html);//获取th标签数量
                    var thLength = $("#list-table tr th").length;
                    for(var i = 0; i < dealList.length;i++){
                        var obj = dealList[i];
                        var number = (pageIndex - 1)*pageSize + i + 1;//序号
                        var distributor_name = obj.distributor_name;
                        var money = obj.money;
                        var checker = obj.checker||"--";
                        var add_time = obj.add_time;
                        var distributor_id = obj.distributor_id;
                        var id = obj.id;

                        html+='<tr>'
                        		+'<td>'+number+'</td>'
                        		+'<td><a href="./admin.php?c=fen_distributor&amp;a=editDistributor&amp;id='+distributor_id+'" class="limitName" title="查看分销商详情">'+distributor_name+'</a></td>'
                        		+'<td>'+money+'</td>'
		                        +'<td>'+checker+'</td>'
		                        +'<td>'+add_time+'</td>'
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