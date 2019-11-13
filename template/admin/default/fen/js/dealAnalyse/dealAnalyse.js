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
        var distributor_name = $.trim($("#distributor_name").val());
        var order_num = $.trim($("#order_num").val());
        var from = $('#startTime').val();
        var to = $('#endTime').val();
        var low_money =$.trim($("#low_money").val());
        var up_money =$.trim($("#up_money").val());
        var selectInfo = {
            "name":distributor_name,
            "from_add_time":from,
            "to_add_time":to,
            "from_total_sales_fee":low_money,
            "to_total_sales_fee":up_money
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
            url:'./admin.php?c=fen_distributor&a=pagingDealAnalyse',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                    total = result.data.pageInfo.total_page;
                    $("#page-selection").bootpag({total:total});//重新计算总页数
                    currentPage = result.data.pageInfo.current_page;//获得当前页数
                    var dealList = result.data.dataList;//获得满足条件的结果集
                    html += '<tr><th class="th1">序号</th><th class="th1">排行</th><th class="th3">分销商名称</th><th class="th2">销售总额</th><th class="th4">下一级人数</th><th class="th5">下一级销售额</th><th class="th4">下二级人数</th><th class="th5">下二级销售额</th><th class="th4">下三级人数</th><th class="th5">下三级销售额</th><th class="th6">加入时间</th><th class="th2">操作</th></tr>';
                    $(".inner-section #list-table tbody").html(html);
                    var thLength = $("#list-table tr th").length;//th标签数量
                    for(var i = 0; i < dealList.length;i++){
                        var obj = dealList[i];
                        var number = (pageIndex - 1)*pageSize + i + 1;
                        var rownum = obj.rownum;//名次
                        var id = obj.id;
                        var distributor_name = "";
                        var name = '<a href="./admin.php?c=fen_distributor&a=distributorInfo&id='+id+'" class="limitName" title="查看分销商详情">'+obj.name+'</a>';
                        var money = obj.total_sales_fee?obj.total_sales_fee:"0.00";

                        var account = obj.account;
                        html+='<tr>'
                        		+'<td>'+number+'</td>'
                        		+'<td><span class="text-info">'+rownum+'</span></td>'
                        		+'<td>'+name+'</td>'
                        		+'<td>'+money+'</td>'
                        		+'<td><span class="text-success">'+obj.ch_num+'</span></td>'
                        		+'<td><span class="text-danger">'+obj.ch_fee+'</span></td>'
                        		+'<td><span class="text-success">'+obj.ch_ch_num+'</span></td>'
                        		+'<td><span class="text-danger">'+obj.ch_ch_fee+'</span></td>'
                        		+'<td><span class="text-success">'+obj.ch_ch_ch_num+'</span></td>'
                        		+'<td><span class="text-danger">'+obj.ch_ch_ch_fee+'</span></td>'
                        		+'<td>'+obj.add_time+'</td>'
                        		+'<td><a class="btn btn-xs btn-primary view" href="./admin.php?c=fen_distributor&a=dealAnalyseDetail&did='+id+'">报表分析</a></td>'
		                     +'</tr>';

                    }
                    if(dealList.length == 0){
                        html += '<tr><td colspan="'+thLength+'"><p class="text-danger">查询结果为空。</p></td></tr>';
                    }
                    $(".inner-section #list-table tbody").html(html);
                }else{
                    responseTip(1,result.errorInfo)
                }

            },
            error:errorResponse
        });
    }
    init();
});