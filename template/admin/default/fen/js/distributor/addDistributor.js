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
    	//添加分销商
    	$('#save').click(function() {
			addDistributor();
		});
        
    	 $("#myForm #phone").blur(function(event){
             var number = $(this).val();
             if(number == ""){
                 return false;
             }
             //是否为数字组成,首位不为零,总位数不超过7
             var reg = /^[0-9]{11}$/;
             if(!reg.test(number)){//不合法时进行提示
                 $(this).val("");
                 $(this).parent().next().html('<label for="name" generated="true" class="error">电话号码为11位数字</label>');
             }else{
            	 $(this).parent().next().find(".error").remove();
             }
         });
    	
    }

    /**
     * 添加标签
     */
    function addDistributor(){
        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,myFormOptions));
    }

    /**
     * 提交添加商品信息的表单配置
     */
    var  myFormOptions={
        url:'./admin.php?c=fen_distributor&a=updateDistributor',
        success:successResponse,
        error:errorResponse
    };
    
    //表单验证信息
    var validateInfo ={
        rules:{
            name:{//标签名称
                required:true
            },
            phone:{
                required:true,
                phone:true
            }
        },
        messages:{
            name:{//商品名称
                required:"请输入分销商名称"
            },
            phone:{
                required:"电话号码为11位数字",
                phone:""
            }
        },
        errorPlacement:function(error,element){

            element.parent().next().append(error);
        }
    };
   init();

});