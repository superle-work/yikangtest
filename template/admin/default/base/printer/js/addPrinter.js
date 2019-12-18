$(function(){
	
    /**
     * 页面初始化
     */
    function init(){
    	addressInit('province', 'city', 'area','江西');
        bindEvent();
        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $("#myForm").validate(validateInfo);
        
        $("#allmap").on("click",".BMap_Marker",function(event){
        	 event.stopPropagation();
        })
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
		//enter键盘事件
        $(".inner-section .map-search-row  .keyword").keydown(function(event){
            event = event ? event: window.event;
            if(event.keyCode == 13){
                var keyword = $('.map-search-row .keyword').val().trim();
                if(keyword == "") return false;
	    		$(".content .inner-section #longitude,.content .inner-section #latitude").val('');
	    		setMap(keyword);
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
            responseTip(json.errorCode,"恭喜您，操作成功！",1500,function(){window.history.go(-1);});
        }else{
            responseTip(json.errorCode,json.errorInfo,1500);
        }
    }
    
    /**
     * 提交添加商品信息的表单配置
     */
    var  goodsFormOptions={
        url:'admin.php?c=base_printer&a=insertPrinter',
        success:successResponse,
        error:errorResponse
    };
    

    //表单验证信息
    var validateInfo ={
        rules:{
            num:{//打印机编号
                required:true
            },
            province:{//购买商品赠送的积分
                required:true,
            },
            city:{
              required:true
            },
            area:{
                required:true
            },
        },
        messages:{
            num:{//打印机编号
                required:"必填项"
            },
            province:{
                required:"必选项",
            },
            city:{
                required:"必选项"
            },
            area:{
                required:"必选项"
            },
        },
        errorPlacement:function(error,element){
            //var name = element.attr("name");
            element.parent().next().append(error);
        }
    };
    
    init();
});

