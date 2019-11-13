$(function(){
	/**
     * 分页初始条件
     */
    var total = 1;//分页总页面数
	var total_count = 0;
    var currentPage = 1;//当前页
    var pageSize = 5;//每页显示的记录数
    var idList = [];//被选中的商品主键集合
    var gids = $("#myForm #gids").val();
    if(gids !=""){
        idList = gids.split(",");
    }

	
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
    	$('#myContent .search-button').click(function() {
    		render(true,1,pageSize);
            return false;
		});
        //enter键盘事件
        $("#myContent .search-param-form input").keydown(function(event){
            event = event ? event: window.event;
            if(event.keyCode == 13){
                render(true,1,pageSize);
                return false;
            }
        });


    }

    /**
     * 全选
     */
    function selectAll(){
        var boxs = $("#bindLabelDialog  input.select-single");//所有商品记录
        //被选中
        if($(this).prop("checked")){
            boxs.prop("checked",true);//复选框全部选中
            boxs.each(function(){
                if(idList.indexOf($(this).val()) < 0){//idList中不包含当前id值，则加入
                    idList.push($(this).val());
                }
            });
        }else{
            //全部取消
            boxs.prop("checked",false);//复选框全部取消选中
            //从idList数组中删除当前id
            boxs.each(function(){
                var index = idList.indexOf($(this).val());
                if(index >= 0){//idList中包含当前id值，则删除
                    idList.splice(index,1);
                }
            });
        }
        $("#myForm #gids").val(idList.join(","));//将当前选中的商品主键写入隐藏域gid中
    }

    /***
     * 单选事件
     */
    function selectSingle(){
        if($(this).prop("checked")){//单选选中时
            if(idList.indexOf($(this).val()) < 0){//idList中不包含当前id值，则加入
                idList.push($(this).val());
            }
            if($(this).parents("#list-table").find(".select-single").length == $(this).parents("#list-table").find(".select-single:checked").length){
                //所有复选框都选中时，将全选复选框置为选中状态
                $(this).parents("#list-table").find(".select-all").prop("checked",true);
            }

        }else{//单选复选框取消选中时
            //从idList数组中删除当前id
            var index = idList.indexOf($(this).val());
            if(index >= 0){//idList中包含当前id值，则删除
                idList.splice(index,1);
            }
            $(this).parents("#list-table").find(".select-all").prop("checked",false);
        }
        $("#myForm #gids").val(idList.join(","));//将当前选中的商品主键写入隐藏域gid中
    }

    /**
     * 获取模糊参数
     */
    function getSelectInfo(){
        var selectInfo = {
            name : $('#myContent #goodsname').val().trim(),
            recommend:$("#myContent #recommend").val(),
            updown : $("#myContent #updown").val()
        };
        return selectInfo;
    }
    /**
     * 分页显示方法
     */
    function myPagination(){
        render(true,1,pageSize);
        //调用公共分页方法
        pagination("#myContent #page-selection",{total:total,pageSize:pageSize},render);

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
                    total_count = result.data.pageInfo.total_count;
                    total = result.data.pageInfo.total_page;
                    $("#page-selection").bootpag({total:total,total_count:total_count});//重新计算总页数,总记录数

                    currentPage = result.data.pageInfo.current_page;
                    var myList = result.data.dataList;

                    html+='<tr><th class="th0"><input  title="全选" type="checkbox" class="select-all"></th><th class="th1">序号</th><th class="th3">缩略图</th><th class="th2">商品名称</th><th class="th4">标准价格</th><th class="th5">销量</th><th class="th7">排序</th><th class="th6">描述</th></tr>';
                    var colspan = $(html).find("th").length;
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var num = (pageIndex-1)*pageSize + i+1;
                        var name = obj.name;
                        var price = obj.price;
                        var quantity = obj.sale_quantity;
                        var thumb = obj.thumb;
                        var imgurl = obj.img_url;
                        var desc = obj.simple_desc?obj.simple_desc:"--";
                        var gid = obj.id;
                        var cids = obj.cids;
						var sortnum = obj.sort_num;
                        var checked = (idList.indexOf(gid) >= 0) ? "checked":"";//判断当前记录先前有没有被选中

                        html+='<tr>'
                                 +'<td><input  class="select-single" type="checkbox" value="'+gid+'" '+checked+'></td>'
                                 +'<td><span>'+num+'</span></td>'
                                 +'<td><img height="40" src="'+thumb+'"></td>'
                                 +'<td>'+ name +'</td>'
                                 +'<td>'+price+'</td>'
                                 +'<td>'+quantity+'</td>'
								 +'<td>'+sortnum+'</td>'
                                 +'<td>'+desc+'</td>'
                               +'</tr>';

                    }

                    if(myList.length == 0){
                        html += '<tr><td colspan="'+colspan+'"><p class="text-danger">暂无数据。</p></td></tr>';
                    }
                    $("#list-table tbody").html(html);
                    //全选事件
                    $("#bindLabelDialog #list-table .select-all").click(selectAll);
                    $("#bindLabelDialog  .select-single").click(selectSingle);
                }else{
                    $("#list-table tbody").html(result.errorInfo);
                }

            },
            error:function(){
                $("#list-table tbody").html("很抱歉，请求失败，网络异常！");
            }
        });
    }


    init();
});