$(function(){
    /***********
     * 后台系统全局js
     */
    /**$('a[data-trigger="tooltip"]').scojs_tooltip({
       cssclass: 'pretty'
        ,delay: 1000
    });**///与tootip的css样式冲突
	//初始化复选框、单选按钮皮肤样式   //需要使用效果增强插件的复选框添加类‘my-icheckbox’，单选按钮添加类‘my-iradio’
    $("input.my-icheckbox,input.my-iradio").iCheck({
        checkboxClass:"icheckbox_minimal-blue",//颜色主题需要与引入的css保持一致
        radioClass:"iradio_minimal-blue",//颜色主题需要与引入的css保持一致
        cursor:true
    });
    //左边栏菜单悬浮提示事件
    $('.container-fluid.scale .left-section a[data-toggle="tooltip"]').tooltip();
    //logo悬浮事件
//    $(".header .logo img").hover(function(){
//        $(this).attr("src","./themes/image/logo2.png");
//    },function(){
//        $(this).attr("src","./themes/image/logo.png");
//    });
    
    //关闭弹框
    $("body").on("click",".notify-wrap .notify-off",function(){
    	$(this).parents(".notify-wrap").slideUp(1000);
    })
    /**
     * 顶级导航点击事件
     */
    $(".header .top-nav .nav-item").click(function(){
        $(".header .top-nav .nav-item").removeClass("active");
        $(this).addClass("active");
        var name = $(this).attr("data-name");
		var topId = $(this).attr("data-id");
        $(".content .left-section ul").hide();
        //var menus = $(".content .left-section ul[data-name="+name+"]");
		var menus = $(".content .left-section ul[data-top-id="+topId+"]");
        //menus.show();
        var href = menus.find("a:first").attr("href");
        window.location.href = href;

    });

    /**
     * 右边栏内部导航效果
     * */
    $(".inner-section > .nav li a").click(function(){
        if($(this).parent().hasClass("selected")){
            return false;
        }
        $(".inner-section > .nav li").removeClass("selected");
        $(this).parent().addClass("selected");

        var id = $(this).attr("flag");//获取显示区域标签id值
        $(".inner-section .items").find(".item").hide();
        $(".inner-section .items").find(id).show();

        return false;

    });

    /**
     * 换肤
     */
    $(".header .changeSkin .skin").click(function(){
        var curSkin = $(".header .changeSkin").attr("skin");
        var skin = $(this).attr("skin");
        if(curSkin == skin){
            return false;
        }
        $.ajax(
            {
                type:"post",
                url:"./admin.php?c=base_index&a=changeSkin",
                data:{"admin_skin":skin},
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
                        window.location.reload();
                    }else{
                        $("#myModal .modal-body").html("<p class='text-danger'>很抱歉，发生异常！</p>");
                        $("#myModal").modal('show');
                        //定时器，1.5秒后模态框自动关闭
                        setTimeout(function(){
                            $("#myModal").modal('hide');
                        },1000);
                    }
                },
                error:errorResponse
            }
        );
    });

    //左边栏--缩放功能
    $(".left-section .btn-scale").click(function(){
        var _this = $(this);
        var isScale = 0;
        if(_this.parents(".container-fluid").hasClass("scale")){
            //若当前已缩小，则左边栏变为正常大小
            isScale = 0;
        }else{
            //当前正常显示，则缩放变小
            isScale = 1;
        }
        $.ajax(
            {
                type:"post",
                url:"./admin.php?c=base_index&a=leftBarScale",
                data:{"left_bar_scale":isScale},
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
                        if(isScale == 0){//去除缩放
                            $('.container-fluid.scale .left-section a[data-toggle="tooltip"]').tooltip('destroy');//去除提示条
                            _this.parents(".container-fluid").removeClass("scale");
                            $(".header").removeClass("scale");
                            $(".footer").removeClass("scale");

                        }else{//缩放
                            _this.parents(".container-fluid").addClass("scale");
                            $(".header").addClass("scale");
                            $(".footer").addClass("scale");
                            $('.container-fluid.scale .left-section a[data-toggle="tooltip"]').tooltip();
                        }
                    }else{
                        $("#myModal .modal-body").html("<p class='text-danger'>很抱歉，发生异常！</p>");
                        $("#myModal").modal('show');
                        //定时器，1.5秒后模态框自动关闭
                        setTimeout(function(){
                            $("#myModal").modal('hide');
                        },1000);
                    }
                },
                error:errorResponse
            }
        );
    });

    /**
     * 返回顶部
     */
    $(window).scroll(function(){
        if($(window).scrollTop() > 200){
            $(".up-nav").show();
        }else{
            $(".up-nav").hide();
        }
    })

    $(".back-bar .back-top,.up-nav .up-icon").click(function(){
        $('html,body').animate({'scrollTop':0},500); //返回顶部动画 数值越小时间越短
    });

});

//自定义确认对话框，返回true或false,确认或取消操作
function myConfirmModal(alertInfo,callback,chooseModal){//参数，当前操作提示文本
    if(alertInfo == undefined || alertInfo == ""){
        alertInfo = "确认当前操作吗？";
    }
    if(chooseModal == "" || chooseModal == undefined){
    	chooseModal = "#myConfirmModal";
    }
    $(chooseModal+" .modal-body").html("<p class='text-danger'>"+alertInfo+"</p>");
    $(chooseModal).modal('show');//对话框显现

    //确认对话框--确认操作
    $(chooseModal+" .btn-confirm").one('click',function(){
        $(chooseModal).modal('hide');//对话框显现
        callback();//调用回调函数
    });
}
//此事件在模态框被隐藏（并且同时在 CSS 过渡效果完成）之后被触发。
$('#myConfirmModal').on('hidden.bs.modal', function (e) {
    // do something...
    $("#myConfirmModal .btn-confirm").off("click");
})
//提交用户表单时的校验规则
function formValidation(arr, $form, options) {
    // 如果JQuery.Validate检测不通过则返回false
    if(!$form.valid()){
        return false;
    }
    for(var i=0;i<arr.length;i++){
        //去除前后空格
        if(arr[i].type !='file'){
            arr[i].value=$.trim(arr[i].value);
        }
    }
}
//提交表单时的配置
var formOptions = {
    beforeSubmit : formValidation,
    type : 'post',
    dataType : 'json',
    clearForm : false, // clear all form fields after successful submit
    resetForm : false
};
/**
 * 提交系统配置信息的表单配置
 */
var options={
    success:successResponse,
    beforeSend:function(xhr){
        //显示“加载中。。。”
        $("#loading").modal('show');
    },
    complete:function(){
        //隐藏“加载中。。。”
        $("#loading").modal('hide');
    },
    error:errorResponse
};
/**
 * 添加息得到服务器响应的回调方法
 */
function successResponse(json,statusText){
    responseTip(json.errorCode,json.errorInfo,2000);
}
/**
 *请求添加失败时（如网络不通畅、超时等）的回调方法
 */
function errorResponse(XMLHttpRequest,textStatus,errorThrown){
    responseTip(1,"网络异常，请求失败！",2000);
}

/**
 * 响应提示弹出框
 * @errorCode:0 为正确提示，1为错误提示
 * @time 弹框显现时长 毫秒
 * @callback 回调函数
 */
function responseTip(errorCode,text,time,callback){
    if(errorCode == 0){
        if(text == ""){
            text = "恭喜您，操作成功！";
        }
        $("#myModal .modal-body").html("<p class='text-success'>"+text+"</p>");
    }else if(errorCode == 1){
        if(text == ""){
            text = "操作异常！";
        }
        $("#myModal .modal-body").html("<p class='text-danger'>"+text+"</p>");
    }else if(errorCode == 2){
    	 $("#myModal .modal-body").html("<p class='text-danger'>"+text+"</p>");
    }
    if(!time){
        time = 3000;//默认3秒
    }
    $("#myModal").modal('show');
    //定时器，1.5秒后模态框自动关闭
    setTimeout(function(){
        $("#myModal").modal('hide');
        if(callback){//如果传了回调函数，则调用
            callback();
        }
    },time);
}

/**
 * 利用bootpag插件完成
 * 分页查询方法
 * @param selector 分页页码所在选择器内
 * @param option 插件自定义配置选择
 * @param callback 翻页事件回调函数
 */
//初始化配置
var pageOption = {
    firstLastUse: true,
	first: '«',
	last: '»',
	prev:'‹',
	next:'›',
    leaps:true,
    //wrapClass: 'pagination',
    //activeClass: 'active',
    //disabledClass: 'disabled',
    //nextClass: 'next',
    //prevClass: 'prev',
    //lastClass: 'last',
    //firstClass: 'first'
    total:1,//总页数
    total_count:0,//总记录数
    page:1, //当前显示页
    pageSize:10, //每页显示的记录数
    maxVisible:10//每页最多显示的页码链接
};//分页配置项

var selectInfo = {};//模糊查询配置参数
function pagination(selector,option,callback){

    $.extend(true,pageOption,option);
    $(selector).bootpag(pageOption).on("page", function(event, pageIndex){//当前页

    	var curPageSize = $("#page-selection #paging-mode").val() || pageOption.pageSize;//当前选择的分页模式，即每页显示几条记录
		pageOption.pageSize = curPageSize;
        //动态加载渲染数据
        callback(true,pageIndex,pageOption.pageSize);
    });
}
/**
 * 利用bootpag插件完成
 * 分页查询方法
 * @param selector 分页页码所在选择器内
 * @param option 插件自定义配置选择
 * @param callback 翻页事件回调函数
 */
//初始化配置
var pageOptions = {
    firstLastUse: true,
    first: '«',
    last: '»',
    prev:'‹',
    next:'›',
    leaps:true,
    //wrapClass: 'pagination',
    //activeClass: 'active',
    //disabledClass: 'disabled',
    //nextClass: 'next',
    //prevClass: 'prev',
    //lastClass: 'last',
    //firstClass: 'first'
    total:1,//总页数
    total_count:0,//总记录数
    page:1, //当前显示页
    pageSize:10, //每页显示的记录数
    maxVisible:10//每页最多显示的页码链接
};//分页配置项
function paginations(selector,option,callback){

    $.extend(true,pageOptions,option);
    $("#page-selections").bootpag(pageOptions).on("page", function(event, pageIndex){//当前页
        var curPageSize = $("#page-selections #paging-mode").val() || pageOptions.pageSize;//当前选择的分页模式，即每页显示几条记录
        pageOptions.pageSize = curPageSize;
        //动态加载渲染数据
        callback(true,pageIndex,pageOptions.pageSize);
    });
}
//字符串去除首发空格
String.prototype.trim=function(){
        return this.replace(/(^\s*)|(\s*$)/g,"");
};
//组合去除重复项
Array.prototype.unique = function(){
    var result = [], hash = {};
    for (var i = 0, elem; (elem = this[i]) != null; i++) {
        if (!hash[elem]) {
            result.push(elem);
            hash[elem] = true;
        }
    }
    return result;
}



///***
// * 全选
// * @param allSelector 全选复选框选择器
// * @param itemSelector 单选复选框选择器
// */
//function selectAll(allSelector,itemSelector){
//    $(allSelector).click(function(){
//        var boxs = $(itemSelector);//所有商品记录
//        //被选中
//        if($(this).prop("checked")){
//            boxs.prop("checked",true);//复选框全部选中
//
//        }else{
//            //全部取消
//            boxs.prop("checked",false);//复选框全部取消选中
//        }
//    });
//}
//
///***
// * 单选事件
// * @param allSelector 全选复选框选择器
// * @param itemSelector 单选复选框class类选择器
// */
//function selectSingle(allSelector,itemSelector){
//    $(itemSelector).click(function(){
//        if($(this).prop("checked")){//单选选中时
//            if($(itemSelector).length == $(itemSelector+":checked").length){
//                //所有复选框都选中时，将全选复选框置为选中状态
//                $(allSelector).prop("checked",true);
//            }
//        }else{//单选复选框取消选中时
//            $(allSelector).prop("checked",false);
//        }
//    });
//}

/**
 * 表格批量选择事件 
 * @param idList 存储所有分页中选中的记录主键id的数组
 * @param selectAllSelector 全选选择器jquery对象
 * @param selectSingleSelector 全选选择器jquery对象
 */
function batchSelect(idList,allSelector,singleSelector){
    selectAll(idList,allSelector,singleSelector);
    selectSingle(idList,allSelector,singleSelector);
}
/**
 * 全选事件
 * @param idList 存储所有分页中选中的记录主键id的数组
 * @param allSelector 全选选择器jquery对象
 * @param singleSelector 单选选择器
 */
function selectAll(idList,allSelector,singleSelector){
    $(allSelector).on("ifChanged",function(){
        var boxs = $(singleSelector);//所有记录
        //被选中
        if($(this).prop("checked")){
            boxs.prop("checked",true);//复选框全部选中
            boxs.parent().addClass("checked");//加my-icheckbox类的时候专用
            boxs.each(function(){
                if($.inArray($(this).val(),idList) < 0){//idList中不包含当前id值，则加入
                    idList.push($(this).val());
                }
            });
        }else{
            //全部取消
            boxs.prop("checked",false);//复选框全部取消选中
            boxs.parent().removeClass("checked");//加my-icheckbox类的时候专用
            //从idList数组中删除当前id
            boxs.each(function(){
                var index = $.inArray($(this).val(),idList);
                if(index >= 0){//idList中包含当前id值，则删除
                    idList.splice(index,1);
                }
            });
        }
    });
}

/**
 * 单选事件
 * @param idList 存储所有分页中选中的记录主键
 * @param allSelector 全选选择器
 * @param itemSelector 单选选择器
 */
function selectSingle(idList,allSelector,itemSelector){
    $(itemSelector).on("ifChanged",function(){
        if($(this).prop("checked")){//单选复选框选中时
            if($(itemSelector).length == $(itemSelector+":checked").length){
                //所有复选框都选中时，将全选复选框置为选中状态
                $(allSelector).prop("checked",true);
                $(allSelector).parent().addClass("checked");//加my-icheckbox类的时候专用
            }
            if($.inArray($(this).val(),idList) < 0){//idList中不包含当前id值，则加入
                idList.push($(this).val());
            }
        }else{//单选复选框取消选中时
            $(allSelector).prop("checked",false);
            $(allSelector).parent().removeClass("checked");
            //从idList数组中删除当前id
            var index = $.inArray($(this).val(),idList);
            if(index >= 0){//idList中包含当前id值，则删除
                idList.splice(index,1);
            }
        }
    });
}

/**
 * 开关样式
 * @param new_text 新文本对象
 */
function mySwitch(new_text){
	//开关插件使用 适用于复选框和单选按钮（只有一个单选按钮），使用开关效果的复选框和单选按钮添加类‘my-switch’
	var my_switch = 
			{
	            "size":'mini'
	            ,"checked":true//The checkbox state
	            ,"animate":true//Animate the switch
	            ,"readonly":false//Readonly state
	            ,"radioAllOff":true//Allow this radio button to be unchecked by the user
	            ,"onColor":"success"//Color of the right side of the switch 'primary', 'info', 'success', 'warning', 'danger', 'default'
	            ,"offColor":"danger"
	            ,"onText":"是"//Text of the left side of the switch ‘ON’
	            ,"offText":"否"//Text of the right side of the switch 'OFF'
	            ,"labelText":"&nbsp;"//Text of the center handle of the switch
	            ,"handleWidth":"auto"//Width of the left and right sides in pixels, 'auto' or Number
	            //,"labelWidth":"auto"
	            //,"baseClass":"bootstrap-switch"//Global class prefix
	            //,"wrapperClass":"wrapper"//Container element class(es)
	            //,"onInit":function(event,state){}//Callback function to execute on initialization
	            //,"onSwitchChange":function(event,state){}//Callback function to execute on switch state change. If false is returned, the status will be reverted, otherwise nothing changes
	        };
	$.extend(true,my_switch,new_text)
	$("input.my-switch").bootstrapSwitch(
    		my_switch
        );
}

/**
 * 开关样式
 * @param new_text 新文本对象
 */
function mySelectorSwitch(new_text,selector){
	//开关插件使用 适用于复选框和单选按钮（只有一个单选按钮），使用开关效果的复选框和单选按钮添加类‘my-switch’
	var my_switch = 
			{
	            "size":'mini'
	            ,"checked":true//The checkbox state
	            ,"animate":true//Animate the switch
	            ,"readonly":false//Readonly state
	            ,"radioAllOff":true//Allow this radio button to be unchecked by the user
	            ,"onColor":"success"
	            ,"offColor":"danger"//Color of the right side of the switch 'primary', 'info', 'success', 'warning', 'danger', 'default'
	            ,"onText":"是"//Text of the left side of the switch ‘ON’
	            ,"offText":"否"//Text of the right side of the switch 'OFF'
	            ,"labelText":"&nbsp;"//Text of the center handle of the switch
	            ,"handleWidth":"auto"//Width of the left and right sides in pixels, 'auto' or Number
	            //,"labelWidth":"auto"
	            //,"baseClass":"bootstrap-switch"//Global class prefix
	            //,"wrapperClass":"wrapper"//Container element class(es)
	            //,"onInit":function(event,state){}//Callback function to execute on initialization
	            //,"onSwitchChange":function(event,state){}//Callback function to execute on switch state change. If false is returned, the status will be reverted, otherwise nothing changes
	        };
	$.extend(true,my_switch,new_text)
	$("#myForm input.my-switch"+selector).bootstrapSwitch(
    		my_switch
        );
}

/**
 * 单选复选框调用样式的方法
 */
function myCheck(){
	$("input.my-icheckbox,input.my-iradio").iCheck({
        checkboxClass:"icheckbox_minimal-blue",//颜色主题需要与引入的css保持一致
        radioClass:"iradio_minimal-blue",//颜色主题需要与引入的css保持一致
        cursor:true
    });
}

//1分钟更新未读通知
function getUnreadNotifyNum(){
	$.ajax({
        async:true,
        type:'post',
        url:'./admin.php?c=base_admin&a=getNewMessage',
        data:{sec:60*2},
        dataType:'json',
        success:function(result){
        	var oldNum = $(".header .pull-right .user-action .number").text()
        	$(".user-action .number").text(result.data.num);
        	if(result.errorCode == 0){
        		//播放消息提醒音乐
        		var au = document.createElement("audio");
        		au.preload="auto";
        		au.src = "./themes/file/qipao.mp3";
        		au.play();
        		//消息列表
        		$("body .notify-wrap").remove();
        		var myList = result.data;
                var html = '';
                for(var i = 0; i < myList.length;i++){
                	var obj = myList[i];
                    html += '<div class="notify-wrap">'
                    	 + '<div class="notify-title">'+ obj.title +'<span class="notify-off"><i class="icon iconfont">&#xe6be;</i></span></div>'
                    	 + '<div class="notify-content">'+ obj.content +'</div>'
                    	 + '<div class="notify-bottom">消息来源：'+ obj.source +'</div>'
                    	 + '</div>';
                }
                $("body").append(html);
                $(".notify-wrap:first").delay(2000).slideDown(1000);
                setTimeout(function(){
                	var _this = $(".notify-wrap:first");
                	nextNotifyShow();
                	_this.slideUp(1000);
                },8000);
        	}
        },
//        error:errorResponse
	});
}
/**
 * 循环显示通知
 */
function nextNotifyShow(){
	var sec = 1000;
	$("body > .notify-wrap").each(function(){
		var _this = $(this);
		setTimeout(function(){
			if(_this.next().length > 0){
        		_this.next().delay(1000).slideDown(1000);
        	}
        	_this.slideUp(1000);
        },sec);
		sec += 6000;
	});
}
setInterval(getUnreadNotifyNum,1000*60*2);
