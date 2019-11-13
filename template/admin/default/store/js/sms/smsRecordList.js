$(function(){
    var total_count = 1;//分页总记录数
    var total = 1;//分页总页面数
    var currentPage = 1;//当前页
    var pageSize = pageOption.pageSize;//每页显示的记录数

    function init(){

        myPagination();
        bindEvent();
    }

    function bindEvent(){
        /**
         * 模糊查询事件
         */
        $(".search-area .search-btn").click(function(){
            render(true,1,pageSize);
        });
        //enter事件
        $(".search-area input").keydown(function(event){
            event = event ? event:window.event;
            if(event.keyCode == 13){
                render(true,1,pageSize);
            }
        });
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
     * 获取模糊参数
     */
    function getSelectInfo(){
        var type = $(".search-area .sms_type").val();
        var verify_result = $(".search-area .verify_result").val();
        var send_phone = $(".search-area .phone").val().trim();
        var selectInfo = {
            "type":type,
            "verify_result":verify_result,
            "send_phone":send_phone
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
            url:'./admin.php?c=m_smsManage&a=pagingRecord',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                    total = result.data.pageInfo.total_page;
                    total_count = result.data.pageInfo.total_count;
                    $("#page-selection").bootpag({total:total,total_count:total_count});//重新计算总页数,总记录数

                    currentPage = result.data.pageInfo.current_page;
                    var myList = result.data.dataList;

                    html+='<tr><th>手机号</th><th>短信类型</th><th>验证码验证结果</th><th>短信内容</th><th>创建时间</th></tr>';
                    var colspan = $(html).find("th").length;
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var type = obj.type;
                        if(type == 0){
                            type = "注册验证码";
                        }else if(type == 1){
                            type = "订单支付成功通知商家";
                        }
                        var result = obj.verify_result;
                        if(obj.type == 0 && result == 0){
                            result = "<p class='text-warning'>未验证</p>";
                        }else if(obj.type == 0 && result == 1){
                            result = "<p class='text-success'>验证成功</p>";
                        }else if(obj.type == 0 && result == 2){
                            result = "<p class='text-danger'>验证失败</p>";
                        }else{
                            result = "--"
                        }
                        html+='<tr><td>'+obj.send_phone+'</td><td>'+type+'</td><td>'+result+'</td><td>'+obj.content+'</td><td>'+obj.add_time+'</td></tr>';

                    }
                    if(myList.length == 0){
                        html += '<tr><td colspan="'+colspan+'"><p class="text-danger">暂无数据。</p></td></tr>';
                    }
                    $(".inner-section table tbody").html(html);

                }else{
                    responseTip(json.errorCode,json.errorInfo,1500);
                }

            },
            error:errorResponse
        });
    }
    init();
});