/**
 ** 订单评价
 *  @since 2015-11-20
 *  @author jjhu
 **/
$(function(){
    //初始化
    function init(){
        bindEvent();
    }
    //事件绑定
    function bindEvent(){
    	
    	var image_box = '<div class="main">'+
            '<div class="add-btn"><i class="icon iconfont camera">&#xe645;</i><span class="add-img">照片</span></div>'+
            '<form class="myForm"><input class="add-img-btn hidden" name="imgurl" accept="image/*" type="file"></form>'+
//          '<span class="del-img second" style="display: none;"><i class="icon iconfont">&#xe602;</i></span>'+
 			'<div class="share-ago"><span class="off-text">×</span></div>'+
 			'<img class="forum-img hidden" src="" />'+
            '</div>';
            
	    $('.upload-image').append(image_box);
    	
    	addImageBox();
		function addImageBox() {
	    		deleteImageBox(); // 激活删除图片的事件
	    		$('.upload-image .main:last-child .add-btn').click(function() {	// 绑定.add-img的click
	    			_this = $(this).next('form').find(".add-img-btn");
					_this.click();
					_this.change(function(){													
						_this.parent(".myForm").ajaxSubmit({
							async:true,
							type : 'post',
							url:"./index.php?c=store&a=upLoadImage",
						    dataType : 'json',
						    beforeSend: function(){
				            	showDialog("#loadingDialog");//显示“加载中。。。”
						    },
						    complete:function(){
						    	hideDialog("#loadingDialog");//隐藏“加载中。。。”
						    },
						    success:function(result){
					    		if(result.errorCode == 0){//成功
									_the = _this.parents('.main');
									_the.children('.forum-img').attr('src', result.data.img_url).removeClass('hidden');
									_the.children('.add-btn').hide();
									_the.children('.share-ago').show();
									_the.parents('.upload-image').append(image_box);
									$('.upload-image .main:last-child .add-btn').unbind('click');
									addImageBox();
					    		}else{
					    			showDialog("#errorDialog","上传失败",result.errorInfo,'&#xe602;');
					    		}
					    	},
						    error:errorResponse
						});
			    	});
	    		});
//	    	}
			
		}
		//删除图片
		deleteImageBox();
    	function deleteImageBox() {
    		$(".main .share-ago").click(function(){
//				var imageBoxNum = $(this).parents('.main').length;
	    		var _the = $(this);
	    		var _this = $(this).parent();
					var img_url = _this.children('img').attr('src');
	    			$.ajax({
				    	url:"./index.php?c=store&a=deleteUpLoadImage",
				        type:"post",
				        data:{"img_url":img_url},
				        dataType:"json",
				        beforeSend:function(xhr){
				        	showDialog('#loadingDialog');
				        },
				        complete:function(){
				        	hideDialog('#loadingDialog');
				        },
				        success:function(json,statusText){
				            if(json.errorCode == 0){
				                //操作成功
				                _this.remove();
//			    				addImageBox();
				            }else{
								showDialog("#errorDialog","删除失败",result.errorInfo,'&#xe602;');
				            }
				        },
				        error:function(){
				        	showDialog('#errorDialog','出错啦~','网络异常！','',1500);
				        }
			        });
	    	});
    	}
    	
    	

        //选择评价等级
        $(".order-area .service-score .level").click(function(){
            //分数 1-5 1差评，2、3中评，4、5好评
            var value = $(this).val();//评价等级 好评1、中评2、差评3
            if(value == 1){
                //好评4-5分
                $(this).parents(".service-score").find(".star .empty").hide();
                $(this).parents(".service-score").find(".star .entity").show();
                $(this).parents(".service-score").find(".star").addClass("checked");//标识被选中
            }else if(value == 2){
                //中评2-3分
                $(this).parents(".service-score").find(".star").removeClass("checked");
                $(this).parents(".service-score").find(".star i").hide();
                $(this).parents(".service-score").find(".star-1 .entity,.star-2 .entity,.star-3 .entity").show();
                $(this).parents(".service-score").find(".star-4 .empty,.star-5 .empty").show();
                $(this).parents(".service-score").find(".star-1,.star-2,.star-3").addClass("checked");
            }else if(value == 3){
                //差评1分
                $(this).parents(".service-score").find(".star").removeClass("checked");
                $(this).parents(".service-score").find(".star i").hide();
                $(this).parents(".service-score").find(".star-1 .entity").show();
                $(this).parents(".service-score").find(".star-1").addClass("checked");
                $(this).parents(".service-score").find(".star-2 .empty,.star-3 .empty,.star-4 .empty,.star-5 .empty").show();

            }
        });

        //直接打分
        $(".order-area .service-score .star").click(function(){
            var _this = $(this);

            var level = _this.attr("data-level");//评价等级

            _this.find(".empty").hide();
            _this.find(".entity").show();
            _this.addClass("checked");
            _this.prevAll().addClass("checked");//标识选中
            _this.prevAll().find(".empty").hide();
            _this.prevAll().find(".entity").show();
            _this.nextAll().removeClass("checked");//取消选中
            _this.nextAll().find(".empty").show();
            _this.nextAll().find(".entity").hide();
            //同步修改 好评、中评、差评
            _this.parents(".service-score").find(".level[value="+level+"]").prop("checked",true);

        });
        
        //点击匿名
        $('.bottom-nav .selected').click(function(){
        	if($(this).children().hasClass('cur')){
        		$(this).children().removeClass('cur');
        	}else{
        		$(this).children().addClass('cur');
        	}
        });

        //提交评价
        $(".bottom-nav .submit").click(function(){
            var _this = $(this);
            var oid = _this.parents(".order-area").attr("data-oid");
            var order_num = $('#order-num').val();//编号
            var is_anonym = _this.parent().find(".icon").hasClass("cur") == true ? "1":"0";
            var comments = [];
            var false_num = 0;
            var goodsList = _this.parents(".order-area").find(".order-goods-list .goods");
//          var imageList = _this.parents(".order-area").find(".order-goods-list .main");
			
            goodsList.each(function(){
                var comment = {};
                comment.gid = $(this).attr("data-gid");
                comment.goods_name = $(this).find(".name").html();//评价商品
                
                if ($.trim($(this).find('.property-area').html())) {
					if ($(this).find('.property-area span').length > 0) {
						var content = '';
                        $(this).find('.property-area').children('span').each(function() {
                            content += $(this).text()+' ';
                        });
                        comment.property = $.trim(content);
					} else {
						comment.property = $(this).find('.property-area').html();
					}
				} else {
                    comment.property = '统一规格';
				}

                
                var imageList = $(this).next().find('.main');
                var imageUrl = '';
//				var imageBoxNum = $('.upload-image .main').length;
				imageList.each(function() {
					imageUrl += ',' + $(this).children('img').attr('src');
				})
                
                comment.image_list = imageUrl;
//              comment.level = $(this).next(".goods-comment").find(".level").val();//评价等级
                var content = $(this).next(".goods-comment").find(".content").val();//评价内容
                if(content == ""){
                	false_num += 1;
                    showDialog('#alertDialog','',"请填写评价内容！");
                    return false;
                }else if(content.length > 300){
                	false_num += 1;
                    showDialog('#alertDialog','',"文本长度最大不超过300!");
                    return false;
                }
                comment.content = content;
                comment.score = $(this).next(".goods-comment").find(".star.checked").length;//选中的星星个数即得分
                comments.push(comment);
            });

            if(false_num >= 1){return false;}
            $.ajax({
                type:"post",
                url:"./index.php?c=store&a=insertComment",
                dataType:"json",
                beforeSend:function(xhr){
   	               //显示“加载中。。。”
   	            	showDialog("#loadingDialog");
                 },
                  complete:function(){
   	               //隐藏“加载中。。。”
   	            	hideDialog("#loadingDialog");
                 },
                data:{"oid":oid,"order_num":order_num,"is_anonym":is_anonym,"comments":JSON.stringify(comments)},
                success:function(json,statusText){
                    if(json.errorCode == 0){
                    	showDialog('#alertDialog','','评论成功！','','',function(){
                    		window.history.go(-1);
                    	});
                    }else{
                    	showDialog('#errorDialog','',json.errorInfo,'');
                    }
                },
                error:function(){
                	showDialog('#errorDialog','','请求失败，网络异常！','');
                }
            });
        });

    }
    
    // 获取图片地址数据
	function getData() {
		var imageUrl = '';
		var imageBoxNum = $('.upload-image .main').length;
		$('.upload-image .main').each(function() {
			imageUrl += ',' + $(this).children('img').attr('src');
		})
		return imageUrl;
	}
 
    init();
});