$(function(){
	/**
     * 分页初始条件
     */
    var total = 1;//分页总页面数
	var total_count = 0;
    var currentPage = 1;//当前页
    var pageSize = 5;//每页显示的记录数
    var idList = [];//被选中的商品主键集合
    var uid = $("#myForm #uid").val();

    // if(uid !=""){
    //     idList = uid.split(",");
    // }

	
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
        $("#myForm #uid").val(idList.join(","));//将当前选中的商品主键写入隐藏域uid中
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
        $("#myForm #uid").val(idList.join(","));//将当前选中的商品主键写入隐藏域uid中
    }

    /**
     * 获取模糊参数
     */
    function getSelectInfo(){
        var selectInfo = {
            nick_name : $('#myContent #nick_name').val().trim(),
            phone : $('#myContent #phone').val().trim(),
            user_type:'0',    //如果要不显示已选的，值为0
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
        // 被选中放再字段里

        var ids = [];
        $("#bindSave").click(function(){

            $("input[class='select-single']:checked").each(function(i){

                var result= $.inArray($(this).val(), ids);
                if (result==-1) {
                ids=($(this).val());

                $(".userid").val(ids);

                }
            })

        });
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
            url:'./admin.php?c=base_admin&a=pagingUser',
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

                    html+='<tr><th class="th0"><input  title="全选" type="checkbox" class="select-all"></th><th class="th1">序号</th><th class="th3">头像</th><th class="th2">昵称</th><th class="th2">手机号</th><th class="th5">真实姓名</th><th class="th2">邮箱</th><th class="th2">地址</th></tr>';
                    var colspan = $(html).find("th").length;
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var num = (pageIndex-1)*pageSize + i+1;
                        var nick_name = obj.nick_name;
                        var head_img_url = obj.head_img_url;
                        var phone = obj.phone?obj.phone:'--';
                        var address = obj.address?obj.address:'--';
                        var email = obj.email?obj.email:'--';
                        var name = obj.name?obj.name:"--";
                        var uid = obj.id;
                        var checked = (idList.indexOf(uid) >= 0) ? "checked":"";//判断当前记录先前有没有被选中

                        html+='<tr>'
                                 +'<td><input  class="select-single" type="checkbox" value="'+uid+'" '+checked+'></td>'
                                 +'<td><span>'+num+'</span></td>'
                                 +'<td><img height="40" src="'+head_img_url+'"></td>'
                                 +'<td>'+ nick_name +'</td>'
                                 +'<td>'+phone+'</td>'
                                 +'<td>'+name+'</td>'
								 +'<td>'+email+'</td>'
                                 +'<td>'+address+'</td>'
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