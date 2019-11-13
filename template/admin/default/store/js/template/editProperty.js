$(function(){

    /**
     * 页面初始化
     */
    function init(){
		bindEvent();

    }
    /**
     * 事件绑定
     */
    function bindEvent(){
    	//编辑
    	$('#save').click(function() {
			upateProperty();
		});
        /**
         * 属性的enter事件，-->添加新属性值
         */
        $("#myForm .value-info .value").keydown(function(event){
            event = event?event:window.event;
            if(event.keyCode == 13){
                $("#myForm .append-value").click();
            }
        });

        //删除
        $("#myForm .value-delete").click(deleteValue);

        //为属性添加多个“属性值”输入框
        $("#myForm .append-value").click(function(){
            var clone = $(this).prev().clone(true);
            clone.find(".value").removeClass("edit-value");//去除“修改”属性操作的标识
            clone.find(".value").val("");//新值置为空
            if(clone.find(".value-delete").length == 0){
                clone.append('<span class="value-delete" title="删除"></span>');
                $(this).before(clone);
            }else{
                $(this).before(clone);
            }
			$(this).prev().find(".value").focus();
        });
    }
    //删除“值”
    function deleteValue(){
        var _this = $(this);
        if($("#myForm .value-delete").length == 1){
            $("#myModal .modal-body").html("<p class='text-danger'><b>操作失败，至少要有一项属性值！</b></p>");
            $("#myModal").modal('show');
            return false;
        }
        if(_this.next().hasClass("edit-value")){
            //删除已写入数据库的属性值
            var id = _this.next().attr("pid");
            $.ajax({
                type:"post",
                url:"./admin.php?c=store_template&a=deletePropertyValue",
                data:{"id":id},
                dataType:"json",
                success:function(json,statusText){
                    if(json.errorCode == 0){
                        //删除成功后，
                        _this.parent().remove();
                    }else if(json.errorCode == 1){
                        $("#myModal .modal-body").html("<p class='text-danger'>"+json.errorInfo+"</p>");
                        $("#myModal").modal('show');
                        //定时器，1.5秒后模态框自动关闭
                        setTimeout(function(){
                            $("#myModal").modal('hide');
                        },1500);
                    }else if(json.errorCode == 2){
                        var data = json.data;
                        var templateNames = "";
                        for(var i = 0;i < data.length;i++){
                            if(i < data.length - 1){
                                templateNames += data[i].name +"、";
                            }else{
                                templateNames += data[i].name;
                            }
                        }
                        templateNames = "已在模板<span style='color:blue;'>"+templateNames+"</span>中使用,请先在模板中删除引用！";
                        $("#myModal .modal-body").html("<p class='text-danger'>很抱歉，操作失败，"+templateNames+"</p>");
                        $("#myModal").modal('show');
                    }
                },
                error:errorResponse
            });
        }else{
            //删除未保存的"属性值"
            _this.parent().remove();

        }
    }
    /**
     * 修改
     */
    function upateProperty(){
        var addValues = [];//待添加属性对象
        var editValues = [];//待修改属性对象
		var name = $('#name').val();
		var sort = $('#sort').val();
        //先判断 所有输入框内必须设置属性值
        $("#myForm .value").each(function(){
            var value = $(this).val().trim();
            if(value == ""){
                //输入值不能为空
                $("#myForm .append-value").parent().next().html('<label for="name" generated="true" class="error">请设置所有属性值</label>');
                return false;
            }else {
                if($(this).hasClass("edit-value")){
                    //待修改属性值

                    var property = {"id":$(this).attr("pid"),"value":value};
                    editValues.push(property);
                }else{
                    //待插入属性值
                    var property = {"name":$("#myForm #name").val(),"sort":sort,"value":value};
                    addValues.push(property);
                }
            }
        });
        var values = {"editValues":JSON.stringify(editValues),"addValues":JSON.stringify(addValues),"name":name,"sort":sort};
        $.ajax({
            type:"post",
            url:"./admin.php?c=store_template&a=updateProperty",
            data:values,
            dataType:"json",
            success:function(json,statusText){
                if(json.errorCode == 0){
                    $("#myModal .modal-body").html("<p class='text-success'><b>恭喜您，操作成功！</b></p>");
                    $("#myModal").modal('show');
                    //定时器，1.5秒后模态框自动关闭
                    setTimeout(function(){
                        $("#myModal").modal('hide');
                        window.location.reload();
                    },1500);
                    //alert("添加成功！");
                }else if(json.errorCode == 1){
                    responseTip(json.errorCode,json.errorInfo,1500);

                }
            },
            error:errorResponse
        });
    }
    init();
});