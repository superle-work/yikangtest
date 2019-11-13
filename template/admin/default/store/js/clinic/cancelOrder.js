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
    }
    /**
     * 获取模糊参数
     */
    function getSelectInfo(){
        var selectInfo = {
            user_name : $('.inner-section .search-param-form #user_name').val().trim(),
            id : $('#id').val().trim(),
            order_num : $('.inner-section .search-param-form #order_num').val().trim(),
            state : $('.inner-section .search-param-form #state').val().trim(),
            from_add_time : $('.inner-section .search-param-form #startTime').val().trim(),
            to_add_time : $('.inner-section .search-param-form #endTime').val().trim()
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
            url:'./admin.php?c=store_clinic&a=pagingCancelOrder',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(json){
                var html ='';
                if(json.errorCode == 0){
                    total_count = json.pageInfo.total_count;
                    total = json.pageInfo.total_page;
                    $("#page-selection").bootpag({total:total,total_count:total_count});//重新计算总页数,总记录数

                    currentPage = json.pageInfo.current_page;
                    var myList = json.orderList;

                    html+='<tr><th class="th1">序号</th><th class="th3">昵称</th><th class="th4">订单号</th><th class="th3">订单总价</th><th class="th3">订单状态</th><th class="th8">核销人员</th><th class="th8">采样时间</th><th class="th8">下单时间</th></tr>';
                    var colspan = $(html).find("th").length;
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var num = (pageIndex-1)*pageSize + i+1;
                        var nick_name = obj.nick_name;     //下单人id
                        var account = obj.account;
                        var order_num = obj.order_num;
                        var total_price = obj.total_price;
                        var user_name = obj.clinic_worker_name;      //诊所工作人员名称
                        var user_id = obj.clinic_worker;             //诊所工作人员id
                        var sample_time = obj.sample_time?obj.sample_time:'---';
                        var add_time = obj.add_time;
                        var oid = obj.id;
                        var state="";
                        if(obj.state==2){
                        	state="<span class='state-2'>待送检</span>";
                        }
                        else if(obj.state==3){
                        	state="<span class='state-3'>送检中</span>";
                        }
                        else if(obj.state==4){
                        	state="<span class='state-4'>检测中</span>";
                        }
                        else if(obj.state==5){
                        	state="<span class='state-5'>已完成</span>";
                        }
                        
                        html+='<tr>'
                                 +'<td>'+num+'</td>'
                                 +'<td><a href="./admin.php?c=base_user&a=userDetail&account='+account+'" class="limit-text">'+nick_name+'</a></td>'
                                 +'<td>'+order_num+'</td>'
                                 +'<td>'+total_price+'</td>'
                                 +'<td>'+state+'</td>'
                                 +'<td><a href="admin.php?c=base_user&a=userDetail&id='+user_id+'">'+user_name+'</a></td>'
                                 +'<td>'+sample_time+'</td>'
                                 +'<td>'+add_time+'</td>'
                              +'</tr>';

                    }

                    if(myList.length == 0){
                        html += '<tr><td colspan="'+colspan+'"><p class="text-danger">暂无数据。</p></td></tr>';
                    }
                    $("#list-table tbody").html(html);
                }else{
                    responseTip(json.errorCode,json.errorInfo,1500);
                }

            },
            error:errorResponse
        });
    }

    init();
});