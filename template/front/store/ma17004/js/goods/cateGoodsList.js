/****
 * 商品分类
 * @author leon
 * @since 2016-05-23
 */
$(function(){
    var total = 1;//分页总页面数
    var currentPage = 1;//当前页
    var pageSize = 20;//每页显示的记录数

    //初始化
    function init(){
    	myPagination();
    	bindEvent();
    }
    //绑定事件
    function bindEvent(){
    	//点击排序导航按钮事件
    	$("#content .goods-sort-area .sort").click(function(){
    		field = $(this).attr('sort-field');
    		sort = $(this).attr('sort');
    		$(this).siblings().removeClass('sort-selected');
    		$(this).addClass('sort-selected');
    		if( $(this).attr('sort') == 'desc'){
    			$(this).attr('sort','asc');
    			$(this).find('i').html('&#xe65b;');
    		}else{
    			$(this).attr('sort','desc');
    			$(this).find('i').html('&#xe65c;');
    		}
    		myPagination();	
    	});
    }
    
    /**
     * 分页显示方法
     */
    function myPagination(){
        render(true,1,pageSize);
        //调用公共分页方法
        pagination("#page-selection",{total:total,pageSize:pageSize},render);

    }
    
    /**
     * 获取模糊参数
     */
    function getSelectInfo(){
        var field = $("#content .goods-sort-area .sort-selected").attr('sort-field');
    	var sort = $("#content .goods-sort-area .sort-selected").attr('sort');
    	var f_sort = field+" "+sort;
    	var category_id = $("#container #content .category_id").val();
        var selectInfo = {
            "id":category_id,
            "sort":f_sort
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
            url:'./index.php?c=store&a=pagingCategoryGoods',
            data:selectInfo,//从1开始计数
            dataType:'json',
            beforeSend:function(xhr){
	               //显示“加载中。。。”
	            	showDialog("#loadingDialog");
	            },
	            complete:function(){
	               //隐藏“加载中。。。”
	            	hideDialog("#loadingDialog");
	            },
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                    total = result.data.pageInfo.total_page;
                    if(async){
                        $("#page-selection").bootpag({total:total});//重新计算总页数
                    }
                    currentPage = result.data.pageInfo.current_page;
                    var myList = result.data.goodsList;	
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var id = obj.id;
                        var thumb = obj.thumb;
                        var name = obj.name;
                        var simple_desc = obj.simple_desc;
                        var price = obj.price;
                        var sale_quantity = obj.sale_quantity?'<em class="sale_quantity">'+ obj.sale_quantity +'</em>':'';
                        html+='<div class="item clearfix">'
                            	+'<a class="goods-detail" href="./index.php?c=store&a=goodsDetail&id='+ id +'">'
                            		+'<div class="goods-image">'
                            			+'<img src="'+thumb+'">'
                            		+'</div>'
                            		+'<div class="goods-info">'
                            			+'<p class="name">'+ name +'</p>'
                            			+'<p class="desc">'+simple_desc+'</p>'
                            			+'<p class="price"><i class="icon iconfont">&#xe604;</i><span>'+ price +'</span></p>'
                            			+'<p class="sale_quantity_title">已售：'+ sale_quantity +'</p>'
                            		+'</div>'
                            	+'</a>'
                            +'</div>';
                    }

                    if(myList.length == 0){
                        html += '<p class="text-danger">商品正在加速更新中，敬请期待</p>';
                    }
                    $("#content .goods-display-area").html(html);
                }else{
                	$("#content .goods-display-area").html("<p class='text-danger'>"+result.errorInfo+"</p>");
                }
            },
            error:function(){
                $("#content .goods-display-area").html("<p class='text-danger'>请求失败，网络异常！</p>");
            }
        });
    }
    init();
});