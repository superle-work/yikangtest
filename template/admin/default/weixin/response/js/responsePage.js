$(function(){
    /**
     * 智能回复页面
     */
    var navFlag = $("#navFlag").val();
    //初始化
    function init(){
        showParserContent();
        bindEvent();
        //初始化默认导航
        if(navFlag !=""){
            $(".right-section .response-type #"+navFlag).click();
        }
    }


    //事件绑定
    function bindEvent(){
        $(document).click(function(){
            $('.qqFace').hide();
        });
        //智能回复启用、停用
        $(".right-section .oper .set-use").click(function(){
            var is_use = $(this).attr("data-isuse");

            $.ajax({
                url:"./admin.php?c=weixin_response&a=useResponse",
                type:"post",
                data:{"is_use":is_use},
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
                    	responseTip(0,json.errorInfo,1500,function(){
                    		window.location.reload();
                    	});
                    }else{
                    	responseTip(1,json.errorInfo,1500);
                    }
                },
                error:errorResponse
            });
        });

        //智能回复导航点击事件
        $(".right-section .response-type li").click(function(){
            $(".right-section .response-type li").removeClass("selected");
            $(this).addClass("selected");
            var use_scene = $(this).find("a").attr("data-use_scene");//智能回复类型，0：关注回复、1：消息自动回复、2：关键词回复
            //$(".right-section .response-set-way").hide();//帮助文档
            //$(".right-section .response-way-"+use_scene).show();
            //显示对应回复类型的内容
            $("#responseContentWrap .response-content").removeClass("selected");
            $("#responseContentWrap .response-content-"+use_scene).addClass("selected");
            if(use_scene == 2){//关键词回复类型时
                //隐藏底部“保存”、“删除回复”按钮
                $("#responseContentWrap .response-toolbar").hide();
            }else{
                $("#responseContentWrap .response-toolbar").show();
                $("#responseContentWrap .response-toolbar-oper").hide();
                $("#responseContentWrap .response-toolbar-"+use_scene).show();
            }
            $("#responseContentWrap .link-oper-area").hide();//超链接弹框隐藏
        });

		/**
		 * 自定义图文 相关操作
		 */
		//自定义图文--上传图片
		$("#responseContentWrap .display-content-my_news .item-upload-image").click(uploadImageFun);
		//自定义图文图片上传事件
		$("#myForm #imgurl").change(myNewsUploadImage);
        //文字、图片、语音、视频、自定义图文 导航的点击事件
        $("#responseContentWrap .response-content-nav li").click(function(){
            var media_type = $(this).find("a").attr("data-media-type");//类型
            $(this).parent().find("li").removeClass("selected");
            $(this).addClass("selected");
            //同步更新内容框内容
            $(this).parents(".response-content").find(".tab-content").removeClass("selected");
            $(this).parents(".response-content").find(".tab-content-"+media_type).addClass("selected");

            $("#responseContentWrap .link-oper-area").hide();//超链接弹框隐藏
        });
        //自定义图文添加事件
        $("#responseContentWrap .display-content-my_news .item-add").click(addMyNews);
		//自定义图文删除事件
		$("#responseContentWrap .display-content-my_news .item-delete").click(deleteMyNews);

        //文字输入框-键盘事件-keyup--显示剩余字数,总字数不得超过600个,同时维护隐藏的字符内容
        $("#responseContentWrap .display-content-text").keyup(function(){
            var str = $(this).html();
            str = contentFilter(str);//调用过滤器
            if(str.length <= 600){
                var cha = 600 - str.length;
                $(this).next().find(".editor-tip").html("还可以输入<em>"+cha+"</em>");
            }else{
                var chao = str.length - 600;

                $(this).next().find(".editor-tip").html("已超出<em class='warn'>"+chao+"</em>字");
            }
        });
        var cursor = -1; // 光标位置，以段落为基准线
        document.onselectionchange = function () {
            if (window.getSelection) {// IE9 and non-IE
                var selection = window.getSelection();
                var offset = selection.focusOffset;//光标离左侧的偏移量(以段落为参照系)

                var targetNode = selection.extentNode;//当前获取焦点的结点：文本结点，或者为父级div.display-content
                if(!targetNode){return false;}
                if(targetNode.nodeType == 3){
                    //为文本结点时
                    var text = targetNode.nodeValue;
                    var rightText = text.substr(offset,10);//获取焦点分隔处右侧的文本内容(取前10个)
                    var leftText = text.substr(-10);//获取焦点分隔处左侧的文本内容（取最后10）
                    var parentElement = targetNode.parentElement;
                    if($(parentElement).hasClass("display-content")){
                        var html = $(parentElement).html();//所有内容
                        var index = 0;//光标在全文本内容中的索引值（从0开始计算）
                        if(rightText !=""){
                            index = html.indexOf(rightText);
                        }else{
                            index = html.indexOf(leftText) + leftText.length;
                        }
                        cursor = index;

                        $(parentElement).attr("data-cursor",cursor);
                    }
                }else if(targetNode.nodeType == 1 && $(targetNode).hasClass("display-content-text")){
                    //nodeType == 1时，为元素结点时
                    // 为父级div.display-content
                    var chlidNodeList = targetNode.childNodes;//内容框内的子结点
                    var focusNodeIndex = selection.focusOffset;//当前获取焦点的子结点
                    var prevHtml = "";
                    for(var i = 0;i < focusNodeIndex;i++){
                        var node = chlidNodeList[i];
                        if(node.nodeType == 3){
                            //文本结点
                            prevHtml +=node.nodeValue;
                        }else if(node.nodeType == 1){
                            //元素结点
                            prevHtml +=node.outerHTML;
                        }
                    }
                    var length = prevHtml.length;
                    cursor = length;
                    $(targetNode).attr("data-cursor",cursor);
                }


            } else if (document.selection && document.selection.createRange) {
                //var selection =document.selection;
                //var range = document.selection.createRange();
            }
        }
        //qq表情-弹出qq小表情
        $(".right-section #responseContentWrap  .icon-emotion").qqFace({
            className : 'emotion-facebox',//qq表情目标载体div class名称
            clickCallback:function(event){//qq表情点击事件
                var title = $(this).find("i").attr("data-title");//qq表情主题
                var gifurl = $(this).find("i").attr("data-gifurl");//qq表情小图片路径
                var img = "<img src='"+gifurl+"' alt='"+title+"'>";
                var cursor = $(this).parents(".tab-content").find(".display-content").attr("data-cursor");
                if(cursor > 0){
                    //光标位置有值时
                    var content = $(this).parents(".tab-content").find(".display-content").html();
                    var prev = content.substring(0,cursor);
                    var next = content.substring(cursor);
                    var html = prev + img + next;
                    $(this).parents(".tab-content").find(".display-content").html(html);
                }else{
                    $(this).parents(".tab-content").find(".display-content").append(img);
                }

                var html = $(this).parents(".tab-content").find(".display-content").html();
                html = contentFilter(html);//调用过滤器

                //计算长度时，将qq表情图片进行过滤处理
                var length = html.length;//全内容字符长度
                if(length <= 600){
                    var cha = 600 - length;
                    $(this).parents(".editor-toolbar").find(".editor-tip").html("还可以输入<em>"+cha+"</em>");
                }else{
                    var chao = length - 600;
                    $(this).parents(".editor-toolbar").find(".editor-tip").html("已超出<em class='warn'>"+chao+"</em>字");
                }
                if (event) {
                    event.stopPropagation();     // 其它浏览器下阻止冒泡
                } else {
                    event.cancelBubble=true;     // ie下阻止冒泡
                }
            }
        });

        //超链接小图标click事件
        $(".right-section #responseContentWrap .icon-link").click(function(event){
            $(this).parent().find(".qqFace").hide();
            $(this).parent().find(".link-oper-area input").val("");
            $(this).parent().find(".link-oper-area").show();
            $(this).parent().find(".link-oper-area .link-name").focus();
            if (event) {
                event.stopPropagation();     // 其它浏览器下阻止冒泡
            }else{
                window.event.cancelBubble=true;     // ie下阻止冒泡
            }
        });


        //超链接操作区域-取消
        $(".right-section #responseContentWrap .link-cancel").click(function(){
            $(this).parents(".link-oper-area").hide();
        });
        //超链接操作区域-添加
        $(".right-section #responseContentWrap .link-add").click(function(event){
            $(this).parents(".link-oper-area").find(".tip-info").html("");//清除提示信息
            var name = $(this).parents(".link-oper-area").find(".link-name").val().trim();//链接名称
            var url = $(this).parents(".link-oper-area").find(".link-url").val().trim();//链接地址
            var tipInfo = "";//提示信息
            if(name == ""){
                tipInfo = "<p class='text-danger'>请输入链接名称</p>";
                $(this).parents(".link-oper-area").find(".tip-info").html(tipInfo);
                return false;
            }
            if(name.length > 30){
                tipInfo = "<p class='text-danger'>字数超过限制，最多输入30字</p>";
                $(this).parents(".link-oper-area").find(".tip-info").html(tipInfo);
                return false;
            }
            if(url == ""){
                tipInfo = "<p class='text-danger'>请输入链接地址</p>";
                $(this).parents(".link-oper-area").find(".tip-info").html(tipInfo);
                return false;
            }

            var urlReg = /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
            if(!urlReg.test(url)){
                tipInfo = "<p class='text-danger'>链接地址格式不正确</p>";
                $(this).parents(".link-oper-area").find(".tip-info").html(tipInfo);
                return false;
            }
            //生成a标签，添加到文本输入框内
            var a = '<a href="'+url+'">'+name+'</a>'
            var cursor = $(this).parents(".tab-content").find(".display-content").attr("data-cursor");
            if(cursor > 0){
                //光标位置有值时
                var content = $(this).parents(".tab-content").find(".display-content").html();
                var prev = content.substring(0,cursor);
                var next = content.substring(cursor);
                var html = prev + a + next;
                $(this).parents(".tab-content").find(".display-content").html(html);
            }else{
                $(this).parents(".tab-content").find(".display-content").append(a);
            }

            var html = $(this).parents(".tab-content").find(".display-content").html();
            html = contentFilter(html);//调用过滤器

            //计算长度时，将qq表情图片进行过滤处理
            var length = html.length;//全内容字符长度
            if(length <= 600){
                var cha = 600 - length;
                $(this).parents(".editor-toolbar").find(".editor-tip").html("还可以输入<em>"+cha+"</em>");
            }else{
                var chao = length - 600;
                $(this).parents(".editor-toolbar").find(".editor-tip").html("已超出<em class='warn'>"+chao+"</em>字");
            }
            $(this).parents(".link-oper-area").hide();//弹框隐藏
            if (event) {
                event.stopPropagation();     // 其它浏览器下阻止冒泡
            }else{
                event.cancelBubble=true;     // ie下阻止冒泡
            }
        });
        //重写文本输入框的enter事件 换行内容转换成“<br/>” 同时禁用ctrl事件
        $("#responseContentWrap .display-content-text").keydown(function(event){
            event = event ? event: window.event;
            if(event.keyCode == 13){//enter事件时
                var line = "<br/>";//换行符
                var cursor = $(this).attr("data-cursor");
                if(cursor > 0){
                    //光标位置有值时
                    var content = $(this).html();
                    var prev = content.substring(0,cursor);
                    var next = content.substring(cursor);
                    var html = prev + line + next;
                    $(this).html(html);
                }else{
                    $(this).append(line);
                }

                var html = $(this).html();
                html = contentFilter(html);//调用过滤器

                //计算长度时，将qq表情图片进行过滤处理
                var length = html.length;//全内容字符长度
                if(length <= 600){
                    var cha = 600 - length;
                    $(this).siblings(".editor-toolbar").find(".editor-tip").html("还可以输入<em>"+cha+"</em>");
                }else{
                    var chao = length - 600;
                    $(this).siblings(".editor-toolbar").find(".editor-tip").html("已超出<em class='warn'>"+chao+"</em>字");
                }
                if (event) {
                    event.stopPropagation();     // 其它浏览器下阻止冒泡
                }else{
                    event.cancelBubble=true;     // ie下阻止冒泡
                }
                return false;
            }else if(event.ctrlKey){//禁用ctrl事件
                return false;
            }
        });
        //被关注回复、统一自动回复----保存功能
        $("#responseContentWrap .response-toolbar .response-save").click(function(){
            var responseType = $(".inner-section .response-type li.selected");//当前操作的回复类型
            var navId = responseType.attr("id");//导航id
            var id = responseType.find("a").attr("data-id");//主键id
            var use_scene = responseType.find("a").attr("data-use_scene");//0 被关注回复，1 消息统一回复 ，2关键词自动回复
            var media_type = $("#responseContentWrap .response-content.selected .response-content-nav li.selected a").attr("data-media-type");
            var content = "";//文本内容
            var url = "";//图片地址
            var media_id = "";//图片、音频或视频的media_id
            //当前选中操作区别
            var selectedTabConElem =  $("#responseContentWrap .response-content.selected .tab-content.selected");
            if(media_type == "text"){
                //文本类型
                content = selectedTabConElem.find(".display-content").html();
                if(content == ""){
                	responseTip(1,"文字必须为1-600个字",1500);
                    return false;
                }
                content = contentFilter(content);//内容过滤处理
            }else if(media_type == "image"){
                //图片类型
                var materialElem = selectedTabConElem.find(".display-material-area img");
                if(materialElem.length == 0){
                	responseTip(1,"请添加图片素材",1500);
                    return false;
                }
                url = materialElem.attr("src");//图片链接地址
                media_id = materialElem.attr("data-media_id");


            }else if(media_type == "voice"){
                //语音类型
                var materialElem = selectedTabConElem.find(".display-material-area .voice-wrap");
                if(materialElem.length == 0){
                    responseTip(1,"请添加语音素材",1500);
                    return false;
                }
                media_id = materialElem.attr("data-media_id");

            }else if(media_type == "video"){
                //视频类型
                var materialElem = selectedTabConElem.find(".display-material-area .video-wrap");
                if(materialElem.length == 0){
                	responseTip(1,"请添加视频素材",1500);
                    return false;
                }
                media_id = materialElem.attr("data-media_id");
            }else if(media_type == "my_news"){//自定义图文时
            	var newsElem = selectedTabConElem.find(".display-content-my_news .item");
            	var itemList = [];
            	var newsFlag = true;
            	newsElem.each(function(){
            		var title = $.trim($(this).find(".title").val());//标题
            		var description = $.trim($(this).find(".description").val());//描述
            		var url = $.trim($(this).find(".url").val());//图文链接
            		var picUrl = $(this).find(".picUrl-area img").attr("src") || "";//图片地址
            		if(title == "" || description == "" || url == "" || picUrl == undefined || picUrl == ""){
            			newsFlag = false;
            			return false;
            		}
            		itemList.push({"title":title,"description":description,"url":url,"picUrl":picUrl});
            	});
            	
            	if(!newsFlag){//数据不完整的时候，不能提交
            		responseTip(1,"请完善数据后再提交！",2000);
            		return false;
            	}
            	if(itemList.length == 0){
            		responseTip(1,"至少添加一个自定义图文");
            		return false;
            	}else{
            		content = JSON.stringify(itemList);//转化成json字符串	
            	}
            	
            }

            $.ajax(
                {
                    type:"post",
                    url:"./admin.php?c=weixin_response&a=updateMessage",
                    data:{"id":id,"use_scene":use_scene,"type":media_type,"media_id":media_id,"content":content,"url":url},
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
                        	responseTip(json.errorCode,"恭喜您，操作成功！",1500,function(){
                        		window.location.href = window.location.href +"&navFlag="+navId;
                        	});
                        }else{
                            responseTip(1,json.errorInfo,1500);
                        }
                    },
                    error:errorResponse
                }
            );
        });

        //移出素材事件 重新选择
        $("#responseContentWrap .material-remove").one('click',function(){
            if($(this).parents(".keywords-response").length == 0){//被关注回复、消息统一回复
                $(this).parent(".display-material-area").hide();
                $(this).parents(".display-content").find(".no-content").show();
                $(this).parent(".display-material-area").html("");//重置内容
            }else{//关键词回复
                $(this).parents(".keywords-response").find(".rule-response-type-area li").removeClass("selected");
                $(this).parents(".keywords-response").find(".rule-response-type-area li:first").addClass("selected");
                $(this).parents(".rule-content-area").find(".tab-content").removeClass("selected");
                $(this).parents(".rule-content-area").find(".tab-content-text").addClass("selected").focus();
                $(this).parent(".display-material-area").html("");//重置内容
            }

        });

        //被关注回复、统一自动回复----启用、停用功能
        $("#responseContentWrap .response-toolbar .response-no-use,#responseContentWrap .response-toolbar .response-use").click(function(){
            var _this = $(this);
            var is_use = _this.attr("data-is_use");//0 停用，1 启用
            var responseType = $(".inner-section .response-type li.selected");//当前操作的回复类型
            var id = responseType.find("a").attr("data-id");//主键id
            //var use_scene = responseType.find("a").attr("data-use_scene");//0 被关注回复，1 消息统一回复 ，2关键词自动回复
            //var media_type = $("#responseContentWrap .response-content.selected .response-content-nav li.selected").find("data-media-type");
            //var content = "";//文本内容
            //var url = "";//图片地址
            //var media_id = "";//图片、音频或视频的media_id

            $.ajax(
                {
                    type:"post",
                    url:"./admin.php?c=weixin_response&a=useMessage",
                    data:{"id":id,"is_use":is_use},
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
                        	responseTip(0,json.errorInfo,1500,function(){
                        		_this.hide();
                                if(is_use == 0){//停用操作
                                    _this.parent().find(".response-use").css("display","inline-block");
                                }else{//启用操作
                                    _this.parent().find(".response-no-use").css("display","inline-block");
                                }
                        	});
                            
                        }else{
                        	responseTip(1,json.errorInfo,1500);
                        }
                    },
                    error:errorResponse
                }
            );
        });

        //关键词回复--启用、停用
        $("#responseContentWrap .keywords-rule .keywords-response-set-use").click(function(){
            var id = $(this).parents(".keywords-rule").attr("data-id");
            var is_use = $(this).attr("data-is_use");
            $.ajax(
                {
                    type:"post",
                    url:"./admin.php?c=weixin_response&a=useRule",
                    data:{"id":id,"is_use":is_use},
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
                        	responseTip(json.errorCode,json.errorInfo,1500,function(){
                        		var responseType = $(".inner-section .response-type li.selected");//当前操作的回复类型
                                var navId = responseType.attr("id");//导航
                                window.location.href = window.location.href +"&navFlag="+navId;
                        	});
                        }else{
                        	responseTip(1,json.errorInfo,1500);
                        }
                    },
                    error:errorResponse
                }
            );
        });
        //关键词回复--选择回复类型 文字text、图片image、语音voice、视频video、图文news
        $(".response-content-2 .rule-wrap .rule-response-type-area li a").click(function(){
            var _this = $(this);
            var media_type = _this.attr("data-media-type");

            if(media_type == "text"){
                //文字类型
                $(this).parents("ul").find("li").removeClass("selected");
                $(this).parent().addClass("selected");
                $(this).parents(".rule-wrap").find(".rule-content-area .tab-content").removeClass("selected");
                $(this).parents(".rule-wrap").find(".rule-content-area .display-material-area").html("");
                $(this).parents(".rule-wrap").find(".rule-content-area .tab-content-text").addClass("selected");
                $(this).parents(".rule-wrap").find(".rule-content-area .tab-content-text").find(".display-content").focus();
            }else if(media_type == "my_news"){//自定义图文时
            	$(this).parents("ul").find("li").removeClass("selected");
                $(this).parent().addClass("selected");
                $(this).parents(".rule-wrap").find(".rule-content-area .tab-content").removeClass("selected");
                $(this).parents(".rule-wrap").find(".rule-content-area .display-material-area").html("");
                $(this).parents(".rule-wrap").find(".rule-content-area .tab-content-my_news").addClass("selected");
                //$(this).parents(".rule-wrap").find(".rule-content-area .tab-content-my_news").find(".display-content").focus();
            	
            }else{//从素材库中选择素材 图片、语音、视频、图文时
                var title = "";
                if(media_type == "image"){
                    title = "请选择图片";
                }else if(media_type == "voice"){
                    title = "请选择语音";
                }else if(media_type == "video"){
                    title = "请选择视频";
                }else if(media_type == "news"){
                    title = "请选择图文消息";
                }
                $("#materialModal").attr("data-media_type",media_type);//为弹框添加 素材媒体类型标识
                $("#materialModal .modal-title").html(title);//添加提示信息
                myPagination();
                //弹出选择素材对话框
                $("#materialModal .modal-footer .btn-confirm").addClass("no-operate");//默认不可操作
                $("#materialModal").modal("show");
            }


        });

        //关键词回复--添加规则
        $(".response-content-2 .btn-rule-add-wrap .btn-ruld-add").click(function(){
            //判断新规则内容是否为空
            $(".response-content-2 .keywords-content").removeClass("active");
            $(this).parents(".response-content").find(".rule-add-wrap").addClass("active");//标识当前为活动区域
            $(this).parents(".response-content").find(".rule-add-wrap").show();
            $(this).parents(".response-content").find(".rule-add-wrap").find(".rule-name").focus();
        });

        //关键词回复--添加规则--移出
        $(".response-content-2 .rule-add-wrap .btn-rule-remove").click(function(){
            var _this = $(this);
            //判断当前有没有未保存的自定义图文的图片信息，有则不让删除，提示先删除自定义图文
            var picUrlElems = _this.parents(".rule-add-wrap").find(".display-content-my_news .picUrl-area img");
            if(picUrlElems.length > 0){//有已上传的图片，提示先删除图文，再删除规则
            	responseTip(1,"有未保存的图文，请先删除图文信息！");
            	return false;
            }
            myConfirmModal("确定要删除规则吗？",function(){
                _this.parents(".rule-add-wrap").find(".rule-response-type-area li").removeClass("selected");
                _this.parents(".rule-add-wrap").find(".rule-response-type-area li:first").addClass("selected");
                _this.parents(".rule-add-wrap").hide().removeClass("active");//去除活动区域操作标识
                //内容重置
                _this.parents(".rule-add-wrap").find("input").val("");
                _this.parents(".rule-add-wrap").find(".display-content-text").html("");
                _this.parents(".rule-add-wrap").find(".display-material-area").html("");
            });
        });

        //关键词回复--规则删除事件
        $(".response-content-2 .keywords-rule .btn-rule-delete").click(function(){
            var _this = $(this);
            var picUrlElems = _this.parents(".keywords-rule-wrap").find(".display-content-my_news .picUrl-area img");
            if(picUrlElems.length > 0){//有已上传的图片，提示先删除图文，再删除规则
            	responseTip(1,"请先删除图文信息！");
            	return false;
            }
            var id = _this.parents(".keywords-rule").attr("data-id");
            myConfirmModal("确定要删除规则吗？",function(){
                $.ajax(
                    {
                        type:"post",
                        url:"./admin.php?c=weixin_response&a=deleteRule",
                        data:{"id":id},
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
                                _this.parents(".keywords-rule").remove();
                            }else{
                                responseTip(1,json.errorInfo,1500);
                            }
                        },
                        error:errorResponse
                    }
                );
            });

        });
        //关键词回复--添加规则--保存(添加新规则、修改旧规则)
        $(".response-content-2 .rule-add-wrap .btn-rule-save,.response-content-2 .keywords-response-wrap .btn-rule-update").click(function(){
            //判断内容的合法性
            var rule_name = $(this).parents(".keywords-rule-wrap").find(".rule-name").val().trim();//规则名
            var keywords = $(this).parents(".keywords-rule-wrap").find(".rule-keywords").val().trim();//关键词
            if(rule_name == "" || keywords == ""){
            	responseTip(1,"请完善基本信息！",2000);
                return false;
            }
            if(rule_name.length > 60){
            	responseTip(1,"规则名字数不超过60个字！",2000);
                return false;
            }
            //正则表达式有问题，回头再修改
            var keywordsReg = /^(?!\|)(.{0,30}\|?)(?!\|)$/g;//关键词的正则表达式 不以竖线“|”开头与结尾
            var result = keywords.match(keywordsReg);
            if(!keywordsReg.test(keywords)){
            	responseTip(json.errorCode,"关键字格式不符合要求！",3000);
                return false;
            }
            var type = $(this).parents(".keywords-rule-wrap").find(".rule-response-type-area li.selected a").attr("data-media-type");//text/image/voice/video/news
            var content = "";
            var url = "";
            var media_id = "";
            var use_scene = $(".response-type li.selected a").attr("data-use_scene");//使用场景 2：关键词回复
            var is_use = "1";
            if(type == "text"){//文字
                content = $(this).parents(".keywords-rule-wrap").find(".display-content-text").html();
                content = contentFilter(content);//内容过滤
            }else if(type == "image"){//图片
                url = $(this).parents(".keywords-rule-wrap").find(".display-material-area img").attr("src");
                media_id = $(this).parents(".keywords-rule-wrap").find(".display-material-area img").attr("data-media_id");
            }else if(type == "voice"){//语音
                media_id = $(this).parents(".keywords-rule-wrap").find(".display-material-area .voice-wrap").attr("data-media_id");
                content = $(this).parents(".keywords-rule-wrap").find(".display-material-area .voice-wrap").find(".voice-name").html();
            }else if(type == "video"){//视频
                media_id = $(this).parents(".keywords-rule-wrap").find(".display-material-area .video-wrap").attr("data-media_id");
                content = $(this).parents(".keywords-rule-wrap").find(".display-material-area .video-wrap").find(".video-name").html();
            }else if(type == "news"){//图文
                media_id = $(this).parents(".keywords-rule-wrap").find(".display-material-area .news-wrap").attr("data-media_id");
                content = $(this).parents(".keywords-rule-wrap").find(".display-material-area .news-wrap").html();
            }else if(type == "my_news"){//自定义图文
            	var newsElem = $(this).parents(".keywords-rule-wrap").find(".tab-content-my_news .item");
            	var itemList = [];
            	var newsFlag = true;
            	newsElem.each(function(){
            		var title = $.trim($(this).find(".title").val());//标题
            		var description = $.trim($(this).find(".description").val());//描述
            		var url = $.trim($(this).find(".url").val());//图文链接
            		var picUrl = $(this).find(".picUrl-area img").attr("src") || "";//图片地址
            		if(title == "" || description == "" || url == "" || picUrl == undefined || picUrl == ""){
            			newsFlag = false;
            			return false;
            		}
            		itemList.push({"title":title,"description":description,"url":url,"picUrl":picUrl});
            	});
            	
            	if(!newsFlag){//数据不完整的时候，不能提交
            		responseTip(1,"请完善数据后再提交！",2000);
            		return false;
            	}
            	if(itemList.length == 0){
            		responseTip(1,"至少添加一个自定义图文");
            		return false;
            	}else{
            		content = JSON.stringify(itemList);//转化成json字符串	
            	}
            }
            if(content !=""){
               content = content.replace('<a href="javascript:;" class="material-remove">删除</a>','');
            }
            var href = "";
            var id = "",message_id = "";
            if($(this).parents(".keywords-rule-wrap").hasClass("rule-add-wrap")){
                //此时为添加新规则
                href = "./admin.php?c=weixin_response&a=addRule";
            }else{
                //修改旧规则
                href = "./admin.php?c=weixin_response&a=updateRule";
                id = $(this).parents(".keywords-rule").attr("data-id");
                message_id = $(this).parents(".keywords-rule").attr("data-message_id");
            }
            var responseType = $(".inner-section .response-type li.selected");//当前操作的回复类型
            var navId = responseType.attr("id");
            $.ajax({
                type:"post",
                url:href,
                data:{"rule_name":rule_name,"keywords":keywords,"is_use":is_use,"use_scene":use_scene,"type":type,"media_id":media_id,"content":content,"url":url,"id":id,"message_id":message_id},
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
                    	responseTip(json.errorCode,json.errorInfo,1500,function(){
                    		var url = window.location.href;
                    		url = url.split("&navFlag")[0];
                    		window.location.href = url +"&navFlag="+navId;
                    	});
                    }else{
                    	responseTip(1,json.errorInfo,3000);
                    }
                },
                error:errorResponse
            });

        });

        //关键回复-规则区域获取焦点事件
        $(".response-content-2  .keywords-response").focusin(function(){
            $(".response-content-2  .keywords-response").removeClass("active");
            $(this).addClass("active");
        });

        //从素材库中选择
        $("#responseContentWrap .create-access a").click(function(){
            //拉取所需素材，写入弹出框，展示给用户以供选择
            var _this = $(this);
            var media_type = _this.parents(".response-content").find(".response-content-nav li.selected a").attr("data-media-type");//素材类型，image,voice,video
            var title = "";
            if(media_type == "image"){
                title = "请选择图片";
            }else if(media_type == "voice"){
                title = "请选择语音";
            }else if(media_type == "video"){
                title = "请选择视频";
            }
            $("#materialModal").attr("data-media_type",media_type);//为弹框添加 素材媒体类型标识
            $("#materialModal .modal-title").html(title);//添加提示信息
            myPagination();
            //弹出选择素材对话框
            $("#materialModal .modal-footer .btn-confirm").addClass("no-operate");//默认不可操作
            $("#materialModal").modal("show");
        });

        //素材库弹出框--底部按钮“确认”事件
        $("#materialModal .btn-confirm").click(function(){
            //判断当前是否可以点击
            if($(this).hasClass("no-operate")){
                return false;
            }
            var responseType = $(".response-type li.selected a").attr("data-use_scene");//当前选中回复导航类型
            var media_type = $(this).parents("#materialModal").attr("data-media_type");//image,voice,video
            var html = "";//添加素材html内容

            var elem = $(this).parents("#materialModal").find(".materail-list .material-content .selected-mask");
            if(media_type == "image"){//选择图片
                var url = elem.parent(".material-content").find("img").attr("src");//图片地址
                var media_id = elem.parent(".material-content").attr("data-media_id");//
                var name = elem.parent(".material-content").find(".image-name").text();//图片名称
                html = "<img src='"+url+"' data-media_id='"+media_id+"'><a href='javascript:;' class='material-remove'>删除</a>";

            }else if(media_type == "voice"){//选择语音
                var media_id = elem.parent(".material-content").attr("data-media_id");
                var name = elem.parent(".material-content").find(".voice-name").text();//名称
                html = "<div class='voice-wrap' data-media_id='"+media_id+"'><span class='voice-icon'><i class='icon iconfont'>&#xe64c;</i></span><span class='voice-name'>"+name+"</span></div><a href='javascript:;' class='material-remove'>删除</a>";

            }else if(media_type == "video"){//选择视频
                var media_id = elem.parent(".material-content").attr("data-media_id");
                var name = elem.parent(".material-content").find(".video-name").text();//名称
                html = "<div class='video-wrap' data-media_id='"+media_id+"'><span class='video-icon'><i class='icon iconfont'>&#xe64d;</i></span><span class='video-name'>"+name+"</span></div><a href='javascript:;' class='material-remove'>删除</a>";

            }else if(media_type == "news"){
                var media_id = elem.parent(".material-content").attr("data-media_id");
                var news = elem.parent(".material-content").clone();
                news.find(".selected-mask").remove()
                html = "<div class='news-wrap' data-media_id='"+media_id+"'>"+news.html()+"</div><a href='javascript:;' class='material-remove'>删除</a>";

            }
            if(responseType == "0" || responseType == "1"){//被关注回复、统一回复
                var selectedConElem = $("#responseContentWrap .response-content.selected .tab-content.selected");//当前待操作区域对象元素
                selectedConElem.find(".no-content").hide();
                selectedConElem.find(".display-material-area").show().html(html);
                selectedConElem.find(".display-material-area .material-remove").one('click',function(){
                    $(this).parent(".display-material-area").hide();
                    $(this).parents(".display-content").find(".no-content").show();
                    $(this).parent(".display-material-area").html("");//重置内容

                });
            }else if(responseType == "2"){//关键词回复
                var currentTypeElem = $(".response-content.selected .keywords-response.active .rule-response-type-area li a[data-media-type='"+media_type+"']");//当前选择回复素材类型元素对象
                $(".response-content.selected .keywords-response.active .rule-response-type-area li").removeClass("selected");
                currentTypeElem.parent().addClass("selected");
                currentTypeElem
                var activeElem = $("#responseContentWrap .response-content.selected .keywords-response.active");//当前active操作区域对象元素
                activeElem.find(".tab-content").removeClass("selected");
                activeElem.find(".display-material-area").addClass("selected").html(html);
                activeElem.find(".display-material-area").attr("data-media-type",media_type);

                activeElem.find(".display-material-area .material-remove").one('click',function(){
                    //删除素材时，默认跳转到“文字”
                    currentTypeElem.parents("ul").find("li").removeClass("selected");
                    currentTypeElem.parents("ul").find("li:first").addClass("selected");
                    activeElem.find(".tab-content").removeClass("selected");
                    $(this).parents(".rule-content-area").find(".tab-content-text").addClass("selected").focus();
                    $(this).parent(".display-material-area").html("");//重置内容

                });
            }

            $("#materialModal").modal("hide");//隐藏弹出框
        });

    }
    //文本内容过滤器，返回过滤后的文本字符串，qq表情img标签过滤，转换成微信通用的格式，如"/微笑"
    //将换行符号<br>转换成微信中能识别的换行符号\n
    function contentFilter(contentHtml){//内容html
        var html = contentHtml;
        if(html == ""){
            return html;
        }

        //var reg = new RegExp('<img src="\\w*.gif" alt=".*">','g');
        var imgReg = /<img src=\".{5,50}.gif\" alt=\"(.{1,6})\">/g;//图片匹配表达式
        var emoReg = /alt=\"(\[.{1,4}\])\"/g;//qq表情表达式

        var imgMatches = html.match(imgReg);//所有图片匹配的结果，如[<img src="..">,<img src="...">...]
        var emoMatches = html.match(emoReg);//所有表情匹配的结果，如[[微笑],[色],....];
        imgMatches = imgMatches ? imgMatches : [];
        emoMatches = emoMatches ? emoMatches : [];
        for(var i = 0;i < imgMatches.length; i++){
            html = html.replace(imgMatches[i],emoMatches[i].split("=")[1].split("\"")[1]);
        }
        //将换行符<br/>转换成微信中能识别的换行符\n
        html = html.replace(/<br\s*\/?>/gi,"\n");
        //将html的转义字符转换成对应的字符，存入数据库
        html = html.replace(/&amp;/g,"&");
        return html;
    }

    //解析页面上文本类型的内容
    function showParserContent(){
        $("#responseContentWrap .real-content").each(function(index){
            var _this = $(this);
            var content = _this.html();//文本内容
            content = contentParser(content);//内容进行解析
            _this.next(".display-content").html(content);
        });

    }
    //文本内容解析器，将qq表情符号转换成img图片标签，将换行符号\r\n转换成<br/>
    function contentParser(contentHtml){
        var html = contentHtml;
        if(html == ""){
            return html;
        }
        //"(?!alt=\")(\[.{1,4}\])"  "(?!alt=\")(\[害羞\])"
        var emoReg = /(?!alt=\")(\[.{1,4}\])/g ;//qq表情表达式 /(?!alt=\")(\[.{1,4}\])/g  "(?!alt=")([害羞])"
        var emoMatches = html.match(emoReg);//所有表情匹配的结果，如[[微笑],[色],....];
        emoMatches = emoMatches ? emoMatches.unique() : [];//去除重复表情
        for(var i = 0;i < emoMatches.length; i++){
            var index = getEmotionIndex(emoMatches[i]);
            var emoStr = emoMatches[i].split("[")[1].split("]")[0];
            var temReg = eval('/(?!alt=\\")(\\['+(emoStr)+'\\])/g');//匹配不含有alt=" 但含有 "emotstr"字符串的
            html = html.replace(temReg,'<img src="./js/public/jquery.qqFace/emotion/'+index+'.gif" alt="'+emoMatches[i]+'">');
        }
        //将微信中能识别的换行符\r\n转换成html换行符<br/>
        html = html.replace(/\n/g,"<br/>");
        //将微信中的&amp;替换成&
        html = html.replace(/&amp;/g,"&");
        
        return html;
    }
    //获取指定表情符号在排列中的位置索引
    function getEmotionIndex(emo){
        var emoStr =emo;//如：[微笑]
        var emoStr = emoStr.split("[")[1].split("]")[0];
        var index = 0;
        for(var i = 0; i <emotion_title.length; i++){
            if(emoStr == emotion_title[i]){
                index = i;
                break;
            }
        }
        return index;
    }
    //qq表情位置数组
    var emotion_title = ['微笑','撇嘴','色','发呆','得意','流泪','害羞','闭嘴','睡','大哭','尴尬','发怒','调皮','呲牙','惊讶',
        '难过','酷','冷汗','抓狂','吐','偷笑','可爱','白眼','傲慢','饥饿','困','惊恐','流汗','憨笑','大兵',
        '奋斗','咒骂','疑问','嘘','晕','折磨','衰','骷髅','敲打','再见','擦汗','抠鼻','鼓掌','糗大了','坏笑',
        '左哼哼','右哼哼','哈欠','鄙视','委屈','快哭了','阴险','亲亲','吓','可怜','菜刀','西瓜','啤酒','篮球','乒乓',
        '咖啡','饭','猪头','玫瑰','凋谢','示爱','爱心','心碎','蛋糕','闪电','炸弹','刀','足球','瓢虫','便便',
        '月亮','太阳','礼物','拥抱','强','弱','握手','胜利','抱拳','勾引','拳头','差劲','爱你','NO','OK',
        '爱情','飞吻','跳跳','发抖','怄火','转圈','磕头','回头','跳绳','挥手','激动','街舞','献吻','左太极','右太极'];//qq表情主题

    /**
     * 分页初始条件
     */
    var total = 1;//分页总页面数
    var currentPage = 1;//当前页
    var pageSize = 12;//每页显示的记录数
    /**
     * 分页显示方法
     */
    function myPagination(){
        $("#materialModal .materail-list").html("");
        render(true,1,pageSize);
        //调用公共分页方法
        pagination("#page-selection",{total:total,pageSize:pageSize},render);

    }
    /**
     * 获取查询参数
     */
    function getSelectInfo(){
        var selectInfo = {
            type : $("#materialModal").attr("data-media_type")
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
            beforeSend:function(xhr){
	        	//显示“加载中。。。”
	        	$("#loading").modal('show');
		    },
		    complete:function(){
		        //隐藏“加载中。。。”
		        $("#loading").modal('hide');
		    },
            success:function(json){
                if(json == null || json == "" || json.errcode){
                    $("#page-selection").bootpag({total:1});//重新计算总页数
                    $("#materialModal  .materail-list").html("<p class='text-danger'>很抱歉,微信平台系统异常！</p>");

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
                        $("#materialModal .materail-list").html(html);
                    }else{
                        html+=madeByType(selectInfo.type,myList);
                        $("#materialModal .materail-list").html(html);

                        //绑定事件
                        $("#materialModal .materail-list .material-content").click(function(){
                            //素材选中、去除选中
                            if($(this).find(".selected-mask").length > 0){//当前已选中
                                //去除选中状态
                                $(this).find(".selected-mask").remove();
                                $("#materialModal .modal-footer .btn-confirm").addClass("no-operate");
                            }else{
                                //选中，同时去除其他素材选中状态
                                $("#materialModal .materail-list .material-content .selected-mask").remove();
                                var mask = "<div class='selected-mask'><i class='icon iconfont'>&#xe659;</i></div>";
                                $(this).append(mask);
                                $("#materialModal .modal-footer .btn-confirm").removeClass("no-operate");
                            }
                        });

                    }
                }
            },
            error:function(){
                $("#materialModal .materail-list").html("很抱歉，请求失败，网络异常！");
            }
        });
    }
    //根据不同media_type类型，拼接html
    function madeByType(type,myList){//news图文,image图片,voice语音,vedio视频
        var html = "";
        if(type == "news"){//图文
            html+="<ul class='news-list'>";
            var imgurl = "./themes/image/wechat.png";//图文素材默认图片，其本图片微信未提供第三方接口，无法展示
            for(var i = 0;i < myList.length; i++){
                var obj = myList[i];//obj={content:news_item[],media_id,update_time};
                var media_id = obj.media_id;//如："mGNTZ_NQpSb2t7R2lo5m_R28NLxs4WxJ4I25NUbmlko"
                var update_time = new Date(parseInt(obj.update_time) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
                var content = obj.content;
                html+="<li class='news-item'>";
                html+="<div class='news-item-inner clearfix material-content' data-media_id='"+media_id+"'>";
                html+="<div class='news-item-content'>";
                html+='<img title="原图被微信屏蔽了" src="'+imgurl+'" class="content-cover">';

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
            html+="<div class='image-list row'>";
            for(var i = 0;i < myList.length; i++){
                var obj = myList[i];
                html+="<div class='col-sm-2 col-md-2 image-item'><div class='image-wrap material-content' data-media_id='"+obj.media_id+"'><img src='"+obj.url+"'><span class='image-name'>"+obj.name+"</span></div></div>";
            }
            html+="</div>";
        }else if(type == "voice"){//语音
            html+="<div class='voice-list row'>";
            for(var i = 0;i<myList.length;i++){
                var obj = myList[i];
                html+="<div class='col-sm-3 col-md-3 voice-item'><div class='voice-content  material-content' data-media_id='"+obj.media_id+"'><span class='voice-icon'><i class='icon iconfont'>&#xe64c;</i></span><span class='voice-name'>"+obj.name+"</span></div></div>";
            }
            html+="</div>";

        }else if(type == "video"){//视频
            html+="<div class='video-list row'>";
            for(var i = 0;i<myList.length;i++){
                var obj = myList[i];
                html+="<div class='col-sm-3 col-md-3 video-item'><div class='video-content  material-content' data-media_id='"+obj.media_id+"'><span class='video-icon'><i class='icon iconfont'>&#xe64d;</i></span><span class='video-name'>"+obj.name+"</span></div></div>";
            }
            html+="</div>";
        }

        return html;
    }
    
    /**
     * 自定义图文添加事件
     */
    function addMyNews(){
    	var _this = $(this);
    	if(_this.parents(".item").siblings().length == 9){//最多只能添加10个
    		responseTip("1","最多只能添加10个图文!");
    		return false;
    	}
    	var clone = _this.parents(".item").clone(true);//复制 包含事件
    	//
    	_this.parents(".item").siblings().removeClass("active");
    	clone.addClass("active");
    	//重置图文信息
    	clone.find(".title,.description,.url").val("");
    	clone.find(".picUrl-area img").remove();
    	_this.parents(".item").after(clone);//插入在当前元素的后面
    }
    
    /**
     * 自定义图文上传图片-点击事件
     */
    function uploadImageFun(){
    	var _this = $(this);
		_this.parents(".item").siblings().removeClass("active");
		_this.parents(".item").addClass("active");
		//获取原图片路径
		var prevUrl = "";
		if(_this.parents(".item").find(".picUrl-area img").length > 0){
			prevUrl = _this.parents(".item").find(".picUrl-area img").attr("src");
			$("#myForm #prevUrl").val(prevUrl);
		}
		$("#myForm #imgurl").click();
    }
	
	/**
	 * 自定义图文图片上传事件
	 */
	function myNewsUploadImage(){
		$("#myForm").ajaxSubmit(
				{   type:"post",
					url:"./admin.php?c=weixin_response&a=uploadMyMessageImage",
					dataType:'json',
					beforeSend:function(xhr){
                        //显示“加载中。。。”
                        $("#loading").modal('show');
                    },
                    complete:function(){
                        //隐藏“加载中。。。”
                        $("#loading").modal('hide');
                    },
					success:function(json){
                        if(json.errorCode == 0){
                            //图片上传成功时
                            var imgurl = json.data;
                            $("#responseContentWrap .display-content-my_news .item.active .image-wrap").html("<img src='"+imgurl+"'>");
                            syncSaveMyNews();//同步到数据库
                            //responseTip(json.errorCode,"恭喜您，图片保存成功",1500);
                        }else{
                            //保存失败
                            responseTip(json.errorCode,json.errorInfo,1500);
                        }
                    },
                    error:errorResponse
				});
    	
	}
	/**
	 * 自定义图文删除图片-事件
	 */
	function deleteMyNews(){
		var _this = $(this);
		_this.parents(".item").siblings().removeClass("active");
		_this.parents(".item").addClass("active");
		var itemLength = $(this).parents(".display-content-my_news").find(".item").length;
		//获取原图片路径
		var prevUrl = "";
		var length = $(this).parents(".item").find(".picUrl-area img").length;
		if(length > 0){//有自定义图文图片时
			prevUrl = $(this).parents(".item").find(".picUrl-area img").attr("src");
		}else{//未保存的自定义图文，即无图文
			if(itemLength == 1){//为最后一个自定义图文时
				responseTip(1,"已经是最后一个自定义图文了！");
			}else{
				_this.parents(".item").remove();
			}
			return false;
		}
		myConfirmModal("确定删除此篇图文吗？",function(){
			if(prevUrl == ""){//未上传图片时
				_this.parents(".item").remove();
			}else{//已上传图片时
				$.ajax({
					type:"post",
					url:"./admin.php?c=weixin_response&a=deleteMyMessageImage",
					data:{
						"picUrl":prevUrl
					},
					dataType:"json",
					success:function(json,status){
						if(json.errorCode == 0){//删除操作成功
							
							syncSaveMyNews();//同步到数据库
							if(itemLength > 1){//非最后一个自定义图文时
								_this.parents(".item").remove();	
							}else{//最后一个自定义图文时
								var clone = _this.parents(".item").clone(true);
								clone.find(".title,.description,.url").val("");
								clone.find(".picUrl-area img").remove();
    							_this.parents(".item").after(clone);//插入在当前元素的后面
    							_this.parents(".item").remove();	
								//var responseType = $(".inner-section .response-type li.selected");//当前操作的回复类型
            					//var navId = responseType.attr("id");
            					//var href = window.location.href;
            					//href = href.split("&navFlag")[0];
            					//window.location.href = href +"&navFlag="+navId;
							}
						}else{//删除操作失败
							responseTip(json.errorCode,json.errorInfo,1500);
						}
					},
					error:errorResponse
				})
			}
		});
	}
	
	/**
	 * 异步保存自定义图文信息
	 */
	function syncSaveMyNews(){
        //同时要异步保存当前图文信息
        var responseType = $(".inner-section .response-type li.selected");//当前操作的回复类型
        var navId = responseType.attr("id");//导航id
        var id = responseType.find("a").attr("data-id");//主键id
        var use_scene = responseType.find("a").attr("data-use_scene");//0 被关注回复，1 消息统一回复 ，2关键词自动回复
        if(use_scene == 2){//关键词回复 同步流程
        	
        }else{//被关注回复、统一自动回复 同步流程
        	var media_type = $("#responseContentWrap .response-content.selected .response-content-nav li.selected a").attr("data-media-type");
	        var content = "";//文本内容
	        var url = "";//图片地址
	        var media_id = "";//图片、音频或视频的media_id
	        //当前选中操作区别
	        var selectedTabConElem =  $("#responseContentWrap .response-content.selected .tab-content.selected");
	        var newsElem = selectedTabConElem.find(".display-content-my_news .item");
	    	var itemList = [];
	    	var newsFlag = true;
	    	newsElem.each(function(){
	    		var title = $.trim($(this).find(".title").val());//标题
	    		var description = $.trim($(this).find(".description").val());//描述
	    		var url = $.trim($(this).find(".url").val());//图文链接
	    		var picUrl = $(this).find(".picUrl-area img").attr("src") || "";//图片地址
	    		itemList.push({"title":title,"description":description,"url":url,"picUrl":picUrl});
	    	});
        	if(itemList.length > 0){
        		content = JSON.stringify(itemList);//转化成json字符串	
        	}
	        $.ajax(
	        {	async:false,
	            type:"post",
	            url:"./admin.php?c=weixin_response&a=updateMessage",
	            data:{"id":id,"use_scene":use_scene,"type":media_type,"media_id":media_id,"content":content,"url":url},
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
	                	//保存成功
	                }else{
	                    responseTip(1,json.errorInfo,1500);
	                }
	            },
	            error:errorResponse
	        });
        }
        
	}

    init();//调用
});