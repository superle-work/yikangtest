$(function(){
	var idList = [];

    /**
     * 页面初始化
     */
    function init(){
    	$(".category-list li a").each(function(){
    		var fir = $(this).attr('fir');
    		var sec = $(this).attr('sec');
    		var thr = $(this).attr('thr');
    		var fou = $(this).attr('fou');
    		var wrap = $(this).parents('.category-item-wrap');
    		var next_wrap = wrap.next()
    		var wrap_rank = next_wrap.attr('rank');
    		var is_last = 1;
    		next_wrap.find('.category-list li a').each(function(){
				if(wrap_rank == '2' && fir && $(this).attr('fir') == fir){
					is_last = 0;
        		}else if(wrap_rank == '3' && fir && sec && $(this).attr('fir') == fir && $(this).attr('sec') == sec){
					is_last = 0;
        		}else if(wrap_rank == '4' && fir && sec && thr && $(this).attr('fir') == fir && $(this).attr('sec') == sec && $(this).attr('thr') == thr){
					is_last = 0;
        		}
    		});
    		if(is_last == 0){
    			$(this).find("span.selected-category").hide();
    		}
    	});
		bindEvent();
    }
    /**
     * 事件绑定
     */
    function bindEvent(){
        //分类点击事件
        selectCategory();
    }
    
    function bindEvent(){
    	//显示下一级分类
    	$(".category-list li a").click(function(){
    		var fir = $(this).attr('fir');
    		var sec = $(this).attr('sec');
    		var thr = $(this).attr('thr');
    		var fou = $(this).attr('fou');
    		var wrap = $(this).parents('.category-item-wrap');
    		var next_wrap = wrap.next()
    		var wrap_rank = wrap.attr('rank');
    		wrap.find('.selected').removeClass('selected');
    		$(this).parent().addClass('selected');
    		$("#content .select-category .category-item-wrap").each(function(){
    			if($(this).attr('rank') > wrap_rank){
    				$(this).addClass('hidden');
    				$(this).find('.selected').removeClass('selected');
    				$(this).find(".category-list li").addClass('hidden');
    			}
    		});
    		if(wrap_rank == '1'){
    			next_wrap.find(".category-list li a").each(function(){
    				if(fir && $(this).attr('fir') == fir){
    					$(this).parent().removeClass('hidden');
    				}
    			});
    		}else if(wrap_rank == '2'){
    			next_wrap.find(".category-list li a").each(function(){
    				if(fir && sec && $(this).attr('fir') == fir && $(this).attr('sec') == sec){
    					$(this).parent().removeClass('hidden');
    				}
    			});
    		}else if(wrap_rank == '3'){
    			next_wrap.find(".category-list li a").each(function(){
    				if(fir && sec && thr && $(this).attr('fir') == fir && $(this).attr('sec') == sec && $(this).attr('thr') == thr){
    					$(this).parent().removeClass('hidden');
    				}
    			});
    		}
    		if(next_wrap.find(".category-list li:not(.hidden)").length > 0){
    			next_wrap.removeClass('hidden');
    		}
    	});
    	
    	//选择分类
    	$(".category-item-wrap a .selected-category").click(function(){
    		var id = $(this).parent().attr('data-id');
        	if($(this).hasClass("active")){
        		idList.splice($.inArray(id,idList),1);
    			$(this).removeClass("active");
    		}else{
    			idList.push(id);
    			$(this).addClass("active");
    			//组合分类名
    			var wrap = $(this).parents('.category-item-wrap');
        		var wrap_rank = wrap.attr('rank');
        		var name = '';
        		$("#content .select-category .category-item-wrap").each(function(){
        			if($(this).attr('rank') < wrap_rank){
        				var select_name = $(this).find('.selected .category-name').text();
        				select_name = select_name.length > 6 ? select_name.substring(0,6) + '..' : select_name;
        				if(name == ''){
        					name = select_name;
        				}else{
        					name += "/" + select_name;
        				}
        			}
        		});
        		var self_name = $(this).parent().find('.category-name').text();
        		self_name = self_name.length > 6 ? self_name.substring(0,6) + '..' : self_name;
        		if(name == ''){
        			name = self_name;
        		}else{
        			name += "/" + self_name;
        		}
        		$(this).parent().find('.category-name').attr('data-name', name);
    		}
        	$('.action-area .ids').val(idList.join(','));
        	renderSelect();
    	});
    }
    
    /**
     * 显示已选择的分类
     */
    function renderSelect(){
    	var html = '';
    	$(".category-item-wrap a .selected-category.active").each(function(){
    		var id = $(this).parent().attr('data-id');
    		var name = $(this).prev().attr('data-name');
    		name = name.length > 14 ? '..' + name.substring(name.length-14) : name;
    		html += '<div class="choosed-item"><a><span>'+name+'</span><span class="cancel-select" data-id="'+id+'"><i class="icon iconfont">&#xe6be;</i></span></a></div>';
    	});
		$(".choose-category-area").html(html);
		
		//删除选择
		$(".choose-category-area .choosed-item a .cancel-select").click(function(){
			var id = $(this).attr('data-id');
			$(".category-item-wrap a .selected-category.active").each(function(){
				if($(this).parent().attr('data-id') == id){
					idList.splice($.inArray(id,idList),1);
					$(this).removeClass("active");
				}
			});
			$('.action-area .ids').val(idList.join(','));
			renderSelect();
		});
		
		if($(".choose-category-area .choosed-item").length > 0){
			$(".action-area a").addClass("success");
		}else{
			$(".action-area a").removeClass("success");
		}
		
		//选择分类后，点击“下一步”，进入商品信息填写页面
		$(".action-area .success").click(function(){
            if(!$(this).hasClass("success")){//当前不具备操作权限时，直接退出
                return false;
            }
            var cids = idList.join(',');
            //跳转到商品添加页面
            window.location.href = "./admin.php?c=store_goods&a=addGoods&cids="+cids;

            return false;
        });
    }
    
    init();
});