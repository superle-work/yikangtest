$(function(){
    /**
     * 素材管理
     */
    var total = 1;//分页总页面数
    var currentPage = 1;//当前页
    var pageSize = 18;//每页显示的记录数

    function init(){
        myPagination();
        bindEvent();
    }

    function bindEvent(){
        //素材导航点击事件
        $(".right-section .media-type li").click(function(){
            $(".right-section .media-type li").removeClass("selected");
            $(this).addClass("selected");
            myPagination();
        });
    }

    //删除素材
    function deleteFn(){
        var media_id = $(this).attr("data-media_id");
        $.ajax({
            url:"./admin.php?c=weixin_material&a=deleteMedia",
            type:"post",
            data:{"media_id":media_id},
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
                    $("#myModal .modal-body").html("<p class='text-success'><b>恭喜您，操作成功！</b></p>");
                    $("#myModal").modal('show');
                    //定时器，1.5秒后模态框自动关闭
                    setTimeout(function(){
                        $("#myModal").modal('hide');
                        window.location.reload();
                    },1500);
                }else{
                    //alert("添加失败，请稍后再试！");
                    $("#myModal .modal-body").html("<p class='text-danger'>"+json.errorInfo+"</p>");
                    $("#myModal").modal('show');
                    //定时器，1.5秒后模态框自动关闭
                    setTimeout(function(){
                        $("#myModal").modal('hide');
                    },3000);
                }
            },
            error:errorResponse
        });
    };
    /**
     * 图文分页显示方法
     */
    function myPagination(){
        render(true,1,pageSize);
        //调用公共分页方法
        pagination("#page-selection",{total:total,pageSize:pageSize},render);

    }
    /**
     * 获取查询参数
     */
    function getSelectInfo(){
        //news图文,image图片,voice语音,video视频
        var type = $(".right-section .media-type li.selected a").attr("data-media_type");//素材类型

        var selectInfo = {
            "type":type
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
            url:'./admin.php?c=weixin_material&a=pagingMedia',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(json){//item:结果集，item_count：查询记录个数，total_count：所有记录个数
				if(json == null || json == ""|| json.errcode){
					$("#page-selection").bootpag({total:1});//重新计算总页数
					$("#myModal .modal-body").html("<p class='text-danger'>很抱歉,微信平台系统异常！</p>");
					$("#myModal").modal('show');
					//定时器，1.5秒后模态框自动关闭
					setTimeout(function(){
						$("#myModal").modal('hide');
					},1500);
				}else{
					var html ='';
					var count = json.total_count;
					if(count%pageSize == 0){//整除
						total = count/pageSize;
					}else{
						total = parseInt(count/pageSize) + 1;
					}
					$("#page-selection").bootpag({total:total});//重新计算总页数

					currentPage = pageIndex;
					var myList = json.item;

					if(myList.length == 0){
						html += '<p class="text-danger">查询结果为空。</p>';
					}else{
						html+=madeByType(selectInfo.type,myList);
					}
					$(".inner-section #materialList").html(html);
					
				}
                

            },
            error:errorResponse
        });
    }
    //根据不同media_type类型，拼接html
    function madeByType(type,myList){//news图文,image图片,voice语音,vedio视频
        var html = "";
        if(type == "news"){//图文
            html+="<ul class='news-list'>";
            var imgurl = "./themes/image/wechat.png";//图文素材默认图片，其本图片微信未提供第三方接口，无法展示
            for(var i = 0;i < myList.length; i++){
                html+="<li class='news-item'>";
                html+="<div class='news-item-inner clearfix'>";
                html+="<div class='news-item-content'>";
                html+='<img title="原图被微信屏蔽了" src="'+imgurl+'" class="content-cover">';
                var obj = myList[i];//obj={content:news_item[],media_id,update_time};
                var media_id = obj.media_id;//如："mGNTZ_NQpSb2t7R2lo5m_R28NLxs4WxJ4I25NUbmlko"
                var update_time = new Date(parseInt(obj.update_time) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
                var content = obj.content;
                html+='<div class="content-abstract">';
                if(content.news_item.length == 1){//图文对象
                    html+='<p class="content-title"><a target="_blank" href="'+content.news_item[0].url+'">'+content.news_item[0].title+'</a></p>';
                }else{
                    for(var j = 0;j < content.news_item.length;j++){
                        var news_item = content.news_item[j];
                        html+='<p class="content-title"><a target="_blank" href="'+news_item.url+'">'+(j+1)+'. '+news_item.title+'</a></p>';
                    }
                }
                html+='</div>';//.content-abstract
                html+="</div>";//.news-item-content
                html+="<div class='oper'></div>";//操作
                html+="<div class='date' title='更新时间'><p>"+update_time+"</p></div>";
                html+="</div>";//.news-item-inner
                html+="</li>";//.news-item

            }

            html+="</ul>";
        }else if(type == "image"){//图片
            html+="<div class='image-list'>";
            for(var i = 0;i < myList.length; i++){
                var obj = myList[i];
                html+="<div class='col-sm-2 col-md-2 image-item'><div class='image-wrap'><img src='"+obj.url+"'><span class='image-name'>"+obj.name+"</span></div></div>";
            }
            html+="</div>";
        }else if(type == "voice"){//语音
            html+="<div class='voice-list'>";
            for(var i = 0;i<myList.length;i++){
                var obj = myList[i];
                html+="<div class='col-sm-3 col-md-3 voice-item'><div class='voice-content'><span class='voice-icon'><i class='icon iconfont'>&#xe64c;</i></span><span class='voice-name'>"+obj.name+"</span></div></div>";
            }
            html+="</div>";

        }else if(type == "video"){//视频
            html+="<div class='video-list'>";
            for(var i = 0;i<myList.length;i++){
                var obj = myList[i];
                html+="<div class='col-sm-3 col-md-3 video-item'><div class='video-content'><span class='video-icon'><i class='icon iconfont'>&#xe64d;</i></span><span class='video-name'>"+obj.name+"</span></div></div>";
            }
            html+="</div>";
        }


        return html;
    }
    init();
});