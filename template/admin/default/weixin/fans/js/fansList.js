$(function(){
    var total = 1;//分页总页面数
    var total_count = 1;
    var currentPage = 1;//当前页
    var pageSize = pageOption.pageSize;//每页显示的记录数

    function init(){
        myPagination();
        bindEvent();
    }

    function bindEvent(){

    }

    /**
     * 案例分页显示方法
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

        var selectInfo = {

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
            url:'./admin.php?c=weixin_fans&a=pagingFans',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(json){
                if(json.errorCode == 0){
                    var html ='';
                    total = json.data.total_page;

                    $("#page-selection").bootpag({total:total});//重新计算总页数

                    currentPage = pageIndex;
                    var myList = json.data.data;
                    myList = (myList)?myList : [];

                    html+='<tr><th class="th1">序号</th><th class="th2">头像</th><th class="th3">昵称</th><th class="th4">性别</th><th class="th5">分组</th><th class="th6">备注</th><th class="th7">关注时间</th></tr>';
                    var colspan = $(html).find("th").length;
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var number = (pageIndex - 1)*pageSize + i + 1;//序号
                        var head_url = obj.headimgurl;
                        var nickname = obj.nickname;
                        var sex = (obj.sex == 1)?"男":(obj.sex == 2?"女":"未知");//1男 2女 0未知
                        var groupname = obj.groupname;
                        var remark = obj.remark;
                        var subscribe_time = obj.subscribe_time;
                        subscribe_time = new Date(parseInt(subscribe_time) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
                        var openid = obj.openid;
                        html+='<tr><td>'+number+'</td><td><img height="40" src="'+head_url+'"></td><td>'+nickname+'</td><td>'+sex+'</td><td>'+groupname+'</td><td>'+remark+'</td><td>'+subscribe_time+'</td></tr>';

                    }
                    if(myList.length == 0){
                        html += '<tr><td colspan="'+colspan+'"><p class="text-danger">暂无数据。</p></td></tr>';
                    }
                    $(".inner-section #list-table tbody").html(html);
                }else{
                   responseTip(json.errorCode,json.errorInfo,1500);
                }

            },
            error:errorResponse
        });
    }
    init();
});