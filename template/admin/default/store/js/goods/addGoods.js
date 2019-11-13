$(function(){
    /**
     * 页面初始化
     */
    function init(){
        bindEvent();
        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        //$("#myForm").validate(validateInfo);
    }
    /**
     * 事件绑定
     */
    function bindEvent(){
        //添加商品
        $('#save').click(function() {
        	$("#myForm").validate(validateInfo);
            addGoods();
        });

        //计算优惠价格
        $("#ori_price,#discount").blur(function(){
            var discount=$('#discount').val();
            var ori_price=parseFloat($("#ori_price").val());
            if(discount && ori_price){
                var price= ori_price*discount/100;
                $("#price").val(price);
            }
        })
        
        
        //添加属性
        $(".add-property-group-wrap .property-group-add").click(function(){
			var new_group = $(".property-tr .property-group-wrap .property-group").eq(0).clone();
			new_group.find('input').val('');
			new_group.find('select option').removeAttr('selected');
			new_group.append("<a href='javascript:;' class='btn btn-danger btn-xs property-group-delete'>删除</a>");
			$(".property-tr .property-group-wrap").append(new_group);
			$(".property-group .property-group-delete").click(function(){
    			$(this).parents('.property-group').remove();
    		});
		});
        
        //选择商品规格
        $("#tid").change(function(){
        	if($(this).val() != ''){
        		$(".property-tr").removeClass('hidden');
        		var tid = $(this).val();
        		$.ajax({
        			 url:"./admin.php?c=store_template&a=findPropertyList",
	                type:"post",
	                data:{"tid":tid},
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
	                    	$(".property-tr").removeClass('hidden');
	                    	var html = '<div class="property-group">';
	                    	var myList = json.data;
	                    	for(var i = 0; i < myList.length;i++){
	                    		var obj = myList[i];
	                    		var optionList =  obj.list;
	                    		var option_html = "";
	                    		for(var j=0; j < optionList.length; j++){
	                    			var option = optionList[j];
	                    			option_html += "<option value="+option.value+">"+option.value+"</option>";
	                    		}
	                    		html +='<span class="span property">'
	                    					+'<span  class="property-name">'+obj.name+'</span>'
	                    					+'<select class="property-value">'
	                    						+option_html
	                    					+'</select>'
	                    				+'</span>';	
	                    	}
	                    	html += '<span class="span">'
	            					+'<span>原价</span>'
	            					+'<input type="text" class="oriprice">'
	            					+'</span>'
	                    			+'<span class="span">'
	                    			+'<span>现价</span>'
	                    			+'<input type="text" class="price">'
	                    			+'</span>'
	                    			+'<span class="span">'
	                    			+'<span>库存</span>'
	                    			+'<input type="text" class="inventory">'
	                    			+'</span>'
	                    		+'</div>';
	                    	if(myList.length == 0){
	                    		$("#tid").val('');
	                    		responseTip(1,"此模板无属性,请在模板列表中重新设置", 3000);
	                    		$(".property-tr .property-group-wrap").html('');
	                    		$(".property-tr").addClass('hidden');
	                    	}else{
	                    		$(".property-tr .property-group-wrap .property-group").remove();
	                    		$(".property-tr .property-group-wrap").append(html);
	                    		$("#inventory").val(0);
	                    		$("#inventory").parents('tr').addClass('hidden');
	                    		$("#standard").val(1);
	                    	}
	                    }else{
	                        responseTip(1,json.errorInfo);
	                    }
	                },
	                error:errorResponse
        		});
        	}else{
        		$(".property-tr .property-group-wrap .property-group").html('');
        		$(".property-tr").addClass('hidden');
        		$("#inventory").val('');
        		$("#inventory").parents('tr').removeClass('hidden');
        		$("#standard").val(0);
        	}
        });

        //商品属性--价格输入合法性验证
        $("#myForm .price,#myForm .oriprice").blur(function(event){
            var number = $(this).val();
            if(number == ""){
                return false;
            }
            //是否为数字组成,首位不为零,总位数不超过7
            var reg = /^[1-9][0-9]{0,3}$|^0$|^([0-9]|[1-9][0-9]{0,3}).[0-9]{1,2}$/;
            if(!reg.test(number)){//不合法时进行提示
                $(this).val("");
                $("#myForm .property-info").html('<label for="name" generated="true" class="error">输入值不合法</label>');
            }else{
                if($("#myForm .property-info label").length > 0){
                    $("#myForm .property-info label").remove();
                }
            }
        });
        $("#myForm .inventory").blur(function(event){
            var number = $(this).val();
            if(number == ""){
                return false;
            }
            var reg = /^[1-9][0-9]{0,3}$|^0$/;
            if(!reg.test(number)){//不合法时进行提示
                $(this).val("");
                $("#myForm .property-info").html('<label for="name" generated="true" class="error">输入值不合法,最多4位</label>');
            }else{
                if($("#myForm .property-info label").length > 0){
                    $("#myForm .property-info label").remove();
                }
            }
        });
        //添加图片事件
        $("#imgurl").change(function(){
            var filepath=$(this).val();
            if(filepath == ""){
                return false;
            }
            var extStart=filepath.lastIndexOf(".");
            var ext=filepath.substring(extStart,filepath.length).toUpperCase();
            if(ext.toLowerCase()!=".jpg" && ext.toLowerCase()!=".jpeg"
                && ext.toLowerCase()!=".png" && ext.toLowerCase()!=".gif"){
                $(this).val("");
                responseTip(1,"文件格式不正确，仅支持jpg、jpeg、gif、png格式，文件小于5M！",2000);
            }

        });

    }

    /**
     * 添加商品
     */
    function addGoods(){
        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,goodsFormOptions));
    }

    /**
     * 添加商品信息得到服务器响应的回调方法
     */
    function successResponse(json,statusText){
        if(json.errorCode == 0){
            responseTip(json.errorCode,"恭喜您，操作成功！",1500,function(){window.history.go(-2);});
        }else{
            responseTip(json.errorCode,json.errorInfo,1500);
        }
    }
    
    /**
     * 提交添加商品信息的表单配置
     */
    var  goodsFormOptions={
        url:'./admin.php?c=store_goods&a=insertGoods',
        success:successResponse,
        error:errorResponse
    };
    

    //表单验证信息
    var validateInfo ={
        rules:{
            name:{//商品名称
                required:true
            },
            good_type:{//商品类别
                required:true
            },
            price:{
              required:true
            },
        },
        messages:{
            name:{//商品名称
                required:"必填项"
            },
            good_type:{//商品类别
                required:"必填项"
            },
            price:{
                required:"必填项"
            },
        },
        errorPlacement:function(error,element){
            //var name = element.attr("name");
            element.parent().next().append(error);
        }
    };
    init();
});

