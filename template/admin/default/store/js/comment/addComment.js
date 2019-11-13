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
        //
        $('#save').click(function() {
        	getSublistJson();
            insertTeacher();
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
                return false;
            }

        });
        
        //添加图片事件
        $("#video_img").change(function(){
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
            }

        });
        
         //添加辅图按钮
        $(".inner-section .helper-image-content .add").click(addImageFile);
        //操作改变辅图
        $(".inner-section .helper-image-content .helper_imgurl").change(helperUrlChange);

    }
    /**
     * 更新教练
     */
    function insertTeacher(){
        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,teacherFormOptions));
    }

    /**
     * 提交添加考试信息的表单配置
     */
    var  teacherFormOptions={
        url:'./admin.php?c=store_comment&a=insertComment',
        success:successResponse,
        error:errorResponse
    };
    /**
     * 添加考试信息得到服务器响应的回调方法
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
            name:{
                required:true
            },
            phone:{
                required:true,
                digits:true
            },
            position:{
                required:true
            },
            imgurl:{
                required:true,
                accept:"jpg,jpeg,gif,png"
            },
            favourite_course:{
                required:true
            }
        },
        messages:{
            name:{
                required:"必填项"
            },
            phone:{
                required:"必填项",
                digits:"号码必须为整数"
            },
            position:{
                required:"必填项"
            },
            imgurl:{
                required:"请选择图片",
                accept:"仅支持jpg、jpeg、gif、png格式"
            },
            favourite_course:{
                required:"必填项"
            }
        },
        errorPlacement:function(error,element){
            element.parent().next().append(error);
        }
    };
    
    /**
     * 辅图改变url地址
     */
    function helperUrlChange(){
        var filepath = $(this).val();
        var extStart = filepath.lastIndexOf(".");
        var ext = filepath.substring(extStart,filepath.length).toUpperCase();
        if(ext.toLowerCase() != ".jpg" && ext.toLowerCase() != ".jpeg"
            && ext.toLowerCase() != ".png" && ext.toLowerCase() != ".gif"){
            $(this).val("");
            responseTip(1,"文件格式不正确，仅支持jpg、jpeg、gif、png格式，文件小于5M！",2000);
            return false;
        }else{
        	$(this).parent().addClass("active");
        }
        
    }
    
     /**
     * 添加一个辅图file
     */
    var helper_num = 0;
    function addImageFile(){
    	helper_num ++;
    	var add_html = '<div class="helper-image-list bottom">'
                      +'<input type="file"  name="helper_image'+helper_num+'" class="helper_imgurl'+helper_num+'">'
                      +'</div>'
                      +'<div class="manage-button bottom"><a href="javascript:;" class="btn btn-default btn-sm del">删除</a></div>'
                      +'<div class="cf"></div>';
    	$(".inner-section .helper-image-content .cf").last().after(add_html);
    	$(".inner-section .helper-image-content .del").click(function(){
    		$(this).parent().prev().remove(); 
    		$(this).parent().next().remove(); 
    		$(this).parent().remove();
    	})
    	$(".inner-section .helper-image-content .helper_imgurl"+helper_num).change(helperUrlChange);
    }
    
    /**
     * 获取辅图json的sublist
     */
    function getSublistJson(){
    	var sublist = [];
    	$(".inner-section .helper-image-content .helper-image-list.active").each(function(){
    		var item = {"file_name":$(this).find("input[type='file']").attr('name')};
    		sublist.push(item);
    	});
    	$(".inner-section #sublist").val(JSON.stringify(sublist));
    }
    
    init();
    
})

