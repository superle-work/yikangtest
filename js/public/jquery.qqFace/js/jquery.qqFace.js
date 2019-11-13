// QQ表情插件
(function($){  
	$.fn.qqFace = function(options){
		var defaults = {
			className : 'facebox',//生成表情依赖div的class
			path : './js/public/jquery.qqFace/emotion/',//表情小图片存放路径
			assign : 'content',//点击小图片时，小图片自动显示到assign id选择器对应的标签内
            clickCallback:function(){},//qq表情click点击事件
			tip : '/'//表情符号前缀
		};
		var option = $.extend(defaults, options);
		var assign = $('#'+option.assign);
		var className = option.className;
		var path = option.path;
		var tip = option.tip;
        var _this = $(this);//目标点击对象

		/*if(assign.length<=0){
			alert('缺少表情赋值对象。');
			return false;
		}*/
		var emotion_title = ['微笑','撇嘴','色','发呆','得意','流泪','害羞','闭嘴','睡','大哭','尴尬','发怒','调皮','呲牙','惊讶',
							 '难过','酷','冷汗','抓狂','吐','偷笑','可爱','白眼','傲慢','饥饿','困','惊恐','流汗','憨笑','大兵',
							 '奋斗','咒骂','疑问','嘘','晕','折磨','衰','骷髅','敲打','再见','擦汗','抠鼻','鼓掌','糗大了','坏笑',
							 '左哼哼','右哼哼','哈欠','鄙视','委屈','快哭了','阴险','亲亲','吓','可怜','菜刀','西瓜','啤酒','篮球','乒乓',
							 '咖啡','饭','猪头','玫瑰','凋谢','示爱','爱心','心碎','蛋糕','闪电','炸弹','刀','足球','瓢虫','便便',
							 '月亮','太阳','礼物','拥抱','强','弱','握手','胜利','抱拳','勾引','拳头','差劲','爱你','NO','OK',
							 '爱情','飞吻','跳跳','发抖','怄火','转圈','磕头','回头','跳绳','挥手','激动','街舞','献吻','左太极','右太极'];//qq表情主题
        
		//在页面创建生成qq表情html标签
		var strFace, labFace;
		if(_this.parent().find('.'+className).length<=0){
				strFace = '<div class="qqFace '+className+'">';
				strFace +='<span class="hook"><span class="hook_dec hook_top"></span><span class="hook_dec hook_btm"></span></span>';
				strFace +='<ul class="emotions">';

				for(var i=0; i<=104; i++){
                    labFace = "["+emotion_title[i]+"]";
					strFace += '<li class="emotions_item"><i data-gifurl="'+(path+i)+'.gif" data-title="'+labFace+'" style="background-position:-'+(i*24)+'px 0;"></i></li>';
				}
                strFace +='</ul><span class="emotions_preview"></span>';
				strFace += '</div>';
				_this.parent().append(strFace);
                //qq表情hover 事件
				_this.parent().find(".emotions_item").hover(function(){
                    var url = $(this).find("i").attr("data-gifurl");
                    var img = "<img src='"+url+"'>";
                    $(this).parents(".qqFace").find(".emotions_preview").html(img);
                });
                _this.parent().find(".qqFace").focusout(function(){
                    //$(this).hide();
                });
                //qq表情click 事件
                _this.parent().find(".emotions_item").click(options.clickCallback);


		}
		//目标点击事件
		$(this).click(function(event){

            var offset = $(this).position();
            var top = offset.top + $(this).outerHeight();
            //$('#'+id).css('top',top);
            //$('#'+id).css('left',offset.left);
			$(this).parent().find('.'+className).show();

            if (event) {
                event.stopPropagation();     // 其它浏览器下阻止冒泡
            }else{
                event.cancelBubble=true;     // ie下阻止冒泡
            }
		});
	};

})(jQuery);

jQuery.extend({ 
unselectContents: function(){ 
	if(window.getSelection) 
		window.getSelection().removeAllRanges(); 
	else if(document.selection) 
		document.selection.empty(); 
	} 
}); 
jQuery.fn.extend({ 
	selectContents: function(){ 
		$(this).each(function(i){ 
			var node = this; 
			var selection, range, doc, win; 
			if ((doc = node.ownerDocument) && (win = doc.defaultView) && typeof win.getSelection != 'undefined' && typeof doc.createRange != 'undefined' && (selection = window.getSelection()) && typeof selection.removeAllRanges != 'undefined'){ 
				range = doc.createRange(); 
				range.selectNode(node); 
				if(i == 0){ 
					selection.removeAllRanges(); 
				} 
				selection.addRange(range); 
			} else if (document.body && typeof document.body.createTextRange != 'undefined' && (range = document.body.createTextRange())){ 
				range.moveToElementText(node); 
				range.select(); 
			} 
		}); 
	}, 

	setCaret: function(){ 
		if(!$.browser.msie) return; 
		var initSetCaret = function(){ 
			var textObj = $(this).get(0); 
			textObj.caretPos = document.selection.createRange().duplicate(); 
		}; 
		$(this).click(initSetCaret).select(initSetCaret).keyup(initSetCaret); 
	}, 

	insertAtCaret: function(textFeildValue){ 
		var textObj = $(this).get(0); 
		if(document.all && textObj.createTextRange && textObj.caretPos){ 
			var caretPos=textObj.caretPos; 
			caretPos.text = caretPos.text.charAt(caretPos.text.length-1) == '' ? 
			textFeildValue+'' : textFeildValue; 
		} else if(textObj.setSelectionRange){ 
			var rangeStart=textObj.selectionStart; 
			var rangeEnd=textObj.selectionEnd; 
			var tempStr1=textObj.value.substring(0,rangeStart); 
			var tempStr2=textObj.value.substring(rangeEnd); 
			textObj.value=tempStr1+textFeildValue+tempStr2; 
			textObj.focus(); 
			var len=textFeildValue.length; 
			textObj.setSelectionRange(rangeStart+len,rangeStart+len); 
			textObj.blur(); 
		}else{ 
			textObj.value+=textFeildValue; 
		} 
	} 
});