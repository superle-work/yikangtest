/*提交订单*/
$(function(){
	//会员卡 + 运费 金额
	var totalPrice = parseFloat($("#content .goods-total .money").html());
	var counts = $("#content #counts").val();  //商品数量
	//var single_price = parseFloat($(".order-goods-list .price").html());      //商品单价
	//var tmpUnitPrice = getUnitPrice(); // 使用会员卡后的价格
	//var oriUnitPrice = parseFloat($("#content .goods-name .price").html());   //商品原始单价
	//var oriTotalPrice = counts * oriUnitPrice; //不计算商品：优惠券，会员卡，余额，会员卡的商品总价
    //var couponMoney = 0; //优惠券价格
    //var feeMoney = 0;    //运费
    //var pointsValue = $(".order-bottom-area .points-money p.p1 .right").attr('points-value');
    //var feeBelow = parseFloat($(".express-area #fee-below").attr("data-fee-below"));//免运费金额
    //var oriFee = parseFloat($(".express-area #fee-below").attr("data-fee"));//正常的运费金额
    //var orderFee = $("#order_fee").attr("data-order-fee");
    //var inventory = parseInt($("#inventory").attr("data-invertory"));
    
    /**
     * 初始化
     */
    function init(){
        bindEvent();
        //getUnitPrice();
    }
    
    /**
     * 积分抵用的     金额(页面显示的,“ 可以使用 30 积分 抵用 30元   ” 随时会变化)
     
    function tmpPointsMoney(){
    	var pointsMoney = $(".points-money .left #points-money").html();
    	if(pointsMoney == '' || pointsMoney == undefined){
    		pointsMoney = 0;
    	}
    	return parseFloat(pointsMoney);
    }
    */
    
    /**
     * 获取商品单价：如果有会员卡返回卡后价格，否则返回原价
     
    function getUnitPrice(){
    	var unit_price = parseFloat($(".order-goods-list .price").html());      //商品单价
    	//使用打折卡，计算使用的单价改变
    	var countCard = $("#count-discount").length;
    	if(countCard > 0){
    		var unit_price = parseFloat($("#count-discount").attr("count-discount"));  //折扣数
    		var dis_price = parseFloat($("#dis-price").text());  //折扣后的总价
    		unit_price = dis_price / counts;
    	}
    	return unit_price;
    }
    */
   
    /**
     * 余额抵现的	金额(页面显示的：余额共30.00元 本订单将消耗余额30.00元)
     
    function tmpBalanceMoney(){
    	var balanceMoney = $(".balance-money #balance-money").html();
    	if(balanceMoney == '' || balanceMoney == undefined){
    		balanceMoney = 0;
    	}
    	return parseFloat(balanceMoney);
    }
    */
    
 	/**
     * 积分是否能抵掉全部订单金额    ？ 0 没有全部抵用， 1已全部抵用	
     
    function isAllPointsPay(){  //TODO
    	var state = $("#content #points_state").val(); //积分抵现状态: 0, 1
    	//如果达到免运费的条件需要重新设置
    	
    	return state;
    }
    */
    
    /**
     * 是否余额全部抵用订单金额	 ？ 0 没有全部抵用， 1已全部抵用
     
    function isAllBalancePay(){
    	var allBalance = $('#content #allBalance').val();//获取余额与订单金额的对比 1 余额大于 订单金额 0 小于
    	return allBalance;
    }
    */
   
    /**
     * 本订单——最大可用积分：(后台传值，固定不变，修改优惠券时改变)
     
    function getMaxUsePoints(){
		var firTotalPoints = $(".order-bottom-area .points-money p.p1 ").attr('data-points');
    	return firTotalPoints;
    }
    */
   
    /**
     * 本订单——最大可用积分——全用，可抵消的订单金额
    
    function getAllPointsMoney(){
		var firPoints = $(".order-bottom-area .points-money p.p1 .right").attr('fir-points');
    	return firPoints;
    }
     */
    
    /*
     * 本订单：可用余额全用，可抵消的订单金额
     
    function getAllBalanceMoney(){
		var firBalance = $(".order-bottom-area .balance-money p.p1 .right").attr('fir-balance');
    	return firBalance;
    }
    */
   
    /*
     * 获取:	实际支付金额（未扣除	打折卡，积分， 余额支付）
     
    function getFirTotalPrice(){
    	var firTotalPrice = parseFloat($("#content .real-total-price-area .total-price").attr("data-total-price"));
    	return firTotalPrice;
    } 
    */

    /**
     * 页面实际支付总金额(下方显示的)
     
    function getTotalMoney(){
    		//原始总金额计算
    		   		
			//会员卡对价格的影响处理： 这里的价格是ajax计算打折卡之后的价格
		　　 var totalPrice = parseFloat($("#content .goods-total .money").html()); //总价

            //countDiscount 会员卡几折优惠
			var countDiscount = $("#count-discount").attr("count-discount");
			countDiscount = countDiscount != undefined ? countDiscount : 10;
			totalPrice = totalPrice * (countDiscount / 10);
			
			
			//优惠券对价格的影响处理
			var couponType =  $("select option:checked").attr("type");
	  		if(couponType == 1){  //代金券
	  			var couponValue = $("select option:checked").attr("coupon-value");
	  		}else if(couponType == 2){  //免费券
	  			couponType = totalPrice;  	
	  		}else if(couponType == 3){  //折扣券
	  			var couponDiscount = $("select option:checked").attr("coupon-discount");
	  				couponValue = totalPrice - totalPrice * (couponDiscount / 10) ;
	  		}else if(couponType == undefined || couponType == 0){
	  			couponValue =0.00;
	  		}
	  		
	  		var fee = $("#exp-fee").text();//运费
	  		if(fee == '' || fee == undefined){
	  			fee = 0;
	  		}
	  		feeMoney = fee;
	  		
	  		totalPrice = parseFloat(totalPrice) + parseFloat(fee) - parseFloat(couponValue);
	  		
	  		//余额对总金额影响
	  		/*if($(".order-bottom-area .balance-money p.p1 .right").parent().hasClass('active')){
	  			var balanceMoney = tmpBalanceMoney();
	  			totalPrice = parseFloat(totalPrice) - parseFloat(balanceMoney);
	  		}
	  		
	  		return (totalPrice.toFixed(2));  //结果保留两位小数
    }
	*/
	
    /**
     * 绑定事件
     */
    function bindEvent(){
    	/*
    	//点击余额抵现
    	$('#content .goods-balance .check-box').click(function(){
    		if($(this).children().hasClass('selected')){
    			$(this).children().removeClass('selected').addClass('no-selected');
    		}else{
    			$(this).children().removeClass('no-selected').addClass('selected')
    		}
    	});
    	
    	//点击为他众筹
    	$('#content .goods-collect .check-box').click(function(){
    		if($(this).children().hasClass('selected')){
    			$(this).children().removeClass('selected').addClass('no-selected');
    		}else{
    			$(this).children().removeClass('no-selected').addClass('selected')
    		}
    	});
    	
    	//数量加1
    	$('.quantity-area .quantity-plus').click(function(){
    		if(counts >= inventory || inventory == 0){
    			return false;
    		}
    		counts++;
    		totalPrice = parseFloat(counts * single_price);		//上部展示:确定不变的订单总价
    		var disTotalPrice = parseFloat(counts * tmpUnitPrice);	//折扣价
    		if(totalPrice > feeBelow){
    			tmpTotalPrice = disTotalPrice.toFixed(2) 
    		}else{
    			tmpTotalPrice = (disTotalPrice + oriFee).toFixed(2); //总金额
    		}
			modifyFeeState();
    		
    		totalPrice = totalPrice.toFixed(2);
    		disTotalPrice = disTotalPrice.toFixed(2);
    		
    		$('.quantity-area .quantity-value').val(counts);
    		$('.order-bottom-area .number').html(counts);
    		$('.bottom-nav .num').html(counts);  
    		$("#content #counts").val(counts);//维护提交的隐藏域数量
    		$('.order-bottom-area .money').html(totalPrice);
    		$('.bottom-nav .money').html(tmpTotalPrice);
    		$('#dis-price').html(disTotalPrice);
    		oriTotalPrice = counts * oriUnitPrice;
    		$("#content #couponSelector").trigger('change');
    	});
    	
    	//数量减1
    	$('.quantity-area .quantity-minus').click(function(){
    		if(counts <= 1){
    			return false;
    		}
    		counts--;
    		totalPrice = parseFloat(counts * single_price);
    		var disTotalPrice = parseFloat(counts * tmpUnitPrice);	//折扣价
    		if(totalPrice > feeBelow){
    			tmpTotalPrice = disTotalPrice.toFixed(2) 
    		}else{
    			tmpTotalPrice = (disTotalPrice + oriFee).toFixed(2); //总金额
    		}
    		modifyFeeState();
    		
    		disTotalPrice = disTotalPrice.toFixed(2);
    		totalPrice.toFixed(2);
    		$('.quantity-area .quantity-value').val(counts);
    		$('.order-bottom-area .number').html(counts);
    		$('.bottom-nav .num').html(counts);   
    		$("#content #counts").val(counts);//维护提交的隐藏域数量
    		$('.order-bottom-area .money').html(totalPrice);
    		$('.bottom-nav .money').html(tmpTotalPrice);
    		$('#dis-price').html(disTotalPrice);
    		oriTotalPrice = counts * oriUnitPrice;
    		$("#content #couponSelector").trigger('change');
    	});
    	
    	//输入数量
    	$(".quantity-area .quantity-value").blur(function(){
    		counts = $(".quantity-area .quantity-value").val();
    		if(counts >= inventory){
    			counts = inventory;
    			$(".quantity-area .quantity-value").val(inventory);
    			return false;
    		}
    	
    		totalPrice = parseFloat(counts * single_price);
    		var disTotalPrice = parseFloat(counts * tmpUnitPrice);	//折扣价
    		if(totalPrice > feeBelow){
    			tmpTotalPrice = disTotalPrice.toFixed(2) 
    		}else{
    			tmpTotalPrice = (disTotalPrice + oriFee).toFixed(2); //总金额
    		}
    		//修改运费提示信息:  免费/10元
    		modifyFeeState();
    		
    		disTotalPrice = disTotalPrice.toFixed(2);
    		totalPrice.toFixed(2);
    		$('.order-bottom-area .number').html(counts);
    		$('.bottom-nav .num').html(counts);   	
    		$("#content #counts").val(counts);//维护提交的隐藏域数量
    		$('.order-bottom-area .money').html(totalPrice);
    		$('.bottom-nav .money').html(tmpTotalPrice);
    		$('#dis-price').html(disTotalPrice);
    		oriTotalPrice = counts * oriUnitPrice;
    		$("#content #couponSelector").trigger('change');
    	});
    	
    	//点击使用积分
    	$(".order-bottom-area .points-money p.p1 .right").on('click',pointsClick);
    	//点击使用余额
    	$(".order-bottom-area .balance-money p.p1 .right").on('click',balanceClick);
    	
        $(".address-list .item-select").click(function(){
            var _this = $(this);
            if(!_this.hasClass("selected")){//当前为选中状态,则取消选中状态
                //地址单选事件
                $(".address-list .item-select").removeClass("selected");
                $(".address-list .item-select").find(".selected").hide();
                $(".address-list .item-select").find(".un-selected").show();
                _this.find(".un-selected").hide();
                _this.find(".selected").show();
                _this.addClass("selected");
            }
        });

        //优惠券 下拉事件 同步修改总价
        $("#content #couponSelector").change(function(){
            var id = $(this).val();
            var type = $(this).attr('type');
            //var oriTotalPrice = parseFloat($('.order-bottom-area #dis-price').text()); //商品单价 * 数目的 = 原始总价
            var is_card = $('.order-bottom-area #count-discount').attr('count-discount');
            if(is_card >= 1 ){
            	var totalPrice = parseFloat($('.order-bottom-area #dis-price').text());
            }else{
            	var totalPrice = parseFloat($('#total-price-1').text());
            }
            var fee = parseFloat($('#exp-fee').text());
            //使用优惠券时，同步修改总价
            $.ajax({
                type:"post",
                url:"./index.php?c=store&a=getPointMoneyState",
                data:{"id":id,'totalPrice':totalPrice,'type':type,'fee':fee, 'oriTotalPrice':oriTotalPrice},
                dataType:"json",

                success:function(json,statusText){
                    if(json.errorCode == 0){
         				var obj = json.data;     
                		$("#content .points-money p").removeClass('active');
			    		$("#content .balance-money p").removeClass('active');			    		
			    		$("#content .points-money p .right").unbind('click');
			    		$("#content .balance-money p .right").unbind('click');
			    		$("#content .points-money p .right").bind('click',pointsClick);
			    		$("#content .balance-money p .right").bind('click',balanceClick);
                    	var obj = json.data;
                    	var maxUsePoints = obj.points.max_use_points;
                    	var pointsMoney = obj.points.pointsMoney;
                    	var state = obj.points.points_state;
                    	var balanceMoney = obj.balance.use_balance;
                    	var totalBalance = obj.balance.totalBalance;
                    	var allBalance = obj.balance.allBalance; 
                    	var totalPrice = obj.totalPrice;
                    	var fee = obj.fee;	//运费金额
                    	//<span id="exp-fee"><!--{$fee}--></span>
                		var feeText = fee > 0 ? '快递<span id="exp-fee">'+fee+'</span>元' : '快递免费';
                    	
                    	$(".order-bottom-area .points-money p.p1 .total-points").html(maxUsePoints);
                    	$(".order-bottom-area .points-money #points-money").html(pointsMoney);
			    		$(".balance-money #balance-money").html(balanceMoney);
			    		$(".bottom-nav .total-money .money").text(parseFloat(totalPrice).toFixed(2));
                    	$(".express-area .express-money").html(feeText);
                    	$(".order-bottom-area .points-money p.p1 ").attr('data-points',maxUsePoints);
                    	//设置状态改变
                    	$("#content #points_state").val(state);     //积分抵现状态
   						$('#content #allBalance').val(allBalance);
						$(".order-bottom-area .points-money p.p1 ").attr('data-points',maxUsePoints);
    					$(".order-bottom-area .balance-money p.p1 .right").attr('fir-balance',balanceMoney);
    					$(".order-bottom-area .points-money p.p1 .right").attr('fir-points',pointsMoney);
						$("#content .real-total-price-area .total-price").attr('data-total-price',totalPrice);
                    }else{
                    	showDialog('#errorDialog','',json.errorInfo,'');
                    }
                },
                error:function(){
                	showDialog('#errorDialog','','网络异常，请求失败！','');
                }
            });

        });
        */
       
       	//无默认体检人员
       	$(".addMem-area .addCheckMember").click(function(){
       		var storage=window.localStorage;
       		storage['url']=window.location.href;
       		location.href="index.php?c=store&a=checkMember";
       	})
       	
       	//重新选择体检人员
       	$(".member-list .action .reSel").click(function(){
       		var storage=window.localStorage;
       		storage['url']=window.location.href;
       		location.href="index.php?c=store&a=checkMember";
       	})
       
       	
        //提交订单
        $(".bottom-nav .pay-method").click(function(){
            $("#content .tip-info").hide();
            //验证收货地址
            var option = $(".member-list .info");
            if(option.length == 0){
                showDialog("#errorDialog",'','添加检查人员信息','&#xe6a9;',2000);
                return false;
            }
            
            var checkMember_id = option.attr("data-id");//收货人
            //var phone =  option.find(".phone").text().trim();//联系方式
            //var address = option.find("#address-info").text().trim();//收货地址
            //var consume_points = $('.points-money .p1').hasClass('active') ? $(".points-money .p1.active .left .total-points").text() : 0;
            //var consume_balance = $('.balance-money .p1').hasClass('active') ? $(".balance-money .p1 #balance-money").text() : 0;

            //var address_text = call_name+"--"+phone+"--"+address;
            //var message = $.trim($(".order-bottom-area .message").val());
            var cgids = $("#content #cgids").val();//从购物车下单，购物车里面待删除的商品
            var gids = $("#content #gids").val();//商品主键
            //var gpids = $("#content #gpids").val();//商品属性主键
            var counts = $("#content #counts").val();//商品数量
            //总价
            var total_price=$(".order-bottom-area .goods-total #total-price-1").text();
            //推荐诊所id
            var clinicID=$(".clinic-area .clinicID").val();
            
            //var user_coupon_id = $("#content #couponSelector").val();
            //var user_card_id = $("#content .user-card-area").attr("data-card-id");
            var payType = $(this).attr("data-payType");//商城支持的订单支付方式 0：仅货到付款，1：仅微信支付，2：货到付款加微信支付
            $.ajax(
                {
                  	type:"post",
                  	url:"./index.php?c=store&a=addOrder",
                  	data:{
                      	"gids":gids,
                      	"cgids":cgids,
                      	"counts":counts,
                      	"pay_method":payType,
                      	"checkMember_id":checkMember_id,
                      	"total_price":total_price,
                      	"clinicID":clinicID,
                  	},
                  	dataType:"json",
                  	beforeSend:function(xhr){
                       	//显示“加载中。。。”
                       	showDialog('#loadingDialog');
                  	},
                  	complete:function(){
				   		//隐藏“加载中。。。”
                	  	hideDialog('#loadingDialog');
                  	},
                  	success:function(json,statusText){
                      	if(json.errorCode == 0){
                    	  	if(json.data.state == 0){
	                    	  	showConfirmDialog('#successConfirmDialog','','订单提交成功','&#xe683;','',function(){
	                    		  	var oid = json.data.addOrderId;//生成的订单记录主键id
	                              	//商城支持的订单支付方式 0：仅货到付款，1：仅微信支付，2：货到付款加微信支付
	                              	if(payType == 0){
	                                  	//跳转到仅货到付款的页面
	                                  	window.location.href = "./index.php?c=store&a=daofuOrderPage&id="+oid;
	                              	}else if(payType == 1 || payType == 2){
	                                  	//跳转到支持货到付款加微信支付的页面
	                                  	window.location.href = "./pay/request/code.php?&oid="+oid+"&module=storePay";
	                              	}
	                    	  	});
                      	 	}else{
                      		  	showConfirmDialog('#successConfirmDialog','','订单提交成功','&#xe683;','',function(){
                      			  window.location.href = "./index.php?c=store&a=orderList&state=1";
                      		  });
                      	  }
                      	}else{
                          	showDialog("#errorDialog", "下单失败", json.errorInfo );
                          	return false;
                      	}
                  	},
                  	error:function(xhr,status,thrown){
                	  	showDialog('#errorDialog','','网络异常，请求失败！','');
                      	return false;
                  	}
                }
            );
       });
		
		/*
        //其他地址
        $(".order-address-area .other-address .more-address").click(function(){
            var userAddress = $(".order-address-area .user-address.current-use");
            if(userAddress.length > 0){
                userAddress.removeClass("current-use");
                $(".order-address-area .new-address").addClass("current-use");
            }else{
                $(".order-address-area .new-address").removeClass("current-use");
                $(".order-address-area .user-address").addClass("current-use");
            }
        });
        */
    }
	
	/**
	 * 在没有开启优惠券的情况下,伴随数量改变,修改运费状态提示信息
	 
	function modifyFeeState(){
//		if($("#content #couponSelector").length = 0){
			//修改运费提示文字
    		var html = '';
    		if(totalPrice > feeBelow){  //不需要邮费
    			html = '免邮';
    		}else{
    			html = '快递 <span id="exp-fee">'+oriFee.toFixed(2)+'</span>元';
    		}
    		$("#fee-below").html(html);
//		}
	}
	*/
	
	/**
	 * 点击积分,重新计算
	 
	function pointsClick(){
		var _this = $(".order-bottom-area .points-money p.p1 .right");
    		var balanceMoney = tmpBalanceMoney();
    		var totalPrice = getTotalMoney();
    		var pointsMoney = tmpPointsMoney();
    		var state = isAllPointsPay();  //积分抵现状态 1全抵,0未全抵
    		var allBalance = isAllBalancePay();	//余额抵现状态 1 全抵,0未全抵
 	  		var firTotalPoints = getMaxUsePoints();
    		var firPoints = getAllPointsMoney();
		    var firBalance = getAllBalanceMoney();
		    var firTotalPrice = getFirTotalPrice();
    		if(_this.parent().hasClass('active')){  //1. 取消选积分积分使用的勾选状态
    			_this.parent().removeClass('active');
    			
    			if(state ==1){
    				if(allBalance == 1){
    					balanceMoney = totalPrice;
    				}else{
    					balanceMoney = firBalance;
	    				if(allBalance !=1){
	    					$("#content .balance-money p .right").bind('click',balanceClick);
	    				} 
    				}
    				//判断余额是否被选中
    				if($(".order-bottom-area .balance-money p").hasClass('active')){
	    					totalPrice = 0.00;
	    			}   				
    			}else{
					pointsMoney = firPoints;   					
					if($(".order-bottom-area .balance-money p").hasClass('active')){
    					totalPrice = parseFloat(totalPrice - firBalance).toFixed(2);
    				}
					if(allBalance == 1){
						balanceMoney = totalPrice;
					}else{
						balanceMoney = firBalance;
					}
    			}
    		}else{	//2. 选择使用积分
    			_this.parent().addClass('active');
  				//	2.1 积分能全抵: 维护:页脚实际支付总价, 余额抵现金额, 余额选中状态
    			if(state == 1){
    				pointsMoney = totalPrice;
    				//totalPoints = parseFloat(totalPrice / pointsValue);
    				balanceMoney = 0.00;
    				totalPrice = 0.00;
    				$("#content .balance-money p").removeClass('active');
					if(allBalance !=1){	//取消余额使用状态
						$("#content .balance-money p .right").unbind('click',balanceClick);
					} 
				//	2.2 积分不能全抵订单价格
    			}else{
    				if(allBalance ==1){
    					//	2.2.1余额全抵
	    				balanceMoney = parseFloat(balanceMoney - pointsMoney).toFixed(2);
	    				totalPrice = parseFloat(totalPrice - pointsMoney).toFixed(2);
	    			}else{
	    				//	2.2.2余额不能全抵
	    				if( parseFloat(firPoints) >= parseFloat(totalPrice)){
	    					totalPrice = 0.00;
	    					balanceMoney = 0.00;
	    					$("#content .balance-money p .right").unbind('click');
	    				}else{
	    					//2.2.2.1 积分+余额  不能全抵:	页脚总价	(totalPrice) - 积分全用抵现 (firPoints)  >  余额全用抵现(firBalance)
	    					if(parseFloat(totalPrice - firPoints) >= parseFloat(firBalance)){
	    						balanceMoney = firBalance;
	    						pointsMoney = firPoints;
	    						totalPrice = parseFloat(totalPrice - firPoints).toFixed(2);
	    						if($(".order-bottom-area .balance-money p").hasClass('active')){
	    							totalPrice = parseFloat(totalPrice  - firBalance).toFixed(2);
	    						}
	    					//2.2.2.2积分+余额  能全抵
	    					}else{
	    						
	    						//获取有没有使用余额
	    						$activeBalanceMoney = $(".balance-money p.active[data-balance]");
	    						var activeBalanceLength = $activeBalanceMoney.length;
	    						var activeBalanceMoney = activeBalanceLength > 0 ? parseFloat($activeBalanceMoney.attr("data-balance")) : 0;//当前余额支付金额
	    						balanceMoney = parseFloat(activeBalanceMoney) || parseFloat(totalPrice - firPoints);
	    						pointsMoney = activeBalanceLength > 0 ? parseFloat(totalPrice - activeBalanceMoney).toFixed(2) : firPoints ;
	    						
	    						totalPrice = parseFloat(totalPrice - firPoints).toFixed(2);
	    						if($(".order-bottom-area .balance-money p").hasClass('active')){
	    							totalPrice = 0.00;
	    						}
	    					}
	    				}
	    			}	
    			}
    		}
    		//totalPoints = parseFloat(pointsMoney / pointsValue).toFixed(2);
    		totalPoints = Math.ceil(pointsMoney / pointsValue);
    		$(".order-bottom-area .points-money p.p1 .total-points").html(parseInt( totalPoints ));  //
    		$(".balance-money #balance-money").html(balanceMoney); //.toFixed(2)
    		$(".order-bottom-area .points-money #points-money").html(pointsMoney);  //
    		$("#final-total-price").text(totalPrice);
    		//$('.order-bottom-area .money').html(totalPrice);
    		$('.bottom-nav .money').html(totalPrice);
    	}
    	*/
    	
    	/**
    	 * 点击余额重新计算
    	 
    	function balanceClick(){    		
    		var _this = $(".order-bottom-area .balance-money p.p1 .right");
    		var balanceMoney = tmpBalanceMoney();
    		var totalPrice = getTotalMoney();
    		var pointsMoney = tmpPointsMoney();
    		var state = isAllPointsPay();
    		var allBalance = isAllBalancePay();
  		    var firTotalPoints = getMaxUsePoints();
    		var firPoints = getAllPointsMoney();
		    var firBalance = getAllBalanceMoney();
		    var firTotalPrice = getFirTotalPrice();
		    //1. 取消使用余额支付
    		if(_this.parent().hasClass('active')){
    			_this.parent().removeClass('active');
   			
    			if(allBalance ==1){
    				totalPoints = firTotalPoints;   				
    				if(state == 1){
    					pointsMoney = totalPrice;
    				}else{
    					pointsMoney = firPoints;
    					$("#content .points-money p .right").bind('click',pointsClick);
    				}
    			}else{				
					
					balanceMoney = firBalance;
					if($(".order-bottom-area .points-money p").hasClass('active')){
    					totalPrice = parseFloat(totalPrice  - firPoints).toFixed(2);
    				}
					if(state == 1){
						pointsMoney = totalPrice;
					}else{
						pointsMoney = firPoints;
					}
    			}
    		//2. 选择使用余额支付
    		}else{
    			_this.parent().addClass('active');
    			//1. 余额大于 订单总额
    			if(allBalance ==1){
    				balanceMoney = totalPrice;
    				pointsMoney = 0.00;
    				totalPrice = 0.00;
    				//totalPoints = 0.00; 
    				$("#content .points-money p").removeClass('active');
    				if(state != 1){
    					$("#content .points-money p .right").unbind('click');
    				}
				}else{
					if(state == 1){ //2. 积分能全抵
						balanceMoney = firBalance;
						totalPrice = parseFloat(totalPrice - firBalance).toFixed(2);
						pointsMoney = parseFloat(pointsMoney - balanceMoney).toFixed(2);						
					}else{
						//3. 积分余额均不能全抵
						//3.1 余额 + 积分 < 页脚实际支付总额
						if(parseFloat(totalPrice - firBalance) >= parseFloat(firPoints)){
    						pointsMoney = firPoints;
    						balanceMoney = firBalance;
    						//totalPoints = firTotalPoints;
    						totalPrice = parseFloat(totalPrice - firBalance).toFixed(2);
    						if($(".order-bottom-area .points-money p").hasClass('active')){
    							totalPrice = parseFloat(totalPrice  - firPoints).toFixed(2);
    						}	
    					//3.2 余额 + 积分 > 页脚实际支付总额
	    				}else{	    						
    						//获取是否已经使用积分抵现
    						$activePointsMoney = $(".points-money p.active[data-points]");
    						var activePointsLength = $activePointsMoney.length;
    						var activePointsMoney = activePointsLength > 0 ? parseFloat($activePointsMoney.find("#points-money").text()) : 0;//当前积分抵用金额
    						pointsMoney = parseFloat(activePointsMoney) || parseFloat(totalPrice - firBalance).toFixed(2);
    						balanceMoney = activePointsLength > 0 ? (totalPrice - activePointsMoney) : firBalance ;
    						
    						
    						totalPrice = parseFloat(totalPrice - firBalance).toFixed(2);
    						if($(".order-bottom-area .points-money p").hasClass('active')){
    							totalPrice = 0.00;
    						}	    						
    					}
					}
				}
    		}
    		//totalPoints = parseFloat(pointsMoney / pointsValue).toFixed(2);
    		totalPoints = Math.ceil(pointsMoney / pointsValue);
    		$(".order-bottom-area .points-money p.p1 .total-points").html(totalPoints);
    		$(".balance-money #balance-money").html(balanceMoney);
    		$(".order-bottom-area .points-money #points-money").html(pointsMoney);
    		$("#final-total-price").text(totalPrice);
    		//$('.order-bottom-area .money').html(totalPrice);
    		$('.bottom-nav .money').html(totalPrice);
    		
    	}
    	*/
    	
    	/**
	     * 计算商品总价格
	     
	    function calcTotalPrice(){
	        var goods = $(".order-goods-list .goods-list");//所有订单商品
	        var totalPrice = 0;
	        goods.each(function(){
//	            var selectedTr = $(this).parents("tr");
	            var price = goods.find(".goods-price .price").html();//单价
	            var quantity = goods.find(".goods-count .count").html();//数量
	            totalPrice = totalPrice + (parseFloat(price)* parseInt(quantity));
	        });
	        //重写总价格
	        $(".goods-total .money").html(totalPrice);
			$(".bottom-nav .money").html(totalPrice);
	    }
    	*/
    	
    	/**
	     * 计算商品总件数
	     
	    function calcTotalCounts(){
	        var goods = $(".order-goods-list .goods-list");//所有订单商品
	        var totalCounts = 0;
	        goods.each(function(){
	            var quantity = goods.find(".goods-count .count").html();//数量
	            totalCounts = totalCounts + parseInt(quantity);
	        });
	        //重写总件数
	        $(".order-bottom-area .number").html(totalCounts);
			$(".bottom-nav .num").html(totalCounts);
			$(".quantity-operation-area .quantity-value").val(totalCounts);
	    }
	    */
	
    init();
});
