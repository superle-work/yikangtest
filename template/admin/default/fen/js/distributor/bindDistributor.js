$(function(){
	/**
     * 分页初始条件
     */
    var total = 1;//分页总页面数
    var currentPage = 1;//当前页
    var pageSize = 5;//每页显示的记录数
    
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
    	//根据条件查询分销商信息
    	$('#myContent .search-button').click(function() {
    		myPagination();
            return false;
		});
        //enter键盘事件
        $("#myContent .search-param-form input").keydown(function(event){
            event = event ? event: window.event;
            if(event.keyCode == 13){
                myPagination();
                return false;
            }
        });


    }

    /***
     * 单击radio事件
     */
    function selectOne(){
		if($(this).attr('checked')){
			$(this).removeAttr('checked');
			$("#id").attr('value','');
			$("#idName").html('');
			var id = $(".select-one:checked").attr('value');
			var name = $(".select-one:checked").attr('data');
		}else{
			$(this).attr('checked','checked');
			var id = $(".select-one:checked").attr('value');
			var name = $(".select-one:checked").attr('data');
			$("#id").attr('value',id);
			$("#idName").html(name);
		}
    }

    /**
     * 获取模糊参数
     */
    function getSelectInfo(){
        var selectInfo = {
            name : $('#distributorName').val().trim(),
            rank : $("#rank").val()
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
            url:'./admin.php?c=fen_distributor&a=pagingDistributor',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                    total = result.data.pageInfo.total_page;
                    $("#myContent #page-selection").bootpag({total:total});//重新计算总页数
                    currentPage = result.data.pageInfo.current_page;
                    var myList = result.data.dataList;
                    if(myList.length > 0){
                        html+='<tr><th class="th0">选择</th><th class="th1">序号</th><th class="th2">分销商名称</th><th class="th3">电话</th><th class="th4">分销商级别</th></tr>';
                    }
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var num = (pageIndex-1)*pageSize + i+1;
                        var name = obj.name;
                        var phone = obj.phone;
                        var rank = (obj.rank == 1)?"一级":(obj.rank == 2?"二级":"三级");
                        var id = obj.id;

                        if(obj.rank != 3){
	                        html+='<tr>'
	                                 +'<td><input  class="select-one" type="radio" name="select-one" data="'+name+'" value="'+id+'"></td>'
	                                 +'<td><span>'+num+'</span></td>'
	                                 +'<td><span class="limitName" title="'+name+'">'+ name +'</span></td>'
	                                 +'<td>'+phone+'</td>'
	                                 +'<td>'+rank+'</td>'
	                               +'</tr>';
                        }else{
                        	html+='<tr>'
                                +'<td><input type="radio" name="select-one" disabled value="'+id+'"></td>'
                                +'<td><span>'+num+'</span></td>'
                                +'<td><span class="limitName" title="'+name+'">'+ name +'</span></td>'
                                +'<td>'+phone+'</td>'
                                +'<td>'+rank+'</td>'
                              +'</tr>';
                        }
                    }

                    if(myList.length == 0){
                        html = '<p class="text-danger">查询结果为空。</p>';
                    }
                    $("#list-table tbody").html(html);
                    //单击事件
                    $("#bindLabelDialog  .select-one").click(selectOne);
                }else{
                    $("#list-table tbody").html("很抱歉，发生异常！");
                }

            },
            error:function(){
                $("#list-table tbody").html("很抱歉，请求失败！");
            }
        });
    }


    init();
});