$(function(){
    var _selectInfo = {
        'rank':1
    };
    /**
     * 页面初始化
     */
    function init(){
        getCategory(_selectInfo);
        bindEvent();
    }
    /**
     * 所有事件的绑定
     */
    function bindEvent(){
        trHover();
        checkBoxEvent();
        arrowEvent();
        delStoredCat();
        //添加删除未保存分类事件
        delUnstoredCat();
        saveSingleCategory();
        saveCategory();
        checkCateName();
        changeStaus();
    }
    //启用、停用当前类别状态
    function changeStaus(){
        $("#list-table .td-3 .cat-statusOper").die("click");
        $("#list-table .td-3 .cat-statusOper").live('click',function(){
            var id = $(this).parent().parent().attr("id");
            var isuse = $(this).attr("status") == 1?0:1;
            $.ajax({
                url:"./admin.php?c=store_category&a=changeStatus",
                type:"post",
                dataType:'json',
                data:{id:id,
                    isuse:isuse},
                success:function(json){
                    if(json.errorCode == 0){
                        getCategory(_selectInfo);
                    }else{
                        responseTip(json.errorCode,json.errorInfo,1500);
                    }
                },
                error:errorResponse
            });
        });
    }
    //为分类名称输入框添加 keyup 事件--为空时给予用户提示
    function checkCateName(){
        $("#list-table .td-1 .category-name").die("keyup");
        $("#list-table .td-1 .category-name").live('keyup',function(){
            if($(this).next().length !=0){
                $(this).next().remove();
            }
            var val = $(this).val();
            if(val == ""){
                var tipping ='<div class="alert-error name-alert">分类名称不能为空</div>';
                $(this).addClass("input-error");
                $(this).after(tipping);
                //alert("分类名称不能为空");
                return false;
            }else{
                //不为空值时
                $(this).removeClass("input-error");
                $(this).next(".alert-error").remove();
                return false;
            }

        });
    }
    //保存单个分类信息
    function saveSingleCategory(){
        $("#list-table .single-cat-save").die("click");//删除先前用.live()绑定的所有事件
        //1.添加新分类信息2.修改原分类信息
        $("#list-table .single-cat-save").live('click',function(){
            var elem = $(this).parent().parent();//获取当前行
            var flag = 0;//当前操作类型标识，0表示添加，1表示更新操作
            var category = {};//分类对象
            category.fir = elem.attr("fir");
            category.sec = elem.attr("sec");
            category.thr = elem.attr("thr");
            category.rank = elem.attr("rank");
            category.name = elem.find(".category-name").val();
            category.order_index = elem.find(".order-index").val();
            category.add_time = elem.find(".td-2").text();

            if(elem.hasClass("cat-stored")){
                //更新操作--获取所有原分类信息，即在数据库中已经有保存的分类
                flag = 1;
                category.cid = elem.attr("id");
            }else{
                //添加操作--获取所有新分类信息,即所有还未写入数据库的分类信息
                flag = 0;
            }
            $.ajax({
                type:'post',
                url:'./admin.php?c=store_category&a=saveSingleCategory',
                dataType:'json',
                data:{
                    "flag":flag,
                    "category":category
                },
                success:function(json){
                    if(json.errorCode == 0){
                        if(flag == 0){//添加新分类
                            //此处实现添加后不刷新页面效果
                            elem.removeClass("cat-unstored").addClass("cat-stored");
                            elem.attr("cid",json.data);
                        }

                        elem.find(".category-name").removeClass("unstored-input");
                        elem.find(".order-index").removeClass("unstored-input");

                        responseTip(json.errorCode,"恭喜您，操作成功！",1500);
                        return false;
                        //getCategory(_selectInfo);
                    }else{
                        responseTip(json.errorCode,json.errorInfo,1500);
                        return false;
                    }
                },
                error:errorResponse
            });
        });
    }
    //保存更改事件
    function saveCategory(){
        $(".hb-toolbar .cat-save").die("click");//删除先前用.live()绑定的所有事件
        //1.添加新分类信息2.修改原分类信息
        $(".hb-toolbar .cat-save").live('click',function(){

            //判断是否有分类信息，若无分类信息保存更改，方法立即退出
            if($("#list-table tbody").length == 0){
                //alert("没有分类信息可保存更改！");
                return false;
            }

            //判断所有分类的分类名称是否为空，若为空，则操作失败，方法直接结束
            var allCate = $("#list-table .td-1 .category-name");
            var emptyFlag = false;//分类名称为空标识，默认不为空
            for(var i = 0; i< allCate.length; i++ ){
                var cate = $(allCate.get(i));//获取分类名称对象
                if(cate.val() == ""){
                    if(cate.next().length == 0){
                        var tipping ='<div class="alert-error name-alert">分类名称不能为空</div>';
                        cate.addClass("input-error");
                        cate.after(tipping);
                    }
                    emptyFlag = true;
                }
            }
            if(emptyFlag == true){
                //若有分类名称为空，则操作结束
                return false;
            }
            //判断页面上的分类名称是否有重复的，有重复的则退出操作
            //var repeatFlag =false;//分类名称是否重复，默认不重复
            /* for(var j = 0; j < allCate.length; j++){
             for(var k = j+1;k < allCate.length; k++){
             var jObj = $(allCate.get(j));
             var kObj = $(allCate.get(k));
             if(jObj.val() == kObj.val()){
             var tipping = '<div class="alert-error name-alert">类目名称不能重复</div>';
             if(jObj.next().length == 0){
             jObj.addClass("input-error").after(tipping);
             repeatFlag = true;
             }
             if(kObj.next().length == 0){
             kObj.addClass("input-error").after(tipping);
             repeatFlag = true;
             }
             }
             }
             }
             if(repeatFlag == true){//若分类名称有重复的，则退出操作
             return false;
             }*/
            //获取所有新分类信息,即所有还未写入数据库的分类信息
            var unstoredEle = $("#list-table tr.cat-unstored");
            var arrObj1=[],arrObj2=[];//两个对象数组，保存数据
            for(var i = 0; i < unstoredEle.length; i++){
                var category={};//分类对象，分别保存待添加、待修改分类数据信息
                var ele = $(unstoredEle.get(i));//未保存新分类行jquery对象
                category.rank = ele.attr("rank");
                category.fir = ele.attr("fir");
                category.sec = ele.attr("sec");
                category.thr = ele.attr("thr");
                category.name = ele.find(".category-name").val();
                category.order_index = ele.find(".order-index").val();

                category.add_time = ele.find(".td-2").text();
                arrObj1.push(category);
            }

            // 获取所有原分类信息，即在数据库中已经有保存的分类
            var storedEle = $("#list-table tr.cat-stored");
            for(var i = 0; i < storedEle.length; i++){
                var category={};//分类对象，分别保存待添加、待修改分类数据信息
                var ele = $(storedEle.get(i));//之前保存过的分类信息
                category.fir = ele.attr("fir");
                category.sec = ele.attr("sec");
                category.thr = ele.attr("thr");
                category.rank = ele.attr("rank");
                category.name = ele.find(".category-name").val();
                category.add_time = ele.find(".td-2").text();
                category.order_index = ele.find(".order-index").val();

                arrObj2.push(category);
            }

            $.ajax({
                type:'post',
                url:'./admin.php?c=store_category&a=batchSaveCategory',
                dataType:'json',
                data:{
                    "addData":arrObj1,
                    "updateData":arrObj2
                },
                success:function(json){
                    if(json.errorCode == 0){
                        responseTip(json.errorCode,"恭喜您，操作成功！",1500,function(){getCategory(_selectInfo);});
                    }else{
                        responseTip(json.errorCode,json.errorInfo,1500);
                    }
                },
                error:errorResponse
            });
        });

    }
    
    //删除 未保存的分类-事件
    function delUnstoredCat(){
        $("#list-table .cat-unstored .cat-delete").die("click");
        $("#list-table .cat-unstored .cat-delete").live('click',function(){
            var curLevel = $(this).parents("tr").attr("class").split(" ")[0];//当前分类级别
            if($(this).parents("tr").next().length != 0  && curLevel < $(this).parents("tr").next().attr("class").split(" ")[0]){//若有子分类，不让删除
                responseTip(1,"很抱歉，操作失败,此分类中还有子分类，不可以删除！",3000);
                return false;
            }
            if($(this).parents("tbody").find("tr").length > 1){
                //判断当前分类有几个显示行
                $(this).parents("tr").remove();
                return false;
            }else{
                $(this).parents("tbody").remove();
                return false;
            }
        });
    }
    //删除已保存的分类
    function delStoredCat(){
        $("#list-table .cat-stored .cat-delete").die("click");
        $("#list-table .cat-stored .cat-delete").live('click',function(){
        	var ele = $(this);
	        myConfirmModal("确定要删除此分类吗？",function(){
	            //1.判断当前分类有无子分类，有子类不让删除
	            //2.判断当前类有没有关联商品，有则不让删除
	            var id = ele.parents("tr").attr("id");
	            var rank = parseInt(ele.parents("tr").attr("rank"));
	            var fir = ele.parents("tr").attr("fir");
	            var sec = ele.parents("tr").attr("sec");
	            var thr = ele.parents("tr").attr("thr");
	            var fou = ele.parents("tr").attr("fou");
	            var selectInfo={"rank":(rank + 1),
	                "id":id,
	                "fir":fir,
	                "sec":sec,
	                "thr":thr,
	                "fou":fou};
	            $.ajax({
	                type:'post',
	                url:'./admin.php?c=store_category&a=getCategorys',
	                dataType:'json',
	                data:selectInfo,
	                success:function(json){
	                    if(json.errorCode == 0){
	                        //有子分类时,不可以删除
	                        responseTip(1,"很抱歉，操作失败,此分类中还有子分类，不可以删除！",2000);
	                    }else if(json.errorCode == 1){//查询结果为空
	                        //可以删除
	                        $.ajax({
	                            type:'post',
	                            url:'./admin.php?c=store_category&a=deleteCategoryByNum',
	                            data:selectInfo,
	                            dataType:'json',
	                            success:function(json){
	                                if(json.errorCode == 0){
	                                    //删除成功时
	                                    ele.parents("tr").remove();
	                                    responseTip(json.errorCode,"恭喜您，操作成功！",1500);
	                                }else{
	                                    //删除失败
	                                    responseTip(json.errorCode,json.errorInfo,1500);
	                                }
	                            },
	                            error:errorResponse
	                        });
	                    }else{
	                        //查询异常时
	                        responseTip(1,json.errorInfo,1500);
	                    }
	                },
	                error:errorResponse
	            });
	
	            return false;
	        });
        });
    }
    //表格行鼠标悬浮事件
    function trHover(){
        $("#list-table tr").die("mouseover mouseout");
        $("#list-table tr").live('mouseover mouseout',function(event){
            if(event.type == 'mouseover'){
                $(this).addClass("high");
            }else if(event.type == 'mouseout'){
                $(this).removeClass("high");
            }
            return false;
        });
    }
    //复选框事件
    function checkBoxEvent() {
        $("#list-table .checkbox-box").die("click");
        //虚拟复选框点击事件
        $("#list-table .checkbox-box").live('click',function(){
            $(this).toggleClass("checked");

            if($(this).hasClass("checked")){
                //当所有复选框都选中时，全选框选中
                var checkboxs = $("#list-table .checkbox-box");//所有复选框对象
                var selectedcheckboxs = $("#list-table .checked");//所有被选中的复选框对象
                if(checkboxs.length == selectedcheckboxs.length){
                    $(".select-opt .checkbox-box").addClass("checked");
                }
            }else{
                $(".select-opt .checkbox-box").removeClass("checked");
            }
            return false;
        });
        /*全选事件*/
        $(".select-opt .cat-checkbox").click(function(){

            if($(this).hasClass("checked")){//当前全选框被选中时，则实现取消效果
                $(this).removeClass("checked");
                $("#list-table .checkbox-box").removeClass("checked");
            }else{//当前复选框未被选中时，则实现全选效果
                $(this).addClass("checked");
                $("#list-table .checkbox-box").addClass("checked");
            }
            return false;
        });
    }

    //arrow箭头事件，隐藏、显现子类型,只对已保存的分类有效
    function arrowEvent(){
        $("#list-table .cat-stored .arrow").die("click");
        $("#list-table .cat-stored .arrow").live('click',function(){
            var rank = parseInt($(this).parents("tr").attr("rank"));//当前分类级别
            var fir = $(this).parents("tr").attr("fir");
            var sec = $(this).parents("tr").attr("sec");
            var thr = $(this).parents("tr").attr("thr");
            if($(this).hasClass("arrow-left")){
                //展现子类信息,异步请求到后台抓取数据
                var selectInfo={
                	"rank":(rank + 1),//查询子分类级别
                    "fir":fir,
                    "sec":sec,
                    "thr":thr
                };
                getSubCate($(this),selectInfo);
                //若当前子类处于隐藏状态，则展现子类信息
                $(this).removeClass("arrow-left").addClass("arrow-top");

            }else{
                //若当前子类处于展现状态，则隐藏子类信息
                $(this).removeClass("arrow-top").addClass("arrow-left");
                //隐藏子类信息
                if(rank == 1){
                    //若隐藏分类为二级分类，则
                    $(this).parents("tr").nextAll("[fir='"+fir+"']").remove();
                }else if(rank ==2){
                    //若隐藏分类为三级分类，则
                    $(this).parents("tr").nextAll("[fir='"+fir+"'][sec='"+sec+"']").remove();
                }else if(rank ==3){
                    //若隐藏分类为四级分类,则
                    $(this).parents("tr").nextAll("[fir='"+fir+"'][sec='"+sec+"'][thr='"+thr+"']").remove();
                }

            }
            return false;//阻止默认事件行为和事件冒泡
        });
    }
    /**
     * 获取第一级分类信息
     */
    function getCategory(selectInfo) {
        //获取一级分类信息
        //获取一级分类:selectInfo={'rank':1},
        $.ajax({
            async:false,
            type:'post',
            url:'./admin.php?c=store_category&a=getCategorys',
            dataType:'json',
            data:selectInfo,
            success:function(json){
                if(json.errorCode == 0){
                    var data = json.data;
                    var html = "";
                    for(var i=0,l=data.length;i<l;i++){
                        html += '<tbody>';
                        //存储当前分类全类属性,包括级别及父分类编码标识
                        var cate = {"rank":selectInfo.rank,
                            "fir":data[i].fir,
                            "sec":"",
                            "thr":"",
                            "fou":""};
                        var statusOper = "";
                        var templateHtml = "<a class='edit' href='./admin.php?c=store_category&a=editCategory&id="+data[i].id+"'>编辑分类</a>";
                        //var status = (data[i].isuse == 0)? "停用" : "启用";
                        //var statusOper = (data[i].is_use == 0)? "启用" : "停用";
                        html += '<tr class="cat-level-'+selectInfo.rank+' cat-stored" id='+data[i].id+' rank="1" fir="'+(cate.fir)+'" sec="'+cate.sec+'" thr="'+cate.thr+'" fou="'+cate.fou+'">'
                            +'<td class="td-00"><span class="checkbox-box"><span class="pseudo-checkbox"></span></span></td>'
                            +'<td class="td-0"><span class="arrow arrow-left" data-rank="'+selectInfo.rank+'"></span></td>'
                            +'<td class="td-1"><div class="input-box"><input class="category-name" type="text" value="'+data[i].name+'"/></div></td>'
                            +'<td class="td-4">'+status+'</td>'
                            +'<td class="td-5"><div class="input-box"><input class="order-index" type="text" value="'+data[i].order_index+'"/></div></td>'
                            +'<td class="td-2">'+data[i].add_time+'</td>'
                            +'<td class="td-3"><a href="./admin.php?c=store_category&a=cateGoodsList&id='+data[i].id+'" >商品集</a><a href="./admin.php?c=store_category&a=addCategory&rank=2&pid='+data[i].id+'" class="cat-addSub" data-rank="1">添加子分类</a>'+templateHtml+'<a class="cat-statusOper" data-rank="1" status="'+data[i].is_use+'">'+statusOper+'</a><a class="cat-delete"  data-rank="1">删除</a><a class="single-cat-save">保存</a></td></tr>';

                        html +='</tbody>';
                    }
                    $("#list-table thead").nextAll().remove();
                    $('#list-table').append(html);

                }else if(json.errorCode == 1){//数据为空
                    $("#list-table").append("<tfoot><tr><td colspan='6'>没有记录</td></tr></tfoot>");
                }else{
                    responseTip(1,json.errorInfo,1500);
                }
            },
            error:errorResponse
        });
        return false;

    }
    //获取指定父类的子分类信息
    function getSubCate(selector,selectInfo){
        //二级分类:selectInfo={'rank':2,'fir':fir},
        //三级分类:selectInfo={'rank':3,'fir':fir,'sec':sec},
        //四级分类:selectInfo={'rank':4,'fir':fir,'sec':sec,'thr':thr}
        $.ajax({
            type:'post',
            url:'./admin.php?c=store_category&a=getCategorys',
            dataType:'json',
            data:selectInfo,
            success:function(json){
                if(json.errorCode == 0){
                    var data = json.data;
                    var html = "";
                    for(var i=0,l=data.length;i<l;i++){
                        //存储当前分类全类属性,包括级别及父分类编码标识
                        var cate = {"rank":selectInfo.rank,
                            "id":data[i].id,
                            "fir":"",
                            "sec":"",
                            "thr":"",
                            "fou":""};
                        //var status = (data[i].isuse == 0)? "停用" : "启用";
                        //var statusOper = (data[i].isuse == 0)? "启用" : "停用";
                        var statusOper = "";
                        var arrowClass = "arrow arrow-left";
                        var addSubHtml ='<a href="./admin.php?c=store_category&a=addCategory&rank='+(parseInt(selectInfo.rank)+parseInt(1))+'&pid='+data[i].id+'" class="cat-addSub">添加子分类</a>';
                        if(selectInfo.rank == 2){//当前子类为二级分类
                            cate.fir = data[i].fir;
                            cate.sec = data[i].sec;
                            addSubHtml += "<a class='edit' href='./admin.php?c=store_category&a=editCategory&id="+data[i].id+"'>编辑分类</a>";
                        }else if(selectInfo.rank == 3){//当前分类为三级子分类
                            cate.fir = data[i].fir;
                            cate.sec = data[i].sec;
                            cate.thr = data[i].thr;
//                            arrowClass = "";
                            addSubHtml += "<a class='edit' href='./admin.php?c=store_category&a=editCategory&id="+data[i].id+"'>编辑分类</a>";

                        }else if(selectInfo.rank == 4){//当前分类为四级子分类
                            cate.fir = data[i].fir;
                            cate.sec = data[i].sec;
                            cate.thr = data[i].thr;
                            cate.fou = data[i].fou;
                            arrowClass = "";
                            addSubHtml = "<a class='edit' href='./admin.php?c=store_category&a=editCategory&id="+data[i].id+"'>编辑分类</a>";
                        }
                        html += '<tr class="cat-level-'+selectInfo.rank+' cat-stored" id="'+cate.id+'" rank="'+cate.rank+'" fir="'+(cate.fir)+'" sec="'+cate.sec+'" thr="'+cate.thr+'" fou="'+cate.fou+'">'
                            +'<td class="td-00"><span class="checkbox-box"><span class="pseudo-checkbox"></span></span></td>'
                            +'<td class="td-0"><span class="'+arrowClass+'"></span></td>'
                            +'<td class="td-1"><div class="input-box"><input class="category-name" type="text" value="'+data[i].name+'"/></div></td>'
                            +'<td class="td-4">'+status+'</td>'
                            +'<td class="td-5"><div class="input-box"><input class="order-index" type="text" value="'+data[i].order_index+'"/></div></td>'
                            +'<td class="td-2">'+data[i].add_time+'</td>'
                            +'<td class="td-3"><a href="./admin.php?c=store_category&a=cateGoodsList&id='+data[i].id+'" >商品集</a>'+addSubHtml+'<a class="cat-statusOper" data-rank="1">'+statusOper+'</a><a class="cat-delete">删除</a><a class="single-cat-save">保存</a></td></tr>';

                    }
                    selector.parents("tr").after(html);//追加在当前分类下面
                }else if(json.errorCode == 1){//数据为空

                }else{
                    responseTip(1,json.errorInfo,1500);
                }
                return false;//阻止默认事件行为和事件冒泡
            },
            error:errorResponse
        });
        return false;
    }
    init();
});