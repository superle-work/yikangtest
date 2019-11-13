/*自定义菜单*/
$(function(){
    function init(){
        $("[data-toggle='popover']").popover();
        bindEvent();
    }
    var CAN_SAVE = true;//是否可以"保存并发布"的标识
    function bindEvent(){
        $(document).click(function(){
            $('.qqFace').hide();
        });
        //菜单编辑--重命名事件
        $("#menuContainer .inner-menu .edit-menu-name").click(menuNameEdit);
        function menuNameEdit(){
            var _this = $(this);
            //校正操作焦点
            $("#menuContainer .inner-menu-item").removeClass("selected");
            _this.parent().addClass("selected");

            var level = parseInt(_this.attr("data-level"));//菜单级别
            var name = _this.parent().find(".inner-menu-link").attr("data-name");//菜单名称
            var modal_title = "";//弹框提示信息
            var edit_menu_info = "";//菜单提示信息
            if(level == 1){
                modal_title = "修改一级菜单名称";
                edit_menu_info = "不多于4个汉字或8个字母";
            }else if(level == 2){
                modal_title = "修改二级菜单名称";
                edit_menu_info = "不多于8个汉字或16个字母";
            }
            $("#myMenuModal .modal-title").html(modal_title);
            $("#myMenuModal .edit-menu-info").html(edit_menu_info);
            $("#myMenuModal .menu-name").val(name);
            $("#myMenuModal").modal('show');//出现弹框
            //修改确认按钮

            $("#myMenuModal .btn-confirm").one('click',function(){
                var newName = $.trim($("#myMenuModal .menu-name").val());//新菜单名称
                if(newName == ""){
                    $("#myMenuModal .tip-info p").html("输入框内容不能为空");
                    return false;
                }
                _this.parent().find(".inner-menu-link").attr("data-name",newName);
                _this.parent().find(".inner-menu-link").find(".menu-name").html(newName);
                _this.parent().click();
                $("#myMenuModal").modal('hide');
            });
            return false;
        };
        //菜单-添加事件
        $("#menuContainer .menu-manage .add-menu").click(menuAdd);
        function menuAdd(){
            var _this = $(this);
            var level = parseInt(_this.attr("data-level"));//父菜单级别

            var modal_title = "";//弹框提示信息
            var add_menu_info = "";//添加菜单提示信息
            var CAN_ADD = true;//是否可以添加菜单的标识
            var subMenuCount = 0;//当前父级菜单的子菜单个数

            if(level == 0){//添加一级菜单

                subMenuCount = $("#menuContainer .menu-list-box .inner-menu").length;;//当前子菜单的个数
                if(parseInt(subMenuCount) == 3){
                    //若一级菜单个数已达到最大限额，给出提示
                    CAN_ADD = false;
                    $("#myModal .modal-body").html("<p class='text-danger'><b>最多只能添加3个一级菜单，当前已达设置上线</b></p>");
                    $("#myModal").modal('show');
                    //定时器，2秒后模态框自动关闭
                    setTimeout(function(){
                        $("#myModal").modal('hide');
                        return false;
                    },2000);

                }
                modal_title = "添加一级菜单";
                add_menu_info = "还能添加"+(3-subMenuCount)+"个一级菜单，请输入名称（4个汉字或8个字母以内）";
            }else if(level == 1){//添加二级子菜单
                //校证操作焦点
                $("#menuContainer .inner-menu-item").removeClass("selected");
                _this.parent().addClass("selected");
                //若二级菜单个数已达到最大限额，给出提示
                subMenuCount = _this.parents(".inner-menu").find(".menu-item-level-2").length;//当前子菜单个数
                if(parseInt(subMenuCount) == 5){
                    CAN_ADD = false;
                    $("#myModal .modal-body").html("<p class='text-danger'><b>最多只能添加5个二级菜单，当前已达设置上线</b></p>");
                    $("#myModal").modal('show');
                    //定时器，2秒后模态框自动关闭
                    setTimeout(function(){
                        $("#myModal").modal('hide');
                        return false;
                    },2000);
                }

                modal_title = "添加二级菜单";
                add_menu_info = "还能添加"+(5-subMenuCount)+"个二级菜单，请输入名称（8个汉字或16个字母以内）";
            }
            if(CAN_ADD == false){
                return false;
            }
            $("#myMenuModal .modal-title").html(modal_title);
            $("#myMenuModal .menu-name").val("");
            $("#myMenuModal .add-menu-info").html(add_menu_info);
            $("#myMenuModal").modal('show');//出现弹框

            //确认按钮-保存新菜单
            $("#myMenuModal .btn-confirm").one('click',function(){
                var newName = $.trim($("#myMenuModal .menu-name").val());//新菜单名称
                if(newName == ""){
                    $("#myMenuModal .tip-info p").html("输入框内容不能为空");
                    return false;
                }
                //添加新菜单
                //若当前添加的是二级菜单且为父级的第一个二级子菜单，则清除一级菜单上的消息
                if(level == 1 && subMenuCount == 0){
                    _this.parent().find(".inner-menu-link").attr("data-type","");
                    _this.parent().find(".inner-menu-link").attr("data-key","");
                }
                //生产新菜单，追加到同级菜单的最后面
                var html = "";
                var target = null;//目标元素 ：当前添加的菜单对象
                if(level == 0){//添加的为一级菜单时
                    var id = $("#menuContainer .inner-menu").length + 1;//一级菜单的id,作为其子菜单的pid，标识父子关系
                    if(subMenuCount == 0){
                        html = '<div class="inner-menu inner-menu-first">'
                    }else{
                        html = '<div class="inner-menu">'
                    }
                    html+= '<div class="inner-menu-item menu-item-level-1">'
                             +'<a href="javascript:;" class="edit-menu-name" data-level="1" data-toggle="popover" data-trigger="hover" data-placement="left" data-content="重命名"><i class="icon iconfont">&#xe623;</i></a>'
                             +'<a href="javascript:;" class="inner-menu-link" data-id="'+id+'" data-pid="0" data-name="'+newName+'" data-type="" data-level="1" data-key=""><span class="menu-name">'+newName+'</span></a>'
                             +'<a href="javascript:;" class="add-menu" data-level="1" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="添加" ><i class="icon iconfont">&#xe61f;</i></a>'
                             +'<a href="javascript:;" class="delete-menu" data-level="1" data-toggle="popover" data-trigger="hover" data-placement="left" data-content="确认删除吗?"><i class="icon iconfont">&#xe624;</i></a>'
                    html+='</div>'
                    html+='</div>';
                    $("#menuContainer .menu-list-box").append(html);
                    //添加菜单成功后 为其绑定事件
                    target = $("#menuContainer .menu-list-box").find(".inner-menu-item:last");//新添加的子菜单
                    target.find(".edit-menu-name").click(menuNameEdit);
                    target.find(".add-menu").click(menuAdd);
                    target.find(".delete-menu").click(menuDelete);
                    target.click(menuClick);

                }else if(level == 1){//添加的为二级菜单时
                    var pid = _this.parent().find(".inner-menu-link").attr("data-id");//父级id
                    var id = pid+''+(subMenuCount+1);//子菜单的id值
                    if(subMenuCount == 0){
                        html = '<div class="inner-menu-item inner-subMenu-item menu-item-level-2 inner-subMenu-item-first">'
                    }else{
                        html = '<div class="inner-menu-item inner-subMenu-item menu-item-level-2">'
                    }
                    html+='<a href="javascript:;" class="edit-menu-name" data-level="2" data-toggle="popover" data-trigger="hover" data-placement="left" data-content="重命名"><i class="icon iconfont">&#xe623;</i></a>'
                        +'<a href="javascript:;" class="inner-menu-link" data-id="'+id+'" data-pid="'+pid+'" data-name="'+newName+'" data-type="" data-level="2" data-key=""><i class="icon iconfont">&#xe620;</i><span class="menu-name">'+newName+'</span></a>'
                        +'<a href="javascript:;" class="delete-menu" data-level="2" data-toggle="popover" data-trigger="hover" data-placement="left" data-content="确认删除吗?"><i class="icon iconfont">&#xe624;</i></a>'
                    html+='</div>'
                    _this.parents(".inner-menu").append(html);
                    //添加菜单成功后 为其绑定事件
                    target = _this.parents(".inner-menu").find(".inner-menu-item:last");//新添加的子菜单
                    target.find(".edit-menu-name").click(menuNameEdit);
                    target.find(".delete-menu").click(menuDelete);
                    target.click(menuClick);
                }
                $("[data-toggle='popover']").popover();//启用弹框组件

                //手动触发其click事件 1、校正活动焦点 2、让右边栏内容处于活动中 提醒用户进行相应的操作
                target.click();

                $("#myMenuModal").modal('hide');
            });
            //$("#myMenuModal .menu-name").get(0).focus();
        };
        
        //菜单名称输入框 enter事件
		$("#myMenuModal .menu-name").keydown(function(event){
			event = event || window.event;
			if(event.keyCode == 13){//enter事件
				$("#myMenuModal .btn-confirm").click();
			}
		});
		
        //菜单点击事件
        $("#menuContainer .inner-menu .inner-menu-item").click(menuClick);
        function menuClick(){
            var _this = $(this);

            var level = _this.find(".inner-menu-link").attr("data-level");//菜单级别
            var name = _this.find(".inner-menu-link").attr("data-name");//菜单名称
            var type = _this.find(".inner-menu-link").attr("data-type");//菜单类型 view/media_id/view_limited/click/...
            var hasChild = false;//是否有子菜单标识，默认为 false
            var text = "";
            if(level == 1){
                text +="一级菜单：";
                if(_this.next(".menu-item-level-2").length > 0){//有二级子菜单
                    hasChild = true;
                }
            }else if(level == 2){
                text +="二级菜单：";
                if(_this.next(".menu-item-level-3").length > 0){//有三级子菜单
                    hasChild = true;
                }

            }else if(level == 3){
                text+="三级菜单：";
            }
            text +=name;
            $("#menuContainer .inner-main .menu-tip-info").html(text);
            if(_this.hasClass("selected")){
                return false;
            }
            //菜单选中效果
            $("#menuContainer .inner-menu .inner-menu-item").removeClass("selected");
            _this.addClass("selected");
            //右边框内容区域
            //判断当前菜单是否还有子菜单
            if(hasChild == false){//无子菜单时
                //判断是否已经为当前菜单设置内容
                if(type == null || type ==""){//未设置内容
                    if(level == 1){//一级菜单 （全部内容设置选项都显现）
                        $(".menu-setting-area-warp .inner-menu-content .action_content").hide();
                        $(".menu-setting-area-warp .inner-menu-content #index .initialCreate").show();
                        $(".menu-setting-area-warp .inner-menu-content #index .skip-to-page").show();
                        $(".menu-setting-area-warp .inner-menu-content #index").show();
                    }else if(level == 2){
                        var html = "";//提示信息
                        html = '请设置“'+name+'”菜单的内容';
                        $(".menu-setting-area-warp .inner-menu-content #index .action_tips").html(html);
                        $(".menu-setting-area-warp .inner-menu-content .action_content").hide();
                        $(".menu-setting-area-warp .inner-menu-content #index .initialCreate").hide();
                        $(".menu-setting-area-warp .inner-menu-content #index .skip-to-page").show();
                        $(".menu-setting-area-warp .inner-menu-content #index").show();
                    }
                }else if(type =="view"){
                    //已设置内容，目前只对url地址跳转类型进行处理
                    var url = _this.find(".inner-menu-link").attr("data-url");//url
                    $(".menu-setting-area-warp .inner-menu-content .action_content").hide();
                    $(".menu-setting-area-warp .inner-menu-content #view #viewDiv").html(url);
                    $(".menu-setting-area-warp .inner-menu-content #view").show();
                }else if(type =="media_id" || type == "view_limited"){//发送消息类型：图文消息
                    var media_id = _this.find(".inner-menu-link").attr("data-media_id");//data-media_id
                    //此处无法获悉为何种素材及其对应内容
                    $(".menu-setting-area-warp .inner-menu-content .action_content").hide();
                    $(".menu-setting-area-warp .inner-menu-content #message .action_tips").html("订阅者点击该子菜单会收到以下消息(已设置的素材信息被微信屏蔽了，此处无法获悉其内容，但不影响用户使用)");
                    $(".menu-setting-area-warp .inner-menu-content #message .display-content .display-material-area").html("");
                    $(".menu-setting-area-warp .inner-menu-content #message").show();
                    //暂不做相应处理
                }else if(type == "miniprogram"){//为小程序时
                	var url = _this.find(".inner-menu-link").attr("data-url");//url
                	var appid = _this.find(".inner-menu-link").attr("data-appid");//appid
                	var pagepath = _this.find(".inner-menu-link").attr("data-pagepath");//pagepath
                    $(".menu-setting-area-warp .inner-menu-content .action_content").hide();
                    $(".menu-setting-area-warp .inner-menu-content #miniProgram #urlText").val(url);
                    $(".menu-setting-area-warp .inner-menu-content #miniProgram #appidText").val(appid);
                    $(".menu-setting-area-warp .inner-menu-content #miniProgram #pagepathText").val(pagepath);
                    $(".menu-setting-area-warp .inner-menu-content #miniProgram").show();
                	
                }else{
                    //事件类型处理
                    var key = _this.find(".inner-menu-link").attr("data-key");//data-key
                    $(".menu-setting-area-warp .inner-menu-content .action_content").hide();
                    $(".menu-setting-area-warp .inner-menu-content #event #typeText").val(type);
                    $(".menu-setting-area-warp .inner-menu-content #event #keyText").val(key);
                    $(".menu-setting-area-warp .inner-menu-content #event").show();
                }
            }else{//有子菜单时
                $(".menu-setting-area-warp .inner-menu-content .action_content").hide();
                if(level == 1){
                    //当前菜单为一级菜单时
                    var subMenuCount = _this.parent(".inner-menu").find(".menu-item-level-2").length;//当前子菜单个数
                    if(subMenuCount== 5){
                        //二级子菜单个数已满，给出提示
                        $(".menu-setting-area-warp .inner-menu-content #none .action_tips").html("你已添加满5个二级菜单");
                        $(".menu-setting-area-warp .inner-menu-content #none .initialCreate").hide();
                    }else{
                        var html = "";//提示信息
                        html = '已为“'+name+'”添加了二级菜单，无法设置其他内容。<br/>您还可以添加'+(5-subMenuCount)+'个二级菜单';
                        $(".menu-setting-area-warp .inner-menu-content #none .action_tips").html(html);
                        $(".menu-setting-area-warp .inner-menu-content #none .initialCreate").show();//添加二级子菜单大图标显现
                    }
                }
                $(".menu-setting-area-warp .inner-menu-content #none").show();

            }

        };
        //右边栏“发送消息”大图标点击事件
        $(".menu-setting-area-warp .inner-menu-content .action_content .sendMsg").click(function(){
            $(".menu-setting-area-warp .inner-menu-content .action_content").hide();
            $(".menu-setting-area-warp .inner-menu-content #message .display-material-area").html("");
            $(".menu-setting-area-warp .inner-menu-content #message").show();
        });
        //发送消息--文字、图片、语音、视频 导航的点击事件
        $(".response-content-nav li").click(function(){
            var media_type = $(this).find("a").attr("data-media-type");//类型
            $(this).parent().find("li").removeClass("selected");
            $(this).addClass("selected");
            //同步更新内容框内容
            $(this).parents(".response-content").find(".tab-content").removeClass("selected");
            $(this).parents(".response-content").find(".tab-content-"+media_type).addClass("selected");
            $(".link-oper-area").hide();//超链接弹框隐藏
        });

        //右边栏“跳转到网页”大图标点击事件
        $(".menu-setting-area-warp .inner-menu-content .action_content .skip-to-page").click(function(){
            $(".menu-setting-area-warp .inner-menu-content .action_content").hide();
            $(".menu-setting-area-warp .inner-menu-content #url #urlText").val("");
            $(".menu-setting-area-warp .inner-menu-content #url").show();

        });
        //右边栏“添加二级菜单”大图标点击事件
        $(".menu-setting-area-warp .inner-menu-content .action_content .initialCreate").click(function(){
            $("#menuContainer .inner-menu-item.selected").find(".add-menu").click();
        });
        //右边栏“添加事件”大图标点击事件
        $(".menu-setting-area-warp .inner-menu-content .action_content .addEvent").click(function(){
            $(".menu-setting-area-warp .inner-menu-content .action_content").hide();
            $(".menu-setting-area-warp .inner-menu-content #event #typeText").val("click");
            $(".menu-setting-area-warp .inner-menu-content #event #keyText").val("");
            $(".menu-setting-area-warp .inner-menu-content .action_content.event").show();
        });
        //右边栏“跳转小程序”大图标点击事件
        $(".menu-setting-area-warp .inner-menu-content .action_content .addMiniProgram").click(function(){
            $(".menu-setting-area-warp .inner-menu-content .action_content").hide();
            
            $(".menu-setting-area-warp .inner-menu-content #miniProgram #appidText").val("");
            $(".menu-setting-area-warp .inner-menu-content #miniProgram #urlText").val("");
            $(".menu-setting-area-warp .inner-menu-content #miniProgram #pagepathText").val("");
            $(".menu-setting-area-warp .inner-menu-content #miniProgram .oper-error-tip").hide();
            $(".menu-setting-area-warp .inner-menu-content .action_content.miniProgram").show();
        });
        //小程序的“保存”
        $(".menu-setting-area-warp .inner-menu-content .action_content #miniProgramSave").click(function(){
        	var menu = $("#menuContainer .inner-menu-item.selected");//当前操作菜单
        	var _this = $(this);
        	var _form = _this.parent().parent();
        	var url = _form.find("#urlText").val();
        	var appid = _form.find("#appidText").val();
        	var pagepath = _form.find("#pagepathText").val();
        	url = $.trim(url);
        	appid = $.trim(appid);
        	pagepath = $.trim(pagepath);
        	//判断url的合法性
            var reg = /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
            if(!reg.test(url)){//url不合法
                menu.find(".inner-menu-link").attr("data-url","");//同步将菜单的url置为空
                $(".menu-setting-area-warp .inner-menu-content #miniProgram #urlFail").show();
            }else{//url合法
                menu.find(".inner-menu-link").attr("data-type","minprogram");
                menu.find(".inner-menu-link").attr("data-url",url);
                $(".menu-setting-area-warp .inner-menu-content #miniProgram #urlFail").hide();
            }
            if(url == "" || appid == "" || pagepath == ""){//输入的信息不完整
            	_form.find(".oper-error-tip").show();
            	return false;
            }
            _form.find(".oper-error-tip").hide();
            menu.find(".inner-menu-link").attr("data-type","miniprogram");
            menu.find(".inner-menu-link").attr("data-url",url);
            menu.find(".inner-menu-link").attr("data-appid",appid);
            menu.find(".inner-menu-link").attr("data-pagepath",pagepath);
            
            
        });
        
        //右边栏“修改”跳转url地址按钮点击事件
        $(".menu-setting-area-warp .inner-menu-content #view .btn_editing").click(function(){
            $(".menu-setting-area-warp .inner-menu-content .action_content").hide();
            //将当前菜单的url值取到，并显示在右边栏输入框
            var menu = $("#menuContainer .inner-menu-item.selected");
            var url = menu.find(".inner-menu-link").attr("data-url");
            $(".menu-setting-area-warp .inner-menu-content #url #urlFail").hide()
            $(".menu-setting-area-warp .inner-menu-content #url #urlText").val(url);
            $(".menu-setting-area-warp .inner-menu-content #url").show();
            
        });
        //右边栏-页面地址-失去焦点事件：保存新页面地址
        $(".menu-setting-area-warp .inner-menu-content #url #urlText").blur(function(){
            var menu = $("#menuContainer .inner-menu-item.selected");//当前操作菜单
            var url = $.trim($(this).val());

            //判断url的合法性
            var reg = /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
            if(!reg.test(url)){//url不合法
                menu.find(".inner-menu-link").attr("data-url","");//同步将菜单的url置为空
                $(".menu-setting-area-warp .inner-menu-content #url #urlFail").show();
            }else{//url合法
                menu.find(".inner-menu-link").attr("data-type","view");
                menu.find(".inner-menu-link").attr("data-url",url);
                $(".menu-setting-area-warp .inner-menu-content #url #urlFail").hide();
            }
        }).focus(function(){//隐藏错误提示
                $(".menu-setting-area-warp .inner-menu-content #url #urlFail").hide();
        });
        //右边栏-EventKey-失去焦点获取焦点事件：保存新的eventType,eventKey
        $(".menu-setting-area-warp .inner-menu-content #event #keyText").blur(function(){
            //获取type值和key值
            var menu = $("#menuContainer .inner-menu-item.selected");//当前操作菜单
            var key = $.trim($(this).val());
            var type =  $(".menu-setting-area-warp .inner-menu-content #event #typeText").val();
            if(key == ""){//key不合法
                $(".menu-setting-area-warp .inner-menu-content #event #keyFail").show();
            }else{//key合法
                menu.find(".inner-menu-link").attr("data-type",type);
                menu.find(".inner-menu-link").attr("data-key",key);
                $(".menu-setting-area-warp .inner-menu-content #event #keyFail").hide();
            }
        }).focus(function(){
                $(".menu-setting-area-warp .inner-menu-content #event #keyFail").hide();
        });
        //右边栏-EventKey-事件：eventType下拉选择事件，保存新的eventType,eventText
        $(".menu-setting-area-warp .inner-menu-content #event #typeText").change(function(){
            var menu = $("#menuContainer .inner-menu-item.selected");//当前操作菜单
            var type = $(this).val();
            //var key = $.trim($(".menu-setting-area-warp .inner-menu-content #event #keyText").val());
            menu.find(".inner-menu-link").attr("data-type",type);

        });
        //菜单调整排序功能(上移、下移)
        $("#menuContainer .inner-menu-item .menu-order").click(menuMove);
        function menuMove(){
            var _this = $(this);
            var _self;//存放当前操作菜单区域div
            var elem;//存放待交换位置的菜单区域div,将_self与elem交换位置即实现调序
            var level = _this.attr("data-level");//菜单级别:1/2
            //校正操作焦点
            $("#menuContainer .inner-menu-item").removeClass("selected");
            _this.parent().addClass("selected");
            if(_this.hasClass("menu-up")){
                //为“上移”操作,与上一个菜单区域交换位置
                if(level == 2){
                    //菜单为二级菜单时
                    _self = _this.parent(".inner-menu-item");
                    elem = _self.prev();//上一个菜单
                    if(elem.hasClass("inner-subMenu-item-first")){
                        //若elem菜单为第一个子菜单,去掉当前操作菜单第一子菜单标识类,为交换位置菜单添加此标识类
                        elem.removeClass("inner-subMenu-item-first");
                        _self.addClass("inner-subMenu-item-first")
                    }
                    if(_self.hasClass("inner-subMenu-item-last")){
                        //若当前菜单为最后一项子菜单，去掉其最后子菜单标识类，为当前菜单添加上此标识类
                        _self.removeClass("inner-subMenu-item-last")
                        elem.addClass("inner-subMenu-item-last");
                    }
                }else if(level == 1){
                    _self = _this.parents(".inner-menu");
                    elem = _self.prev();//上一个菜单区
                    if(elem.hasClass("inner-menu-first")){
                        //若elem菜单为第一个菜单,去掉当前操作菜单第一菜单标识类,为交换位置菜单添加此标识类
                        elem.removeClass("inner-menu-first");
                        _self.addClass("inner-menu-first")
                    }
                    if(_self.hasClass("inner-menu-last")){
                        //若当前菜单为最后菜单，去掉其最后菜单标识类，为当前菜单添加上此标识类
                        _self.removeClass("inner-menu-last")
                        elem.addClass("inner-menu-last");
                    }
                }
                //交换位置--上移
                _self.insertBefore(elem)

            }else{
                //为“下移”操作，与下一个菜单区域交换位置
                if(level == 2){
                    //菜单为两级菜单时
                    _self = _this.parent(".inner-menu-item");
                    elem = _self.next();
                    if(_self.hasClass("inner-subMenu-item-first")){
                        //若当前菜单为第一个子菜单,去掉当前操作菜单第一子菜单标识类,为交换位置菜单添加此标识类
                        _self.removeClass("inner-subMenu-item-first");
                        elem.addClass("inner-subMenu-item-first")
                    }
                    if(elem.hasClass("inner-subMenu-item-last")){
                        //若待交换菜单为最后一项子菜单，去掉其最后子菜单标识类，为当前菜单添加上此标识类
                        elem.removeClass("inner-subMenu-item-last")
                        _self.addClass("inner-subMenu-item-last");
                    }
                }else if(level == 1){
                    _self = _this.parents(".inner-menu");
                    elem = _self.next();
                    if(_self.hasClass("inner-menu-first")){
                        //若当前菜单为第一个菜单,去掉当前操作菜单第一菜单标识类,为交换位置菜单添加此标识类
                        _self.removeClass("inner-menu-first");
                        elem.addClass("inner-menu-first")
                    }
                    if(elem.hasClass("inner-menu-last")){
                        //若待交换菜单为最后菜单，去掉其最后菜单标识类，为当前菜单添加上此标识类
                        elem.removeClass("inner-menu-last")
                        _self.addClass("inner-menu-last");
                    }
                }
                //交换位置--下移
                _self.insertAfter(elem);

            }
        };
        //菜单删除事件
        $("#menuContainer .inner-menu-item .delete-menu").click(menuDelete);
        function menuDelete(){
            //去除菜单选中状态
            $("#menuContainer .inner-menu .inner-menu-item").removeClass("selected");
            var parent = $(this).parent(".inner-menu-item");
            var level = parent.find(".inner-menu-link").attr("data-level");//菜单级别
            var name = parent.find(".inner-menu-link").attr("data-name");//菜单名称
            var tipInfo = "";
            if(level == 1){
                tipInfo = "一级菜单“"+name+"”";
                if(parent.next(".inner-subMenu-item").length > 0){
                    tipInfo+="及其所有子菜单";
                }
            }else if(level == 2){
                tipInfo = "二级菜单“"+name+"”";
            }
            if(level == 2){
                parent.remove();
            }else if(level == 1){
                parent.parent(".inner-menu").remove();
            }
            $(".menu-setting-area-warp .inner-menu-content .menu-tip-info").html("");
            $(".menu-setting-area-warp .inner-menu-content .action_content").hide();
            $(".menu-setting-area-warp .inner-menu-content #none .initialCreate").hide();
            $(".menu-setting-area-warp .inner-menu-content #none .action_tips").html("你可以点击左侧菜单或添加一个新菜单，然后设置菜单内容");
            $(".menu-setting-area-warp .inner-menu-content #none").show();
        }

        //文字输入框-键盘事件-keyup--显示剩余字数,总字数不得超过600个,同时维护隐藏的字符内容
        $(".response-content .display-content-text").keyup(function(){
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
        var cursor = -1; // 光标位置
        document.onselectionchange = function () {
            if (window.getSelection) {// IE9 and non-IE
                var selection =window.getSelection();
                var targetNode = selection.extentNode;//当前获取焦点的结点：文本结点，或者为父级div.display-content
                if(!targetNode){return false;}
                if(targetNode.nodeType == 3){
                    //为文本结点时
                    var text = targetNode.nodeValue;
                    var parentElement = targetNode.parentElement;
                    if($(parentElement).hasClass("display-content")){
                        var html = $(parentElement).html();//所有内容
                        var length = html.split(text)[0].length;
                        cursor = selection.focusOffset + length;
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
        $(".right-section  .response-content .icon-emotion").qqFace({
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
        $(".right-section .response-content .icon-link").click(function(event){
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
        $(".right-section .response-content .link-cancel").click(function(){
            $(this).parents(".link-oper-area").hide();
        });
        //超链接操作区域-添加
        $(".right-section .response-content .link-add").click(function(event){
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
        //移出素材事件 重新选择
        $(".response-content .material-remove").one('click',function(){
                $(this).parent(".display-material-area").hide();
                $(this).parents(".display-content").find(".no-content").show();
                $(this).parent(".display-material-area").html("");//重置内容
        });
        //从素材库中选择
        $(".response-content .create-access a").click(function(){
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
            }else if(media_type == "news"){
                title = "请选择图文消息";
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
            var media_id = "";
            var media_type = $(this).parents("#materialModal").attr("data-media_type");//image,voice,video
            var html = "";//添加素材html内容

            var elem = $(this).parents("#materialModal").find(".materail-list .material-content .selected-mask");
            if(media_type == "image"){//选择图片
                var url = elem.parent(".material-content").find("img").attr("src");//图片地址
                media_id = elem.parent(".material-content").attr("data-media_id");//
                var name = elem.parent(".material-content").find(".image-name").text();//图片名称
                html = "<img src='"+url+"' data-media_id='"+media_id+"'><a href='javascript:;' class='material-remove'>删除</a>";

            }else if(media_type == "voice"){//选择语音
                media_id = elem.parent(".material-content").attr("data-media_id");
                var name = elem.parent(".material-content").find(".voice-name").text();//名称
                html = "<div class='voice-wrap' data-media_id='"+media_id+"'><span class='voice-icon'><i class='icon iconfont'>&#xe64c;</i></span><span class='voice-name'>"+name+"</span></div><a href='javascript:;' class='material-remove'>删除</a>";

            }else if(media_type == "video"){//选择视频
                media_id = elem.parent(".material-content").attr("data-media_id");
                var name = elem.parent(".material-content").find(".video-name").text();//名称
                html = "<div class='video-wrap' data-media_id='"+media_id+"'><span class='video-icon'><i class='icon iconfont'>&#xe64d;</i></span><span class='video-name'>"+name+"</span></div><a href='javascript:;' class='material-remove'>删除</a>";

            }else if(media_type == "news"){
                media_id = elem.parent(".material-content").attr("data-media_id");
                var news = elem.parent(".material-content").clone();
                news.find(".selected-mask").remove()
                html = "<div class='news-wrap' data-media_id='"+media_id+"'>"+news.html()+"</div><a href='javascript:;' class='material-remove'>删除</a>";

            }

            var selectedConElem = $("#message .response-content.selected .tab-content.selected");//当前待操作区域对象元素
            selectedConElem.find(".no-content").hide();
            selectedConElem.find(".display-material-area").html(html).show();
            selectedConElem.find(".display-material-area .material-remove").one('click',function(){
                $(this).parent(".display-material-area").hide();
                $(this).parents(".display-content").find(".no-content").show();
                $(this).parent(".display-material-area").html("");//重置内容

            });

            var menu = $("#menuContainer .inner-menu-item.selected");//当前操作菜单
            menu.find(".inner-menu-link").attr("data-type","media_id");
            menu.find(".inner-menu-link").attr("data-media_id",media_id);
            $("#materialModal").modal("hide");//隐藏弹出框
        });
        //保存并发布
        $("#menuContainer #savePubBtn").click(function(){
            //var selectedMenu = $("#menuContainer .inner-menu-item.selected");//当前选中的菜单
            var menus = $("#menuContainer .inner-menu-item");//所有菜单
            menus.each(function(){
                var selectedMenu = $(this);

                if(selectedMenu !=null){
                    //判断当前菜单有无设置内容，没有设置内容则给出提示
                    var level = selectedMenu.find(".inner-menu-link").attr("data-level");//菜单等级
                    if(level == 1){
                        var subMenuCount = selectedMenu.parent(".inner-menu").find(".menu-item-level-2").length;//当前子菜单个数;
                        if(subMenuCount == 0){//未设置子菜单时
                            //判断当前菜单是否为自己为设置内容
                            var type = selectedMenu.find(".inner-menu-link").attr("data-type");

                            if(type == ""){
                                //给出提示，菜单未设置内容,无法保存发布
                                CAN_SAVE = false;
                                return false;
                            }else{
                                if(type == "view"){//菜单类型为地址
                                    var url = selectedMenu.find(".inner-menu-link").attr("data-url");
                                    if(url == ""){//为空时
                                        CAN_SAVE = false;
                                        return false;
                                    }else{
                                        CAN_SAVE = true;
                                    }
                                }else{
                                    CAN_SAVE = true;
                                }
                            }
                        }else{//有子菜单
                            CAN_SAVE = true;
                        }
                    }else if(level == 2){
                        var type = selectedMenu.find(".inner-menu-link").attr("data-type");
                        if(type == ""){
                            //给出提示，菜单未设置内容,无法保存发布
                            CAN_SAVE = false;
                            return false;
                        }else{
                            if(type == "view"){//菜单类型为地址
                                var url = selectedMenu.find(".inner-menu-link").attr("data-url");
                                if(url == ""){//为空时
                                    CAN_SAVE = false;
                                    return false;
                                }else{
                                    CAN_SAVE = true;
                                }
                            }else{
                                CAN_SAVE = true;
                            }
                        }
                    }
                }
            });
            if(CAN_SAVE == false){
                $("#myModal .modal-body").html("<p class='text-danger'>请设置当前菜单内容</p>");
                $("#myModal").modal('show');
                //定时器，1.5秒后模态框自动关闭
                setTimeout(function(){
                    $("#myModal").modal('hide');
                },1500);
                return false;
            }
            CAN_SAVE = true;

            var menuList = [];//存放所有微信菜单属性值
            //读取微信菜单
            var menuElems = $("#menuContainer .inner-menu .inner-menu-item .inner-menu-link");//所有菜单元素
            menuElems.each(function(){
                var menu = {};//存放单个菜单属性值
                //var level = $(this).attr("data-level");//菜单级别 :1/2
                var name = $(this).attr("data-name");//菜单名称
                var id = $(this).attr("data-id");//菜单id，
                var pid = $(this).attr("data-pid");//菜单父级id,为0时无父级
                var type = $(this).attr("data-type");//菜单类型，view/media_id/view_limited/click/...
                var code = "";
                var pagepath = "";
                var appid = "";
                if(type == "view"){//跳转页面
                    code = $(this).attr("data-url");
                }else if(type == "media_id" || type == "view_limited"){//发送信息 图文、文字、图片、声音、视频时type为media_id，为view_limited时仅仅是发送图文的url地址（这里我们只能获取素材的media_id）
                    code = $(this).attr("data-media_id");
                }else if(type == "miniprogram"){//小程序
                	code = $(this).attr("data-url");
                	appid = $(this).attr("data-appid");
                	pagepath = $(this).attr("data-pagepath");
                	menu.appId = appid;
                	menu.pagepath = pagepath;
                }else{//事件
                    code = $(this).attr("data-key");
                }
                menu = {"id":id,"pid":pid,"name":name,"type":type,"code":code};
                if(type == "miniprogram"){
                	menu = $.extend(true,{},menu,{"appid":appid,"pagepath":pagepath});
                }
                menuList.push(menu);
            });
            $.ajax({
                url:"./admin.php?c=weixin_menu&a=setWechatMenu",
                type:"post",
                data:{"menuList":JSON.stringify(menuList)},
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
                        },1500);
                    }else{
                        $("#myModal .modal-body").html("<p class='text-danger'>"+json.errorInfo+"</p>");
                        $("#myModal").modal('show');
                        //定时器，1.5秒后模态框自动关闭
                        setTimeout(function(){
                            $("#myModal").modal('hide');
                        },1500);
                    }
                },
                error:errorResponse
            });

        });
    }

    //文本内容过滤器，返回过滤后的文本字符串，qq表情img标签过滤，转换成微信通用的格式，如"/微笑"
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
        return html;
    }
    //文本内容解析器，将qq表情符号转换成img图片标签
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

    init();
});