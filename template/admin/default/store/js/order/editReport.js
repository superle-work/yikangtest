$(function(){
    function init(){
        bindEvent();
    }
    
    function bindEvent(){
        var image_box = '<div class="main">'+
            '<div class="add-btn"><i class="icon iconfont add">&#xe67f;</i></div>'+
            '<form class="myForm"><input class="add-img-btn hidden" name="imgUrl" accept="image/*" type="file"></form>'+
            '<div class="share-ago"><span class="off-text">×</span></div>'+
            '<img class="forum-img hidden" src="" data-url=""/>'+
            '</div>';
        addImageBox();
        function addImageBox() {
            var imageBoxNum = $('.upload-image .main').length;
            var delBtnNum = $('.upload-image .share-ago :visible').length;
            if (imageBoxNum < 6 && delBtnNum == imageBoxNum) {
                $('.upload-image').append(image_box);
                deleteImageBox(); // 激活删除图片的事件
                $('.upload-image .main:last-child .add-btn').click(function() { // 绑定.add-img的click
                    _this = $(this).next('form').find(".add-img-btn");
                    // uploadImage();
                    _this.click();
                    _this.change(function(){                                                    
                        _this.parent(".myForm").ajaxSubmit({
                            async:true,
                            type : 'post',
                            url:"index.php?c=store&a=upLoadImage",
                            dataType : 'json',
                            beforeSend: function(){
                                
                            },
                            complete:function(){
                               
                            },
                            success:function(result){
                                if(result.errorCode == 0){//成功
                                    _the = _this.parents('.main');
                                    _the.children('.forum-img').attr('src', result.data.img_url).removeClass('hidden');
                                    _the.children('.forum-img').attr('data-url', result.data.thumb).removeClass('hidden');
                                    _the.children('.add-btn').hide();
                                    _the.children('.share-ago').show();
                                    addImageBox();
                                }else{
                                    responseTip('',"上传图片失败",1500);
                                }
                            },
                            error:errorResponse
                        });
                    });
                });
            }
            
        }
        //删除图片
        deleteImageBox();
        function deleteImageBox() {
            $(".main .share-ago").click(function(){
                var imageBoxNum = $('.upload-image .main').length;
                var _the = $(this);
                var _this = $(this).parent();
//              if(_the.hasClass("second")){//后来加载的image-box
                    var img_url = _this.children('img').attr('src');
                    $.ajax({
                        url:"./index.php?c=store&a=deleteUpLoadImage",
                        type:"post",
                        data:{"img_url":img_url},
                        dataType:"json",
                        beforeSend:function(xhr){
                        },
                        complete:function(){
                        },
                        success:function(json,statusText){
                            if(json.errorCode == 0){
                                //操作成功
                                _this.remove();
                                addImageBox();
                            }else{
                                responseTip('',"删除失败",1500);
                            }
                        },
                        error:function(){
                            showDialog('#errorDialog','','',1500);
                            responseTip('',"出错啦~,网络异常！",1500);
                        }
                    });
//              }else{
//                  _this.remove();
//                  addImageBox();
//              }
            });
        }
        
        //打包图片
        function getImgJson(){
            var img_list = [];
            
            $('.upload-image .main').each(function() {      
                if($(this).find(".forum-img").attr("src")){
                    var img = {'img_url':$(this).find(".forum-img").attr("src"),'thumb':$(this).find(".forum-img").attr("data-url")}
                    img_list.push(img);
                }           
            })
            return JSON.stringify(img_list);
        }
        
        //提交报告
        $(".btn-area #upload").click(function(){
            var id=$("#id").val();
            var report_desc=$("#report_desc").val();
            var img_str=getImgJson();
            if(img_str && id){
                $.ajax({
                    type:'post',
                    url:'admin.php?c=store_order&a=updateReport',
                    data:{
                        'id':id,
                        'report_desc':report_desc,
                        'img_str':img_str,
                    },
                    dataType:'json',
                    beforeSend:function(xhr){
                    },
                    complete:function(){
                    },
                    success:function(res){
                        if(res.errorCode == 0){
                            responseTip("","提交成功",1000,function(){
                                location.href="admin.php?c=store_order&a=orderList";
                            });
                        }else{
                            responseTip('',"提交失败",1500);
                        }
                    },
                    error:function(res){
                        responseTip('',"提交失败",1500);
                    }
                })
            }
        })
    }
    
    init();
})
