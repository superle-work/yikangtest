$(function(){
    /**
     * 分页初始条件
     */
    var total = 1;//分页总页面数
    var total_count = 1;//分页总记录数
    var currentPage = 1;//当前页
    var pageSize = pageOption.pageSize;//每页显示的记录数


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
        $('.search-param-form .search-button').click(function() {
            render(true,1,pageSize);
            return false;
        });
        //enter键盘事件
        $(".search-param-form input").keydown(function(event){
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
            name : $('#name').val().trim()
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
            url:'./admin.php?c=store_template&a=pagingProperty',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                    total = result.data.pageInfo.total_page;
                    total_count = result.data.pageInfo.total_count;
                    $("#page-selection").bootpag({total:total,total_count:total_count});//重新计算总页数,总记录数

                    currentPage = result.data.pageInfo.current_page;
                    var myList = result.data.propertyList;

                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var name = obj.name;
                        html+= '<div class="property"><div class="property-info"><div class="name">'+name+'</div><div class="action"><a href="javascript:;" class="btn btn-primary btn-xs look">编辑</a><a href="javascript:;" class="btn btn-danger btn-xs delete">删除</a></div></div></div>';

                    }

                    if(myList.length == 0){
                        html = '<p class="text-danger">暂无数据。</p>';
                        $("#list_container .propertyList").html(html);
                    }else{
                        $("#list_container .propertyList").html(html);
                        //属性查看编辑事件
                        $(".propertyList .property .look").click(function(){
                            var name = $(this).parents(".property-info").find(".name").text();
                            window.location.href = "./admin.php?c=store_template&a=editProperty&name="+name;
                        });
                        //属性删除事件
                        $(".propertyList .property .delete").click(deleteProperty);
                    }

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
    function deleteProperty() {
            var _this = $(this);
            var name = _this.parents(".property-info").find(".name").text();
            myConfirmModal("确定删除属性“"+name+"”吗？",function(){
                $.ajax({
                    url:"./admin.php?c=store_template&a=deleteProperty",
                    type:"post",
                    data:{"name":name},
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
                });
            });
    }
    init();
});