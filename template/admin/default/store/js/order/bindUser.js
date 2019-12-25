$(function(){
	/**
     * 分页初始条件
     */
    var totals = 1;//分页总页面数
	var totals_count = 0;
    var currentPages = 1;//当前页

    var pageSizes = 5;//每页显示的记录数
    var idLists = [];//被选中的商品主键集合
    // var uid = $("#myForm #uid").val();

    // if(uid !=""){
    //     idLists = uid.split(",");
    // }

	
    /**
     * 页面初始化
     */
    function init(){

        myPaginations();
        bindEvent();
    }

    /**
     * 事件绑定
     */
    function bindEvent(){
    	//根据条件查询商品信息
    	$('#myContent .search-button').click(function() {
    		renders(true,1,pageSizes);
            return false;
		});
        //enter键盘事件
        $("#myContent .search-param-form input").keydown(function(event){
            event = event ? event: window.event;
            if(event.keyCode == 13){
                renders(true,1,pageSizes);
                return false;
            }
        });


    }

    /**
     * 全选
    //  */
    function selectAll(){
        var boxs = $("#bindLabelDialog  input.select-single");//所有商品记录
        //被选中
        if($(this).prop("checked")){
            boxs.prop("checked",true);//复选框全部选中
            boxs.each(function(){
                if(idLists.indexOf($(this).val()) < 0){//idLists中不包含当前id值，则加入
                    idLists.push($(this).val());
                }
            });
        }else{
            //全部取消
            boxs.prop("checked",false);//复选框全部取消选中
            //从idLists数组中删除当前id
            boxs.each(function(){
                var index = idLists.indexOf($(this).val());
                if(index >= 0){//idLists中包含当前id值，则删除
                    idLists.splice(index,1);
                }
            });
        }
        $("#myForm #uid").val(idLists.join(","));//将当前选中的商品主键写入隐藏域uid中
    }

    /***
     * 单选事件
     */
    function selectSingle(){
        if($(this).prop("checked")){//单选选中时
            if(idLists.indexOf($(this).val()) < 0){//idLists中不包含当前id值，则加入
                idLists.push($(this).val());
            }
            if($(this).parents("#list-table").find(".select-single").length == $(this).parents("#list-table").find(".select-single:checked").length){
                //所有复选框都选中时，将全选复选框置为选中状态
                $(this).parents("#list-table").find(".select-all").prop("checked",true);
            }

        }else{//单选复选框取消选中时
            //从idLists数组中删除当前id
            var index = idLists.indexOf($(this).val());
            if(index >= 0){//idLists中包含当前id值，则删除
                idLists.splice(index,1);
            }
            $(this).parents("#list-table").find(".select-all").prop("checked",false);
        }
        $("#myForm #uid").val(idLists.join(","));//将当前选中的商品主键写入隐藏域uid中
    }

    /**
     * 获取模糊参数
     */
    function getSelectInfo(){
        var selectInfo = {
            nick_name : $('#myContent #nick_name').val(),
            phone : $('#myContent #phone').val(),
            user_type:'0',    //如果要不显示已选的，值为0
        };
        return selectInfo;
    }
    /**
     * 分页显示方法
     */
    function myPaginations(){
        renders(true,1,pageSizes);
        //调用公共分页方法
        paginations("#myContent #page-selections",{total:totals,pageSize:pageSizes},renders);

    }
    // 被选中在打印机  

        var ids = [];
        $("#bindSave").click(function(){

            $("input[class='select-single']:checked").each(function(i){

                var result= $.inArray($(this).val(), ids);
                if (result==-1) {
                ids.push($(this).val());
                }
            })
            // console.log(ids);
            $.ajax({
                type:'post',
                url:'./admin.php?c=store_order&a=printer',
                data:{
                    id:printerid,
                    ids:ids,
                },
                success:function(res){
                    // console.log(res)
                    
                }
            });
        });

    /**
     * 分页动态渲染数据
     * @param async ajax请求是否异步
     * @param pageIndex 当前显示页
     * @param pageSizes 每页显示记录数
     */
    function renders(async,pageIndex,pageSizes){
        var selectInfo = getSelectInfo();

        selectInfo.pageIndex = pageIndex;
        selectInfo.pageSize = pageSizes;
        $.ajax({
            async:async,
            type:'post',
            url:'./admin.php?c=base_user&a=pagingprinter',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(result){

                var html ='';
                if(result.errorCode == 0){
                    totals_count = result.data.pageInfo.total_count;

                    totals = result.data.pageInfo.total_page;

                    $("#page-selections").bootpag({total:totals,total_count:totals_count});//重新计算总页数,总记录数

                    currentPages = result.data.pageInfo.current_page;

                    var myList = result.data.dataList;

                    html+='<tr><th class="th0">选择</th><th class="th1">序号</th><th class="th2">打印机编号</th><th class="th2">省</th><th class="th5">市</th><th class="th2">县</th>';
                    var colspan = $(html).find("th").length;
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var num = (pageIndex-1)*pageSizes + i+1;
                        var code = obj.num;
                        var province = obj.province;
                        var city = obj.city?obj.city:'--';
                        var area = obj.area?obj.area:'--';
                        var uid = obj.id;
                        var checked = (idLists.indexOf(uid) >= 0) ? "checked":"";//判断当前记录先前有没有被选中

                        html+='<tr>'
                                 // +'<td><input  type="radio" data-id="'+uid+'" ></td>'
                                 +'<td><input  class="select-single" type="checkbox" value="'+code+'" '+checked+'></td>'
                                 +'<td><span>'+num+'</span></td>'
                                 +'<td>'+code+'</td>'
                                 +'<td>'+ province +'</td>'
                                 +'<td>'+city+'</td>'
								 +'<td>'+area+'</td>'
                               +'</tr>';

                    }

                    if(myList.length == 0){
                        html += '<tr><td colspan="'+colspan+'"><p class="text-danger">暂无数据。</p></td></tr>';
                    }
                    $("#list-tables tbody").html(html);
                    //全选事件
                    $("#bindLabelDialog #list-table .select-all").click(selectAll);
                    $("#bindLabelDialog  .select-single").click(selectSingle);
                }else{
                    $("#list-tables tbody").html(result.errorInfo);
                }

            },
            error:function(){
                $("#list-tables tbody").html("很抱歉，请求失败，网络异常！");
            }
        });
    }


    init();
});