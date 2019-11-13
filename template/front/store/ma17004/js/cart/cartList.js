/***
 * 购物车列表
 */
$(function(){
	var total_counts = parseInt($('.cart-bottom-area .goods-count').html());//初始商品总件数
	
    function init(){
        var width = $("#container").width();//计算当前窗口的宽度
        $("#container .cart-bottom-area").width(width);//将底部导航的宽度设置与移动设备相同
        initBalance();
        calcTotalPrice();
        calcTotalCounts();
        bindEvent();
    }
    function bindEvent(){
        //用户变化屏幕方向时调用
        $(window).bind( 'orientationchange resize', function(e){
            width = $("#container").width();//计算当前窗口的宽度
            $("#container .cart-bottom-area").width(width);//将底部导航的宽度设置与移动设备相同
        });
        /**
         * 商品详情链接点击事件
         
        $(".cart-goods-list .cart-goods a").click(function(){
            var isuse = $(this).attr("data-isuse");
            if(isuse == 1){//当前可点击时，跳转链接
                return true;
            }else{//当前点击无效时，不跳转
                return false;
            }
        });
        */
        
        //
        $('.top-area div').click(function(){
        	var type=$(this).attr("data-type");
        	
        	if(type){
        		location.href="index.php?c=store&a=cartList&type="+type;
        	}
        });
        
        /**
         * 购物车商品单选事件,购物车商品删除--单选事件
         */
        $(".cart-goods-list .good-sel").click(function(){
            var _this = $(this);
            
            /*
            if(_this.find("i.disable").length > 0){//禁止点击已下架商品
                return false;
            }
            */
            
            if(_this.hasClass("selected")){
            	//当前为选中状态,则取消选中状态
            	_this.removeClass("selected");
            	_this.children("i").html("&#xe623;");
            	/*
                //当前为选中状态,则取消选中状态
                _this.find(".selected").hide();
                _this.find(".un-selected").show();
                _this.removeClass("selected");
				//
                var selector = "";
               //将底部功能“全选”状态置为未选中状态
                if(_this.hasClass("item-select")){
                    //说明当前为商品选择单选事件
                    selector = "#selectAll";
                }else{
                    //当前为删除商品单选事件
                    selector = "#selecAllDelete";
                }
                var elem = $(".cart-top-area "+selector);
               if(elem.hasClass("all-selected")){
                   elem.removeClass("all-selected");
                   elem.find(".not-all-selected").show();
                   elem.find(".all-selected").hide();
               }
               */
            }else{
            	//若当前为未选中状态，则置为选中状态
                _this.addClass("selected");
                _this.children("i").html("&#xe648;");
                /*
                _this.addClass("selected");
                _this.find(".un-selected").hide();
                _this.find(".selected").show();
                _this.addClass("selected");
                var selector = "",selector2 = "";

                if(_this.hasClass("item-select")){
                    //说明当前为商品选择单选事件
                    selector = "#selectAll";
                    selector2 = ".item-select";
                }
//              else{
//                  //当前为删除商品单选事件
//                  selector = "#selectAll";
//                  selector2 = ".item-delete";
//              }
                if($(".cart-goods-list "+selector2).length == $(".cart-goods-list "+selector2+".selected").length){
                    //都为选中状态时，将底部功能“全选”状态置为选中状态

                    var elem = $(".cart-top-area "+selector);
                    elem.addClass("all-selected");
                    elem.find(".not-all-selected").hide();
                    elem.find(".all-selected").show();
                }
                */
            }
            
            //同步计算选中商品总数和总价
        	calcTotalPrice();
            calcTotalCounts();

            
            //结算按钮是否显示
            if($(".cart-goods-list .good-sel.selected").length > 0){
                $(".cart-bottom-area #balance span").addClass("can-use");
            }else{
                $(".cart-bottom-area #balance span").removeClass("can-use");
            }
            
            
            /*
            if(_this.hasClass("item-select")){
                calcTotalPrice();
                calcTotalCounts();
            }
            
            //同步维护“结算”或“删除”按钮状态
            if(_this.hasClass("item-select")){
                if($(".cart-goods-list .item-select.selected").length > 0){
                    $(".cart-bottom-area #balance span").addClass("can-use");
                }else{
                    $(".cart-bottom-area #balance span").removeClass("can-use");
                }
            }else{
                if($(".cart-goods-list .item-delete.selected").length > 0){
                    $(".cart-bottom-area #deleteAll span").addClass("can-delete");
                }else{
                    $(".cart-bottom-area #deleteAll span").removeClass("can-delete");
                }
            }
            */
        });

        /**
         * 购物车顶部“编辑”事件,
         
        $(".cart-top-area .edit").click(function(){
            $(this).hide();//隐藏“编辑”
            $(".cart-top-area .complete").show();//显现“完成”
			//显示删除全选
			$(".cart-top-area #selectAll").addClass('is_delete');
			$(".cart-top-area #selectAll").removeClass('all-delete');
			$(".cart-top-area #selectAll .selected").hide();
			$(".cart-top-area #selectAll .delete").show();
			$(".cart-top-area #selectAll .delete .all-delete").hide();
			$(".cart-top-area #selectAll .delete .not-all-delete").show();
            //隐藏选择 结算功能，展现删除商品功能
            $(".cart-goods-list .item-select").hide();
            $(".cart-goods-list .item-delete").show();
            $(".cart-goods-list .item-delete .un-selected").show();
            $(".cart-goods-list .item-delete .selected").hide();
            $(".cart-goods-list .quantity-operation-area").hide();
            //底部功能区对应变化
            $(".cart-bottom-area #selectAll").hide();
            $(".cart-bottom-area #selecAllDelete").removeClass("all-selected");
            $(".cart-bottom-area #selecAllDelete").show();
            $(".cart-bottom-area #selecAllDelete .all-selected").hide();
            $(".cart-bottom-area #selecAllDelete .not-all-selected").show();
            $(".cart-bottom-area #totalPrice").hide();
            $(".cart-bottom-area #balance").hide();
            $(".cart-bottom-area #deleteAll span").removeClass("can-use");
            $(".cart-bottom-area #deleteAll").show();

        });
        */
       
        /**
         * 购物车顶部编辑“完成”事件，隐藏删除功能，显现结算功能
         
        $(".cart-top-area .complete").click(function(){
            $(this).hide();//隐藏“完成”			
            $(".cart-top-area .edit").show();//显现“编辑”
            $(".cart-top-area #selectAll").removeClass('is_delete');
            $(".cart-top-area #selectAll .selected").show();//显现“编辑”
            $(".cart-top-area #selectAll .delete").hide();//显现“编辑”
            
            $(".cart-goods-list .item-delete").hide();
            $(".cart-goods-list .item-select").show();
//          $(".cart-goods-list .quantity").show();
            $(".cart-goods-list .quantity-operation-area").show();
            //底部功能区对应变化
            calcTotalPrice();
            calcTotalCounts();
            $(".cart-bottom-area #selectAll").show();
            $(".cart-bottom-area #selecAllDelete").hide();
            $(".cart-bottom-area #totalPrice").show();
            $(".cart-bottom-area #balance").show();
            $(".cart-bottom-area #deleteAll").hide();
        });
        */

        /***
         * 购物车功能区--全选功能
         */
        $(".cart-top-area .all-select").click(function(){
            var _this = $(this);
            if(_this.children(".location").hasClass("selected")){
            	$(".goods .good-sel").each(function(){
        			$(this).removeClass('selected');
        			$(this).children("i").html("&#xe623;");
            	})
            	_this.find(".location").removeClass('selected');
            	_this.find(".location").html("&#xe623;");
            }
            else{
            	_this.find(".location").addClass('selected');
            	_this.find(".location").html("&#xe648;");
            	$(".goods .good-sel").each(function(){
        			$(this).addClass('selected');
        			$(this).children("i").html("&#xe648;");
            	})
            }
            /*
            //判断是否对是全选删除
            if(_this.hasClass('is_delete')){
            	if(_this.hasClass("all-delete")){
	
	                //当前为选中状态,则取消选中状态
	                _this.find(".all-delete").hide();
	                _this.find(".not-all-delete").show();
	                _this.removeClass("all-delete");
	                //将购物车中所有商品置为未选中状态
	                var selector = "";
//	                if(_this.attr("id") == "selectAll"){
//	                    selector = ".item-select";
//	                    $(".cart-bottom-area #balance span").removeClass("can-use");
//	                }else{
	                selector = ".item-delete";
	                $(".cart-bottom-area #deleteAll span").removeClass("can-use");
//	                }
	                $(".cart-goods-list .cart-goods "+selector).removeClass("selected");
	                $(".cart-goods-list .cart-goods "+selector+" .un-selected").show();
	                $(".cart-goods-list .cart-goods "+selector+" .selected").hide();
	            }else{
	                //若当前为未选中状态，则置为选中状态
	                _this.find(".not-all-delete").hide();
	                _this.find(".all-delete").show();
	                _this.addClass("all-delete");
	                //将所有商品置为选中状态
//	                var selector = "";
//	                if(_this.attr("id") == "selectAll"){
//	                    selector = ".item-select";
//	                    $(".cart-bottom-area #balance span").addClass("can-use");
//	                }else{
	                selector = ".item-delete";
	                $(".cart-bottom-area #deleteAll span").addClass("can-delete");
//	                }
	                $(".cart-goods-list .cart-goods .item-delete").addClass("selected");
	                $(".cart-goods-list .cart-goods .item-delete .un-selected").hide();
	                $(".cart-goods-list .cart-goods .item-delete .selected").show();
	           }
            }else{
	            if(_this.hasClass("all-selected")){
	                //当前为选中状态,则取消选中状态
	                _this.find(".all-selected").hide();
	                _this.find(".not-all-selected").show();
	                _this.removeClass("all-selected");
	                //将购物车中所有商品置为未选中状态
	                var selector = "";
	                if(_this.attr("id") == "selectAll"){
	                    selector = ".item-select";
	                    $(".cart-bottom-area #balance span").removeClass("can-use");
	                }else{
	                    selector = ".item-delete";
	                    $(".cart-bottom-area #deleteAll span").removeClass("can-use");
	                }
	                $(".cart-goods-list .cart-goods "+selector).removeClass("selected");
	                $(".cart-goods-list .cart-goods "+selector+" .un-selected").show();
	                $(".cart-goods-list .cart-goods "+selector+" .selected").hide();
	            }else{
	                //若当前为未选中状态，则置为选中状态
	                _this.find(".not-all-selected").hide();
	                _this.find(".all-selected").show();
	                _this.addClass("all-selected");
	                //将所有商品置为选中状态
	                var selector = "";
	                if(_this.attr("id") == "selectAll"){
	                    selector = ".item-select";
	                    $(".cart-bottom-area #balance span").addClass("can-use");
	                }else{
	                    selector = ".item-delete";
	                    $(".cart-bottom-area #deleteAll span").addClass("can-use");
	                }
	                $(".cart-goods-list .cart-goods "+selector).addClass("selected");
	                $(".cart-goods-list .cart-goods "+selector+" .un-selected").hide();
	                $(".cart-goods-list .cart-goods "+selector+" .selected").show();
	            }
	        } 
	        */
	       
	       	//同步计算选中商品总数和总价
            calcTotalPrice();
            calcTotalCounts();
            
            //结算按钮是否显示
            if($(".cart-goods-list .good-sel.selected").length > 0){
                $(".cart-bottom-area #balance span").addClass("can-use");
            }else{
                $(".cart-bottom-area #balance span").removeClass("can-use");
            }
        });
		
        /**数量输入框键盘事件--输入非数字无效**/
        $(".cart-goods-list .goods .state .num-area .num").keyup(function() {
	        var quantity = $(this).val();
	        var ereg = /^[0-9][0-9]{0,4}$/;
	        if( quantity !="" && ereg.test(quantity)){
	            //非空且合法
	            quantity = parseInt(quantity);
	        }else if ( quantity !="" && !ereg.test(quantity)){
	            //非空且不合法
	            quantity = 1;
	        }
	        $(this).val(quantity);
			//$(this).parents(".quantity-operation-area").prev().find(".quantity-value").html(quantity);
        }).blur(function(){
            //失去焦点事件
            var quantity = $(this).val();
            if(quantity == ""|| quantity == 0){
                quantity = 1;
                $(this).val(quantity);
            }          			//$(this).parents(".quantity-operation-area").prev().find(".quantity-value").html(quantity);
            calcTotalPrice();
            calcTotalCounts();
        });

        /**商品数量添加事件**/
        $('.cart-goods-list .goods .state .num-area .incCount').click(function() {
            var _this = $(this);
            var qElem = _this.prev();
            var value = parseInt(qElem.val());
            var now = value + 1;
        	updateCartGoodsCount(_this,now);
            	
            /*选中才能增加
            if(_this.parents('.goods').find('.good-sel').hasClass('selected')){
            	var qElem = _this.prev();
	            var value = parseInt(qElem.val());
	            var now = value + 1;
//	            total_counts++;
            	updateCartGoodsCount(_this,now);
            }
           	*/

            //qElem.val(now);
            //$(this).parent().prev().find(".quantity-value").html(now);
        });
        
        /**商品数量减少事件**/
        $('.cart-goods-list .goods .state .num-area .decCount').click(function() {
            var _this = $(this);
            var qElem = _this.next();
            var value = parseInt(qElem.val());
            var now = value - 1;
            if(now < 1){   //数量减为0，移除该商品
                var id=$(this).parent().attr("data-id");
                $.ajax({
                    url:"./index.php?c=store&a=deleteCartGoods",
                    type:"post",
                    data:{"cgids":id},
                    dataType:"json",
                    success:function(json,statusText){
                        if(json.errorCode == 0){
                            //删除成功，同步将页面上的元素集删除
                            if($(".cart-goods-list .goods-info").length == 1){
                                //若删除数量与总数量相同，则重新刷新页面
                                window.location.reload();
                            }else{
                                $(_this).parents(".goods-info").remove();
                                $(_this).parents(".goods-info").next().remove();

                                //重新计算总价
                                calcTotalPrice();
                                calcTotalCounts();
                                //结算功能置为不可用
                                if($(".cart-goods-list .good-sel.selected").length==0){
                                    $(".cart-bottom-area #balance span").removeClass("can-use");
                                }
                            }
                        }else{                          
                            showDialog('#alertDialog','',json.errorInfo,'&#xe6a9;',1500);
                        }
                    },
                });
                return;
            }           
            updateCartGoodsCount(_this,now);
            
            /*选中才能减少
            if(_this.parents('.goods').find('.good-sel').hasClass('selected')){
	            var qElem = _this.next();
	            var value = parseInt(qElem.val());
	            var now = value - 1;
	            if(now < 1){
	                now = 1;
	                return false;
	            }
//	            total_counts--;	            
	            updateCartGoodsCount(_this,now);
			}
			*/
        });

        /**
         * 下单，生成待付款订单
         */
        $(".cart-bottom-area #balance span").click(function(){
            if($(this).hasClass("can-use")){
                //判断当前是否具有结算操作
                var selectedElems = $(".cart-goods-list .good-sel.selected");
                var cgids = [];//表cart_goods的主键id
                var gids = [];//商品表goods的主键id
                var counts = [];//商品数量
//              var gpids = [];//表cart_goods的字段 多规格的标识
				
				if(selectedElems.length > 0){
					selectedElems.each(function(index){
                        //var elem = $(this);
                        cgids.push($(this).attr("data-id"));
                        gids.push($(this).attr("data-gid"));
                        //gpids.push(elem.attr("data-gpid"));
                        counts.push($(this).parent().find(".num-area .num").val());
	                });
				}
                
                cgids.join(",");
                gids.join(",");
                //gpids.join(",");
                counts.join(",");
                //生成待提交订单,确认后跳转到待付款订单页面，进行支付
                var uid=$(this).attr("data-user");
                window.location.href = "./pay/request/balancePay.php?cgids="+cgids+"&gids="+gids+"&counts="+counts+"&uid="+uid;
            }
        });
        
        /**
         * 购物车商品删除事件
         */
        $(".cart-top-area .delete").click(function(){
            //判断当前是否具有删除操作
            var deleteElems = $(".cart-goods-list .goods .selected");//待删除商品项
            var cgids = [];
            deleteElems.each(function(index){
                cgids.push($(this).attr("data-id"));
            });
            cgids = cgids.join(",");
            if(cgids){
            	$("#deleteModal").modal("show");
            	//取消
            	$("#deleteModal .cancel").click(function(){
            		$("#deleteModal").modal("hide");
            	})
            	//确认
            	$("#deleteModal .confirm").click(function(){
            		$("#deleteModal").modal("hide");
            		$.ajax({
	                    url:"./index.php?c=store&a=deleteCartGoods",
	                    type:"post",
	                    data:{"cgids":cgids},
	                    dataType:"json",
	                    beforeSend:function(xhr){
	      	               //显示“加载中。。。”
	      	            	showDialog("#loadingDialog");
	      	            },
	      	            complete:function(){
	      	               //隐藏“加载中。。。”
	      	            	hideDialog("#loadingDialog");
	      	            },
	                    success:function(json,statusText){
	                        if(json.errorCode == 0){
	                            //删除成功，同步将页面上的元素集删除
	                            if($(".cart-goods-list .goods-info").length == deleteElems.length){
	                                //若删除数量与总数量相同，则重新刷新页面
	                                window.location.reload();
	                            }else{
	                                deleteElems.each(function(){
	                                    $(this).parents(".goods-info").remove();
	                                    $(this).parents(".goods-info").next().remove();
	                                });
	                                //重新计算总价
	                                calcTotalPrice();
	                                calcTotalCounts();
	                                //结算功能置为不可用
	                                $(".cart-bottom-area #balance span").removeClass("can-use");
	                            }
	                        }else{                      	
	                        	showDialog('#alertDialog','',json.errorInfo,'&#xe6a9;',1500);
	                        }
	                    },
	                    error:function(){
	                        //请求出错
	                    	showDialog('errorDialog','','网络异常，请求失败！' ,'&#xe6a9;',1500);
	                    }
	                });
            	})
            }
            else{
            	showDialog('#errorDialog','','未选择任何商品','&#xe6a9;',1500);
            }
        });

    }

    /**
     * 修改购物车商品数量
     * 购物车商品表主键id
     * 购物车商品表商品数量count
     */
    function updateCartGoodsCount(_this,count){
        var id = _this.parents(".num-area").attr("data-id");
		
        $.ajax({
            url:"./index.php?c=store&a=updateGoodsCount",
            type:"post",
            data:{"id":id,"goods_count":count},
            dataType:"json",
//          beforeSend:function(xhr){
//	               //显示“加载中。。。”
//	            	showDialog("#loadingDialog");
//	            },
//	            complete:function(){
//	               //隐藏“加载中。。。”
//	            	hideDialog("#loadingDialog");
//	            },
            success:function(json,jsonText){
                if(json.errorCode == 0){//请求成功时，修正数量
                    _this.parent().find('.num').val(count);
//                  _this.parent().prev().find(".quantity-value").html(count);
                    calcTotalPrice();
                    calcTotalCounts();
//                  _this.parents().find('.goods-count').html(total_counts);
                }else{
                    //请求出错
                	showDialog('#errorDialog','',json.errorInfo,'&#xe6a9;',1500);
                }
            },
            error:function(){
                //请求出错
            	showDialog('#errorDialog','','网络异常，请求失败！','&#xe6a9;',1500);
            }
        });
    }
    /**
     * 计算购物车商品总价格
     */
    function calcTotalPrice(){
        var selectedGoods = $(".cart-goods-list .goods .good-sel.selected");//所有选中的商品记录
        var totalPrice = 0;
        selectedGoods.each(function(){
            var price = $(this).next().find(".price").text();//单价
            price=price.substring(1);
            var quantity = $(this).next().next().find(".num").val();//数量
            totalPrice = totalPrice + (parseFloat(price)* parseInt(quantity));
        });
        //重写总价格
        $(".cart-bottom-area .total-price-value").html(totalPrice.toFixed(2));
    }
    
    /**
     * 计算购物车商品总数
     */
    function calcTotalCounts(){
        var selectedGoods = $(".cart-goods-list .goods .good-sel.selected");//所有选中的商品记录
        var total_counts = 0;
        selectedGoods.each(function(){
            var quantity = $(this).next().next().find(".num").val();//数量
            total_counts = total_counts + parseInt(quantity);
        });
        //重写商品总数
        $(".cart-bottom-area .goods-count").html(total_counts);
    }
    
    //进入购物车时维护底部结算按钮
    function initBalance(){
    	if($(".cart-goods-list .good-sel.selected").length > 0){
            $(".cart-bottom-area #balance span").addClass("can-use");
        }else{
            $(".cart-bottom-area #balance span").removeClass("can-use");
        }
    }

    init();
});