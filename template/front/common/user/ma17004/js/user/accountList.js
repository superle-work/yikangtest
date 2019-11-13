$(function(){
    /**
     * 初始化
     */
    function init(){
        var width = $("#container").width();//计算当前窗口的宽度
        $("#container .account-bottom-area").width(width);//将底部导航的宽度设置与移动设备相同
        bindEvent();
    }

    /**
     * 绑定事件
     */
    function bindEvent(){
        //用户变化屏幕方向时调用
        $(window).bind( 'orientationchange resize', function(e){
            width = $("#container").width();//计算当前窗口的宽度
            $("#container .account-bottom-area").width(width);//将底部导航的宽度设置与移动设备相同
        });

        /**
         * 顶部“编辑”事件,
         */
        $(".account-top-area .edit").click(function(){
            $(this).hide();//隐藏“编辑”
            $(".account-top-area .complete").show();//显现“完成”
            //隐藏选择 展现删除商品功能
            $(".account-list .item-select").hide();
            $(".account-list .item-delete").show();
            $(".account-list .item-delete .un-selected").show();
            $(".account-list .item-delete .selected").hide();

            //底部功能区对应变化
            $(".account-bottom-area #defaultAccount").hide();
            $(".account-bottom-area #addAccount").hide();

            $(".account-bottom-area #selectAllDelete").show();
            $(".account-bottom-area #selectAllDelete .all-selected").hide();
            $(".account-bottom-area #selectAllDelete .not-all-selected").show();
            $(".account-bottom-area #deleteAll span").removeClass("can-use");
            $(".account-bottom-area #deleteAll").show();

        });
        /**
         * 顶部编辑“完成”事件，隐藏删除功能
         */
        $(".account-top-area .complete").click(function(){
            $(this).hide();//隐藏“完成”

            $(".account-top-area .edit").show();//显现“编辑”
            $(".account-list .item-delete").hide();
            $(".account-list .item-select").show();
            //底部功能区对应变化
            $(".account-bottom-area #selectAllDelete").hide();
            $(".account-bottom-area #deleteAll").hide();
            $(".account-bottom-area #defaultAccount").show();
            $(".account-bottom-area #addAccount").show();


        });
        /***
         * 底部功能区--全选功能
         */
        $(".account-bottom-area #selectAllDelete").click(function(){
            var _this = $(this);
            if(_this.hasClass("all-selected")){
                //当前为选中状态,则取消选中状态
                _this.find(".all-selected").hide();
                _this.find(".not-all-selected").show();
                _this.removeClass("all-selected");
                //将所有账户置为未选中状态
                var selector = ".item-delete";
                $(".account-bottom-area #deleteAll span").removeClass("can-use");
                $(".account-list .account-area "+selector).removeClass("selected");
                $(".account-list .account-area "+selector+" .un-selected").show();
                $(".account-list .account-area "+selector+" .selected").hide();
            }else{
                //若当前为未选中状态，则置为选中状态
                _this.find(".not-all-selected").hide();
                _this.find(".all-selected").show();
                _this.addClass("all-selected");
                //将所有商品置为选中状态
                var selector  = ".item-delete";
                $(".account-bottom-area #deleteAll span").addClass("can-use");
                $(".account-list .account-area "+selector).addClass("selected");
                $(".account-list .account-area "+selector+" .un-selected").hide();
                $(".account-list .account-area "+selector+" .selected").show();
            }
        });
        /**
         * 账户列表单选事件,账户删除--单选事件
         */
        $(".account-list .item-select,.account-list .item-delete").click(function(){
            var _this = $(this);
            if(_this.hasClass("selected")){//当前为选中状态,则取消选中状态
                if(_this.hasClass("item-delete")){
                    //当前为删除商品单选事件
                    _this.find(".selected").hide();
                    _this.find(".un-selected").show();
                    _this.removeClass("selected");

                    var selector = "";
                    //将底部功能“全选”状态置为未选中状态
                    selector = "#selectAllDelete";
                    var elem = $(".account-bottom-area "+selector);
                    if(elem.hasClass("all-selected")){
                        elem.removeClass("all-selected");
                        elem.find(".not-all-selected").show();
                        elem.find(".all-selected").hide();
                    }
                }
            }else{
                //若当前为未选中状态，则置为选中状态
                if(_this.hasClass("item-delete")){
                    //当前为删除商品单选事件
                    _this.find(".un-selected").hide();
                    _this.find(".selected").show();
                    _this.addClass("selected");
                    var selector = "",selector2 = "";
                    selector = "#selectAllDelete";
                    selector2 = ".item-delete";
                    if($(".account-list "+selector2).length == $(".account-list "+selector2+".selected").length){
                        //都为选中状态时，将底部功能“全选”状态置为选中状态
                        var elem = $(".account-bottom-area "+selector);
                        elem.addClass("all-selected");
                        elem.find(".not-all-selected").hide();
                        elem.find(".all-selected").show();
                    }
                    if($(".account-list .item-delete.selected").length > 0){
                        $(".account-bottom-area #deleteAll span").addClass("can-use");
                    }else{
                        $(".account-bottom-area #deleteAll span").removeClass("can-use");
                    }
                }else{
                    //账户单选事件
                    $(".account-list .item-select").removeClass("selected");
                    $(".account-list .item-select").find(".selected").hide();
                    $(".account-list .item-select").find(".un-selected").show();
                    _this.find(".un-selected").hide();
                    _this.find(".selected").show();
                    _this.addClass("selected");
                }

            }

            //同步维护底部“删除”按钮状态
            if(_this.hasClass("item-delete")){
                if($(".account-list .item-delete.selected").length > 0){
                    $(".account-bottom-area #deleteAll span").addClass("can-use");
                }else{
                    $(".account-bottom-area #deleteAll span").removeClass("can-use");
                }
            }
        });
        //置为默认
        $("#container .account-bottom-area #defaultAccount").click(function(){
            //获取当前单选的账户选项
            var option = $("#content .account-list .item-select.selected");
            if(option.length == 0){
                alert("你还没有选择账户呢！");
            }else{
                var id = option.parents(".account-area").attr("data-id");
                $.ajax({
                    url:"./index.php?c=base_user&a=setDefaultAccount",
                    type:"post",
                    data:{"id":id},
                    dataType:"json",
                    success:function(json,statusText){
                        if(json.errorCode == 0){
                            alert("操作成功！");
                            window.location.reload();
                        }else{
                            alert(json.errorInfo);
                        }

                    },
                    error:function(){
                        alert("抱歉，网络异常！");
                    }
                });
            }

        });

        //删除账户
        $("#container #deleteAll span").click(function(){
            if(!$(this).hasClass("can-use")){
                return false;//不具有删除条件时直接退出
            }

			if(!confirm("确认删除吗？")){
                return false;
            }
            var ids = [];
            var elems = $(".account-list .item-delete.selected");

            elems.each(function(index){
                var id = $(this).parents(".account-area").attr("data-id");
                ids.push(id);
            });

            var _this = $(this);
            ids = ids.join(",");
            $.ajax({
                url:"./index.php?c=base_user&a=deleteAccount",
                type:"post",
                data:{"ids":ids},
                dataType:"json",
                success:function(json,statusText){
                    if(json.errorCode == 0){
                        window.location.reload();
                    }else{
                        alert(json.errorInfo);
                    }

                },
                error:function(){
                    alert("抱歉，网络异常！");
                }
            });
        });

        //账户类型下拉事件
        $(".account_type").change(function(){
            var value = $(this).val();
            if(value == 2){
                //银行账户
                $(this).parents("tbody").find(".account_bank-info").show();
            }else{
                $(this).parents("tbody").find(".account_bank-info").hide();
                $(this).parents("tbody").find(".account_bank-info .account_bank").val("");
            }

        });
        //账户编辑
        $("#content .account-list .edit").click(function(){
            var _this = $(this);
            var id = _this.attr("data-id");
            $.ajax({
                url:"./index.php?c=base_user&a=getAccountById",
                type:"post",
                data:{"id":id},
                dataType:"json",
                success:function(json,statusText){
                    if(json.errorCode == 0){
                        var data = json.data;
                        var id = data.id;

                        $("#editAccountModal .account_type").val(data.account_type);
                        $("#editAccountModal .account").val(data.account);
                        $("#editAccountModal .account_bank").val(data.account_bank);
                        $("#editAccountModal .name").val(data.name);
                        $("#editAccountModal .save-account").attr("data-id",id);

                        $("#editAccountModal").modal('show');
                    }else{
                        alert(json.errorInfo);
                    }

                },
                error:function(){
                    alert("请求失败，网络异常！");
                }
            });
        });
        //账户编辑--保存事件
        $("#editAccountModal .save-account").click(function(){
            var _this = $(this);
            var id = _this.attr("data-id");
            var account_type = $.trim($("#editAccountModal .account_type").val());//账户类型
            var account = $.trim($("#editAccountModal .account").val());//
            var account_bank = $.trim($("#editAccountModal .account_bank").val());//银行及其所属支行
            var name = $.trim($("#editAccountModal .name").val());//户主

            if(account_type == 2 && (account == "" || account_bank == "" || name == "")){//为银行卡类型时、银行信息必填
                alert("请先完善信息！");
                return false;
            }else if(account_type !=2 &&(account == "" || name == "")){
                alert("请先完善信息！");
                return false;
            }

            $.ajax({
                url:"./index.php?c=base_user&a=updateAccount",
                type:"post",
                data:{"id":id,
                      "account_type":account_type,
                      "account":account,
                      "account_bank":account_bank,
                      "name":name},
                dataType:"json",
                success:function(json,statusText){
                    if(json.errorCode == 0){
                        $("#editAccountModal").modal('show');
                        window.location.reload();//成功后刷新页面
                    }else{
                        alert(json.errorInfo);
                    }

                },
                error:function(){
                    alert("请求失败，网络异常！");
                }
            });
        });
        //添加账户
        $("#container .account-bottom-area #addAccount").click(function(){
            $("#addAccountModal").modal("show");
        });
        //添加新账户--提交新账户
        $("#addAccountModal .add-account").click(function(){

            var account_type = $.trim($("#addAccountModal .account_type").val());//账户类型
            var account = $.trim($("#addAccountModal .account").val());//
            var account_bank = $.trim($("#addAccountModal .account_bank").val());//银行及其所属支行
            var name = $.trim($("#addAccountModal .name").val());//户主
            
            if(account_type == 2){
            	if(account == "" || account_bank == "" || name == ""){
                    alert("请先完善信息！");
                    return false;
                }
            }else{
            	if(account == "" || name == ""){
                    alert("请先完善信息！");
                    return false;
                }
            }
            

            $.ajax({
                url:"./index.php?c=base_user&a=addAccount",
                type:"post",
                data:{
                    "account_type":account_type,
                    "account":account,
                    "account_bank":account_bank,
                    "name":name
                    },
                dataType:"json",
                success:function(json,statusText){
                    if(json.errorCode == 0){
                        $("#addAccountModal").modal('show');
                        window.location.reload();//成功后刷新页面
                    }else{
                        alert(json.errorInfo);
                    }

                },
                error:function(){
                    alert("请求失败，网络异常！");
                }
            });
        });

    }

    init();
});