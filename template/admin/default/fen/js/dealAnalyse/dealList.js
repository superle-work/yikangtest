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
        	$('#account').val('');
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
//      var account = $('#account').val();
        var from = $('#startTime').val();
        var to = $('#endTime').val();
        var low_money =$.trim($("#low_money").val());
        var up_money =$.trim($("#up_money").val());
        var selectInfo = {
            "distributor_name":distributor_name,
            "order_num":order_num,
            "from_add_time":from,
            "to_add_time":to,
            "from_money":low_money,
            "to_money":up_money
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
            url:'./admin.php?c=fen_distributor&a=pagingDeal',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                    total = result.data.pageInfo.total_page;
                    $("#page-selection").bootpag({total:total});//重新计算总页数
                    currentPage = result.data.pageInfo.current_page;//获取当前页数
                    var dealList = result.data.dataList;//符合条件的结果集
                    html += '<tr><th class="th1">序号</th><th class="th2">订单号</th><th class="th3">分销商名称</th><th class="th6">昵称</th><th class="th4">订单总价</th><th class="th4">所得佣金</th><th class="th5">添加时间</th></tr>';
                    $(".inner-section #list-table tbody").html(html);//获取th标签数量
                    var thLength = $("#list-table tr th").length;
                    for(var i = 0; i < dealList.length;i++){
                        var obj = dealList[i];
                        var number = (pageIndex - 1)*pageSize + i + 1;//序号
                        var oid = obj.oid;
                        var nick_name = obj.nick_name;     //用户昵称
                        var order_num = obj.order_num?obj.order_num:"--";
                        var money = obj.money?obj.money:"--";    //所得佣金
                        var total_price = obj.total_price?obj.total_price:"--";   //订单总价
                        var add_time = obj.add_time?obj.add_time:"--";
                        var distributor_id = obj.distributor_id;    //分销商id
                        var user_id = obj.user_id;        //用户id
                        var id = obj.id;      //记录id
                        var distributor_name = "";
                        if(obj.distributor_name){
                            distributor_name = '<a href="./admin.php?c=fen_distributor&a=distributorInfo&id='+distributor_id+'" class="limitName" title="查看分销商详情">'+obj.distributor_name+'</a>';
                        }else{
                            distributor_name = "总部";
                        }
                        
                        html+='<tr>'
                        		+'<td>'+number+'</td>'
                        		+'<td>'+order_num+'</a></td>'
                        		+'<td>'+distributor_name+'</td>'
                        		+'<td><a href="./admin.php?c=base_user&a=userDetail&id='+user_id+'" class="limitName" title="查看用户详情">'+nick_name+'</a></td>'
                        		+'<td>'+total_price+'</td>'
                        		+'<td>'+money+'</td>'
		                        +'<td>'+add_time+'</td>'
		                     +'</tr>';

                    }
                    if(dealList.length == 0){
                        html += '<tr><td colspan="'+thLength+'"><p class="text-danger">查询结果为空。</p></td></tr>';
                    }
                    $(".inner-section #list-table tbody").html(html);
                }else{
                   responseTip(1,result.errorInfo);
                }

            },
            error:errorResponse
        });
    }
    
    
    init();
});