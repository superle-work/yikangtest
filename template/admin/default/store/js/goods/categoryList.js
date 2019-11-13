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
        addNewCategory();
        addSubCategory();
        delStoredCat();
        //添加删除未保存分类事件
        delUnstoredCat();
        saveSingleCategory();
        saveCategory();
        checkCateName();
        changeStaus();
        bindTemplate();
        changeTemplate();
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
    //"添加"新一级商品分类事件-未保存--在表格后面追加一行
    function addNewCategory(){
        $(".hb-toolbar .addCatagory").die("click");//删除用live绑定的事件
        $(".hb-toolbar .addCatagory").live('click',function(){
            //添加新类型时在表格最后面新添加一行
            var date = new Date();
            var year = date.getFullYear();
            var month = (date.getMonth() + 1) ;
            month = month < 10 ? '0'+month : month;
            var day = date.getDate();
            day = day < 10 ? '0'+day : day;
            var time = year +'-'+ month +"-"+ day;//当前日期
            //计算出当前新一级分类的fir值
            if($("#list-table tbody:first tr:first").length > 0){
                var fir = parseInt($("#list-table tbody:first tr:first").attr("fir"))+1;//转换成数字
            }else{
                fir = 1;
            }
            if(fir < 10){//若是一位数
                fir = "00"+fir;
            }else if(fir < 100){//若是两位数
                fir ="0"+fir;
            }else{
                //三位数不处理
            }

            var html ='<tbody><tr class="cat-level-1 cat-unstored look-me" rank="1" fir="'+fir+'" sec="" thr="" fou="">'
                +'<td class="td-00"><span class="checkbox-box"><span class="pseudo-checkbox"></span></span></td>'
                +'<td class="td-0"><span></span></td>'
                +'<td class="td-1"><div class="input-box"><input class="category-name" type="text" /></div></td>'
                +'<td class="td-4">启用</td>'
                +'<td class="td-5"><div class="input-box"><input class="order-index" type="text" /></div></td>'
                +'<td class="td-2">'+time+'</td>'
                +'<td class="td-3"><a class="cat-addSub">添加子分类</a><a class="cat-delete">删除</a><a class="single-cat-save">保存</a></td></tr></tbody>';

            if($("#list-table tbody").find(".look-me").length > 0){
                //页面上有新添加待保存的分类
                $("#list-table tbody").find(".look-me").removeClass("look-me");
            }
            $("#list-table thead").after(html);

            //获取焦点
            $("#list-table tbody:first .td-1 .category-name").focus();
            return false;
        });
    }
    //添加子分类
    function addSubCategory(){
        //删除先前用live()绑定的所有事件
        $("#list-table tbody .td-3 .cat-addSub").die("click");
        //为当前分类添加子分类
        $("#list-table tbody .td-3 .cat-addSub").live('click',function(){
            var html = "";
            var date = new Date();
            var year = date.getFullYear();
            var month = (date.getMonth() + 1) ;
            month = month < 10 ? '0'+month : month;
            var day = date.getDate();
            day = day < 10 ? '0'+day : day;
            var time = year +'-'+ month +"-"+ day;//当前日期
            var rank = parseInt($(this).parents("tr").attr("rank"));//当前分类级别
            rank += 1;//当前子分类级别
            var fir = $(this).parents("tr").attr("fir");//父类别分类编号
            var sec = $(this).parents("tr").attr("sec");//父类别分类编号
            var thr = $(this).parents("tr").attr("thr");//父类别分类编号
            var fou = $(this).parents("tr").attr("fou");//父类别分类编号
            var subCateNo;//记录当前子分类编号
            //计算出当前子分类的编号,需要以下两步才能确定当前子分类编号
            //1、计算出数据库中子分类最大编号，2、计算出当前未保存的子分类最大编号，比较大小
            var subCate;
            if(rank == 2){
                //所有二级子分类
                subCate =$(this).parents("tr").nextAll("[rank='"+rank+"'][fir='"+fir+"']");
            }else if(rank == 3){
                //所有三级子分类
                subCate =$(this).parents("tr").nextAll("[rank='"+rank+"'][fir='"+fir+"'][sec='"+sec+"']");
            }else if(rank == 4){
                //所有四级子分类
                subCate =$(this).parents("tr").nextAll("[rank='"+rank+"'][fir='"+fir+"'][sec='"+sec+"'][thr='"+thr+"']");
            }
            if(subCate.length > 0){
                //若页面中已有子分类展示时，直接从它们中读取出最大编号即可，此即为当前分类最大子分类编号
                var ele =$(this).parents("tr").next();//所有子分类都是按编号从大往小排序的，即第一个子分类即为最大编号者
                if(rank == 2){//当前子分类为2级时
                    sec = parseInt(ele.attr("sec")) +1;//当前子分类编号
                    if(sec < 10){
                        sec = "00"+sec;
                    }else if(sec < 100){
                        sec = "0"+sec;//子分类sec编号
                    }
                }else if(rank == 3){//当前子分类为3级时
                    thr = parseInt(ele.attr("thr")) +1;//当前子分类编号
                    if(thr < 10){
                        thr = "00"+thr;//子分类thr编号
                    }else if(thr < 100){
                        thr = "0"+thr;
                    }
                }else if(rank == 4){//当前子分类为4级时
                    fou = parseInt(ele.attr("fou")) +1;//当前子分类编号
                    if(fou < 10){
                        fou = "00"+fou;//子分类fou编号
                    }else if(fou < 100){
                        fou = "0"+fou;
                    }
                }
            }else{
                //页面中暂且没有该分类的子分类展示,
                // 读取数据库中当前分类子分类最大编号,从而计算出当前子分类编号
                if($(this).parents("tr").hasClass("cat-unstored")){
                    //若当前分类还没有保存,其子分类编号可置为“001”
                    subCateNo = "001";
                }else{
                    //若当前分类已保存,从数据库读取其子分类最大编号
                    $.ajax({
                        async:false,//置为同步
                        url:"./admin.php?c=store_category&a=getCategoryMax",
                        type:"post",
                        data:{'rank':rank,'fir':fir,'sec':sec,'thr':thr},//当前子分类级别，父分类编号
                        dataType:'json',
                        success:function(json){
                            //获取数据库中子分类最大编号
                            if(json.errorCode == 0){
                                var maxno = json.data;//字符串形式，如"008"
                                if(maxno !=null && maxno !=undefined && maxno !=""){
                                    //有数据时，即有子分类时
                                    maxno = parseInt(maxno);
                                    if((maxno+1) < 10){
                                        subCateNo = "00"+(maxno+1);
                                    }else if((maxno+1) < 100){
                                        subCateNo = "0"+(maxno+1);
                                    }else{
                                        subCateNo = (maxno + 1);
                                    }
                                }else{
                                    //查询结果为空，无子分类时,当前子分类编号置为“001”
                                    subCateNo = "001";
                                }

                            }else{//发生异常
                                responseTip(json.errorCode,json.errorInfo,1500);
                            }
                        },
                        error:errorResponse
                    });
                }
                if(rank == 2){
                    sec = subCateNo;//预置当前子分类sec编号
                }else if(rank == 3){
                    thr = subCateNo;//预置当前子分类thr编号
                }else if(rank == 4){//若当前子分类级别为四级
                    fou = subCateNo;//预置当前子分类fou编号

                }
            }

            var arrowClass = "arrow arrow-left";
            var addSubHtml ='<a class="cat-addSub">添加子分类</a>';
            if(rank == 3 || rank == 4){//若当前子分类级别为四级，则不具有添加、查看子分类功能
                arrowClass = "";
                addSubHtml = "";
            }
            if($("#list-table tbody").find(".look-me").length > 0){
                //页面上有新添加待保存的分类
                $("#list-table tbody").find(".look-me").removeClass("look-me");
            }

            //对应级别的分类信息
            html = '<tr class="cat-level-'+rank+' cat-unstored look-me" rank="'+rank+'" fir="'+fir+'" sec="'+sec+'" thr="'+thr+'" fou="'+fou+'">'
                +'<td class="td-00"><span class="checkbox-box"><span class="pseudo-checkbox"></span></span></td>'
                +'<td class="td-0"><span></span></td>'
                +'<td class="td-1"><div class="input-box"><input class="category-name" type="text" /></div></td>'
                +'<td class="td-4">启用</td>'
                +'<td class="td-5"><div class="input-box"><input class="order-index" type="text" /></div></td>'
                +'<td class="td-2">'+time+'</td>'
                +'<td class="td-3">'+addSubHtml+'<a class="cat-delete">删除</a><a class="single-cat-save">保存</a></td></tr>';
            $(this).parents("tr").after(html);

            return false;
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
            //1.判断当前分类有无子分类，有子类不让删除
            //2.判断当前类有没有关联商品，有则不让删除
            var ele = $(this);
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
                        //无子分类时,再判断该分类有无商品关联，有则不让删除
                        $.ajax({
                            type:'post',
                            url:'./admin.php?c=store_category&a=categoryRelateGoods',
                            dataType:'json',
                            data:selectInfo,
                            success:function(json){
                                if(json.errorCode == 0){
                                    //有商品关联，则不让删除
                                    responseTip(1,"很抱歉，操作失败,该分类正在被其他商品使用，无法删除！",3000);

                                }else if(json.errorCode == 1){//查询结果为空时
                                    //无商品关联，可以删除
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
                                    responseTip(1,json.errorInfo,1500);
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
    }
    //分类绑定模板
    function bindTemplate(){
        $("#list-table  .cat-bind-template").die("click");
        $("#list-table  .cat-bind-template").live('click',function(){
            var ele = $(this);
            var id = ele.parents("tr").attr("id");
            //跳出弹出框，选择模板
            $.ajax({
                type:"post",
                url:"./admin.php?c=store_category&a=showBindTemplate",
                data:{"id":id},
                dataType:"json",
                success:function(json,statusText){
                    if(json.errorCode == 0){
                        var html = "";
                        var list = json.data.templateList;
                        if(list !=undefined && list.length > 0){
                            html +="<span>模板名称：</span>";
                        }
                        for(var i = 0 ;i<list.length;i++){
                            if(i == 0){
                                html+="<label><input type='radio' checked name='template' value='"+list[i].id+"'>"+list[i].name+"</label>";
                            }else{
                                html+="<label><input type='radio' name='template' value='"+list[i].id+"'>"+list[i].name+"</label>";
                            }
                        }

                        if(list.length > 0){//有模板数据时
                            $("#bindTemplateDialog .modal-body").html(html);
                            $("#bindTemplateDialog #bindSave").click(function(){
                                var id = json.data.category.id;
                                var tid = $("#bindTemplateDialog .modal-body input:checked").val();
                                SaveBindTemplate(id,tid);
                            });
                            $("#bindTemplateDialog").modal('show');
                        }else{//无模板数据时
                            responseTip(1,"当前无模板数据，请先制作模板。",1500);
                        }
                    }else{
                        responseTip(1,json.errorInfo,1500);
                    }
                },
                error:errorResponse
            });
        });
    }
    //分类更换绑定的模板
    function changeTemplate(){
        $("#list-table  .cat-change-template").die("click");
        $("#list-table  .cat-change-template").live('click',function(){
            var ele = $(this);
            var id = ele.parents("tr").attr("id");
            //跳出弹出框，选择模板
            $.ajax({
                type:"post",
                url:"./admin.php?c=store_category&a=showBindTemplate",
                data:{"id":id},
                dataType:"json",
                success:function(json,statusText){
                    if(json.errorCode == 0){
                        var html = "";
                        var list = json.data.templateList;
                        if(list !=undefined && list.length > 0){
                            html +="<span>模板名称：</span>";
                        }
                        for(var i = 0 ;i<list.length;i++){
                            if(i == 0){
                                html+="<label><input type='radio' checked name='template' value='"+list[i].id+"'>"+list[i].name+"</label>";
                            }else{
                                html+="<label><input type='radio' name='template' value='"+list[i].id+"'>"+list[i].name+"</label>";
                            }
                        }

                        $("#bindTemplateDialog .modal-body").html(html);
                        $("#bindTemplateDialog #bindSave").click(function(){
                            var id = json.data.category.id;
                            var tid = $("#bindTemplateDialog .modal-body input:checked").val();
                            SaveBindTemplate(id,tid);
                        });
                        $("#bindTemplateDialog").modal('show');

                    }else{
                        responseTip(1,json.errorInfo,1500);
                    }
                },
                error:errorResponse
            });
        });
    }
    //绑定模板--保存
    function SaveBindTemplate(id,tid){
        $.ajax({
            type:"post",
            url:"./admin.php?c=store_category&a=saveBindTemplate",
            data:{"id":id,"tid":tid},
            dataType:"json",
            success:function(json,statusText){
                if(json.errorCode == 0){
                    $("#bindTemplateDialog").modal('hide');
                    window.location.reload();
                }else{
                    responseTip(1,json.errorInfo,1500);
                }
            },
            error:errorResponse
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
                //若当前子类处于隐藏状态，则展现子类信息
                $(this).removeClass("arrow-left").addClass("arrow-top");
                //展现子类信息,异步请求到后台抓取数据
                var selectInfo={"rank":(rank + 1),//查询子分类级别
                    "fir":fir,
                    "sec":sec,
                    "thr":thr};
                getSubCate($(this),selectInfo);

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
                        var templateHtml = "";
                        if(data[i].tid > 0){
                            templateHtml += "<a class='cat-change-template'>更换模板</a>";
                        }else{
                            templateHtml += "<a class='cat-bind-template'>绑定模板</a>";
                        }
                        //var status = (data[i].isuse == 0)? "停用" : "启用";
                        //var statusOper = (data[i].is_use == 0)? "启用" : "停用";
                        html += '<tr class="cat-level-'+selectInfo.rank+' cat-stored" id='+data[i].id+' rank="1" fir="'+(cate.fir)+'" sec="'+cate.sec+'" thr="'+cate.thr+'" fou="'+cate.fou+'">'
                            +'<td class="td-00"><span class="checkbox-box"><span class="pseudo-checkbox"></span></span></td>'
                            +'<td class="td-0"><span class="arrow arrow-left" data-rank="'+selectInfo.rank+'"></span></td>'
                            +'<td class="td-1"><div class="input-box"><input class="category-name" type="text" value="'+data[i].name+'"/></div></td>'
                            +'<td class="td-4">'+status+'</td>'
                            +'<td class="td-5"><div class="input-box"><input class="order-index" type="text" value="'+data[i].order_index+'"/></div></td>'
                            +'<td class="td-2">'+data[i].add_time+'</td>'
                            +'<td class="td-3"><a class="cat-addSub" data-rank="1">添加子分类</a>'+templateHtml+'<a class="cat-statusOper" data-rank="1" status="'+data[i].is_use+'">'+statusOper+'</a><a class="cat-delete"  data-rank="1">删除</a><a class="single-cat-save">保存</a></td></tr>';

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
                        var addSubHtml ='<a class="cat-addSub">添加子分类</a>';
                        if(selectInfo.rank == 2){//当前子类为二级分类
                            cate.fir = data[i].fir;
                            cate.sec = data[i].sec;
                            if(data[i].tid > 0){
                                addSubHtml += "<a class='cat-change-template'>更换模板</a>";
                            }else{
                                addSubHtml += "<a class='cat-bind-template'>绑定模板</a>";
                            }

                        }else if(selectInfo.rank == 3){//当前分类为三级子分类
                            cate.fir = data[i].fir;
                            cate.sec = data[i].sec;
                            cate.thr = data[i].thr;
                            arrowClass = "";
                            if(data[i].tid > 0){
                                addSubHtml = "<a class='cat-change-template'>更换模板</a>";
                            }else{
                                addSubHtml = "<a class='cat-bind-template'>绑定模板</a>";
                            }

                        }else if(selectInfo.rank == 4){//当前分类为四级子分类
                            cate.fir = data[i].fir;
                            cate.sec = data[i].sec;
                            cate.thr = data[i].thr;
                            cate.fou = data[i].fou;
                            arrowClass = "";
                            addSubHtml = "";
                        }
                        html += '<tr class="cat-level-'+selectInfo.rank+' cat-stored" id="'+cate.id+'" rank="'+cate.rank+'" fir="'+(cate.fir)+'" sec="'+cate.sec+'" thr="'+cate.thr+'" fou="'+cate.fou+'">'
                            +'<td class="td-00"><span class="checkbox-box"><span class="pseudo-checkbox"></span></span></td>'
                            +'<td class="td-0"><span class="'+arrowClass+'"></span></td>'
                            +'<td class="td-1"><div class="input-box"><input class="category-name" type="text" value="'+data[i].name+'"/></div></td>'
                            +'<td class="td-4">'+status+'</td>'
                            +'<td class="td-5"><div class="input-box"><input class="order-index" type="text" value="'+data[i].order_index+'"/></div></td>'
                            +'<td class="td-2">'+data[i].add_time+'</td>'
                            +'<td class="td-3">'+addSubHtml+'<a class="cat-statusOper" data-rank="1">'+statusOper+'</a><a class="cat-delete">删除</a><a class="single-cat-save">保存</a></td></tr>';

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