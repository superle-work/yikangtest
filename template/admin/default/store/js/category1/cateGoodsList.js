$(function(){
	/**
     * 分页初始条件
     */
    var total_count = 1;//分页总记录数
    var total = 1;//分页总页面数
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
            name : $('#goodsname').val().trim(),
            recommend:$("#recommend").val(),
            updown : $("#updown").val(),
            id : $('#id').val()
        };
        return selectInfo;
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
            url:'./admin.php?c=store_goods&a=pagingGoods',
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

                    html+='<tr><th class="th1">序号</th><th class="th2">商品名称</th><th class="th7">价格</th><th class="th10">销量</th><th class="th5">排序</th><th class="th6">状态</th><th class="th7">首页推荐</th><th class="th8">添加日期</th></tr>';
                    var colspan = $(html).find("th").length;
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var num = (pageIndex-1)*pageSize + i+1;
                        var name = obj.name;
                        var createtime = obj.add_time;
                        var price = obj.price;
                        var quantity = obj.sale_quantity;
                        var sortnum = obj.sort_num;
                        var updown = obj.updown;//1上架，2下架
                        var recommend = obj.recommend;//0不推荐，1推荐
                        var thumb = obj.thumb;
                        var imgurl = obj.img_url;
                        var gid = obj.id;
                        var cids = obj.cids;
                        var add_time = obj.add_time;

                        html+='<tr>'
                                 +'<td>'+num+'</td>'
                                 +'<td>'+ name +'</td>'
                                 +'<td>'+price+'</td>'
                                 +'<td>'+quantity+'</td>'
                                 +'<td>'+sortnum+'</td>'
                                 +'<td>'+(updown == 1?"上架":"<span style='color:red;'>下架</span>")+'</td>'
                                 +'<td>'+(recommend == 0 ?"不推荐":"<span class='text-primary'>推荐</span>")+'</td>'
                                 +'<td>'+add_time+'</td>'
                               +'</tr>';

                    }

                    if(myList.length == 0){
                        html += '<tr><td colspan="'+colspan+'"><p class="text-danger">暂无数据。</p></td></tr>';
                    }
                    $("#list-table tbody").html(html);
                }else{
                    responseTip(result.errorCode,result.errorInfo,1500);

                }

            },
            error:errorResponse
        });
    }

    init();
});