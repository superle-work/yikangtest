$(function(){
    /**
     * 页面初始化
     */
    function init(){

    	bindEvent();
        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $("#myForm").validate(validateInfo);
    }

    /**
     * 事件绑定
     */
    function bindEvent(){
    	//修改商品信息
    	$('#save').click(function() {
    		updateGoods();
    	});

        //计算优惠价格
        $("#ori_price,#discount").blur(function(){
            var discount=$("#discount").val();
            var ori_price=parseFloat($("#ori_price").val());
            if(discount && ori_price){
                var price= ori_price*discount/100;
                $("#price").val(price);
            }
        })
        
    	//返回
    	$('#back').click(function() {			 
            window.history.go(-1);
            //window.location.href = './admin.php?c=store_goods&a=goodsList';
    	});
        //添加图片事件
        $("#imgurl").change(function(){
            var filepath=$(this).val();
            var extStart=filepath.lastIndexOf(".");
            var ext=filepath.substring(extStart,filepath.length).toUpperCase();
            if(ext.toLowerCase()!=".jpg" && ext.toLowerCase()!=".jpeg"
                && ext.toLowerCase()!=".png" && ext.toLowerCase()!=".gif"){
                $(this).val("");
                responseTip(1,"文件格式不正确，仅支持jpg、jpeg、gif、png格式，文件小于5M！",2000);
                return false;
            }else{
                $("#imgFlag").val(1);
            }
        });

        //辅图上传
        $("#myForm .side-image").change(function(){
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
                return false;
            }else{
                //imgFlag 1 更新，2 添加，3 删除
                if($(this).parent().find(".thumb").length>0){
                    //已存在图片，标识当前为更新操作
                    $(this).parent().find(".imgFlag").val(1);
                }else{
                    $(this).parent().find(".imgFlag").val(2);
                }

            }
        });

        //辅图删除
        $("#myForm .delete-slide-image").click(function(){
            $(this).parent().find(".imgFlag").val(3);//标识辅图删除
            $(this).parent().find(".thumb").remove();
            $(this).parent().find(".side-image").remove();
            $(this).remove();
        });
    }

    /**
     * 修改商品
     */
    function updateGoods(){
        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,goodsFormOptions));
    }
    /**
     * 提交添加商品信息的表单配置
     */
    var  goodsFormOptions={
        url:'./admin.php?c=store_goods&a=updateGoods',
        success:successResponse,
        error:errorResponse
    };
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

    //表单验证信息
    var validateInfo ={
        rules:{
            name:{//商品名称
                required:true
            },
            good_type:{//商品类别
                required:true
            },
            price:{//商品现价
                required:true,
            },
        },
        messages:{
            name:{//商品名称
                required:"必填项"
            },
            good_type:{//商品类别
                required:"必填项"
            },
            price:{//商品标准价格
                required:"必填项",
                goodsPrice:"价格由数字组成,最多包含两位小数",
            },
        },
        errorPlacement:function(error,element){
            //var name = element.attr("name");
            element.parent().next().append(error);
        }
    };
    init();
});