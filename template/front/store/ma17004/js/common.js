$(function(){
    /**
     * 返回顶部
     */
    $("#footer .back-top").click(function(){
        $('html,body').animate({'scrollTop':0},500); //返回顶部动画 数值越小时间越短
    });

    var width = $("#container").width();//计算当前窗口的宽度
    $("#container .bottom-nav").width(width);//将底部导航的宽度设置与移动设备相同
    $("#container .bottom-nav").css("display","block");
    /**$(window).scroll(function(){
        var elem = $("#container .bottom-nav");
        if($(window).scrollTop()>10){  //距顶部多少像素时，出现返回顶部按钮
            //未显示的时候，将其显示
            if(!elem.hasClass("nav-show")){
                elem.css("display","block");
                elem.addClass("nav-show");
            }
        }else{
            if(elem.hasClass("nav-show")){
                elem.hide();
                elem.removeClass("nav-show");
            }
        }
    });**/

    //用户变化屏幕方向时调用
    $(window).bind( 'orientationchange resize', function(e){
        width = $("#container").width();//计算当前窗口的宽度
        $("#container .bottom-nav").width(width);//将底部导航的宽度设置与移动设备相同
    });
    
});

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
        $("#myAlertModal .modal-body").html("<p class='text-success'>"+text+"</p>");
    }else if(errorCode == 1){
        if(text == ""){
            text = "操作异常！";
        }
        $("#myAlertModal .modal-body").html("<p class='text-danger'>"+text+"</p>");
    }
    if(!time){
        time = 3000;//默认3秒
    }
    $("#myAlertModal").modal('show');
    //定时器，1.5秒后模态框自动关闭
    setTimeout(function(){
        $("#myAlertModal").modal('hide');
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
    total:1,//总页数
    page:1, //当前显示页
    pageSize:20, //每页显示的记录数
    maxVisible:4//每页最多显示的页码链接
};//分页配置项
var selectInfo = {};//模糊查询配置参数
function pagination(selector,option,callback){
    $.extend(true,pageOption,option);
    $(selector).bootpag(pageOption).on("page", function(event, pageIndex){//当前页
        //动态加载渲染数据
        callback(true,pageIndex,pageOption.pageSize);
    });
}
String.prototype.trim=function(){
        return this.replace(/(^\s*)|(\s*$)/g,"");
};