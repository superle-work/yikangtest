$(function(){
    var total = 1;//分页总页面数
    var total_count = 1;//分页总记录数
    var currentPage = 1;//当前页
    var pageSize = pageOption.pageSize;//每页显示的记录数

    function init(){
        myPagination();
        bindEvent();
    }

    function bindEvent(){
//  	$(".right-section #updateHeadImg").click(function(){
//          $.ajax(
//              {
//                  type:"post",
//                  url:"./admin.php?c=base_user&a=groupFans",
//                  beforeSend:function(xhr){
//                      //显示“加载中。。。”
//                      $("#loading").modal('show');
//                  },
//                  data:{},
//                  dataType:"json",
//                  success:function(json,jsonText){
//                      if(json.errorCode == 0){
//                          var groupCount = json.data;//分组数量
//                      	for(var i = 1; i <= groupCount;i++){//批量导入老粉丝
//                      		$.ajax(
//				                {
//				                    type:"post",
//				                    url:"./admin.php?c=base_user&a=asynGroupFansImg",
//				                    data:{"batch":i,"groupCount":groupCount},
//				                    dataType:"json",
//				                    success:function(json,jsonText){
//				                    	if(json.errorCode == 0){
//				                    		//某一次的同步完成  不提醒，避免骚扰用户
//				                    	}else if(json.errorCode == 1){
//				                    		responseTip(1,json.errorInfo);
//								        }else if(json.errorCode == 3){//每次同步成功，不提示，避免干扰用户
//								        	responseTip(0,"同步完成！");
//								        	render(true,currentPage,pageSize);
//								        }
//				                    },
//				                    error:errorResponse
//				                    
//				                });
//                      	}
//                      }else{
//                      	responseTip(1,json.errorInfo);
//                      }
//                  },
//                  complete:function(){
//                      //隐藏“加载中。。。”
//                      $("#loading").modal('hide');
//                  },
//                  error:errorResponse
//              }
//          );
//      });
        
        /**
         * 同步微信昵称
         */
        $(".right-section #updateNickName").click(function(){
            $.ajax(
                {
                    type:"post",
                    url:"./admin.php?c=base_user&a=groupFans",
                    beforeSend:function(xhr){
                        //显示“加载中。。。”
                        $("#loading").modal('show');
                    },
                    data:{},
                    dataType:"json",
                    success:function(json,jsonText){
                        if(json.errorCode == 0){
                            var groupCount = json.data;//分组数量
                        	for(var i = 1; i <= groupCount;i++){//批量导入老粉丝
                        		$.ajax(
				                {
				                    type:"post",
				                    url:"./admin.php?c=base_user&a=asynGroupFansNickname",
				                    data:{"batchs":i,"groupCount":groupCount},
				                    dataType:"json",
				                    success:function(json,jsonText){
				                    	if(json.errorCode == 0){
				                    		//某一次的同步完成  不提醒，避免骚扰用户
				                    	}else if(json.errorCode == 1){
				                    		responseTip(1,json.errorInfo);
								        }else if(json.errorCode == 3){//每次同步成功，不提示，避免干扰用户
								        	responseTip(0,"同步完成！");
								        	render(true,currentPage,pageSize);
								        }
				                    },
				                    error:errorResponse
				                    
				                });
                        	}
                        }else{
                        	responseTip(1,json.errorInfo);
                        }
                    },
                    complete:function(){
                        //隐藏“加载中。。。”
                        $("#loading").modal('hide');
                    },
                    error:errorResponse
                }
            );
        });


        /**
         * 模糊查询事件
         *
         */
        $(".inner-section .search-area .search-btn").click(function(){
           render(true,1,pageSize);
        });
        //enter事件
        $(".inner-section .search-area input").keydown(function(event){
            event = event ? event:window.event;
            if(event.keyCode == 13){
                render(true,1,pageSize);
            }
        });

        //查看用户详情

        //删除
        $(".inner-section .delete").click(function(){
            var id = $(this).attr("data-id");
            myConfirmModal("确定删除吗？",function(){
                $.ajax({
                    url:"./admin.php?c=base_user&a=deleteUser",
                    type:"post",
                    data:{"id":id},
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
                            if(currentPage !=1 && total_count % pageSize == 1){//非首页且末页记录数为1时
                                currentPage = currentPage - 1;
                            }
                            render(true,currentPage,pageSize);
                        }else{
                            responseTip(json.errorCode,json.errorInfo,1500);
                        }
                    },
                    error:errorResponse
                });
            });

        });
    }

    /**
     * 查看用户详情
     */
    function userDetail(){
        var id = $(this).attr("data-id");
        window.location.href = "./admin.php?c=base_user&a=userDetail&id="+id;
    }

    /**
     * 设为管理员
     */
    function userAdmin(){
        var id = $(this).attr("data-id");
        $.ajax({
            url:"./admin.php?c=base_user&a=userAdmin&id="+id,
            type:"post",
            data:{"id":id},
            dataType:"json",
            success:function(json,statusText){
                if(json.errorCode == 0){

                    responseTip(json.errorCode,"恭喜您，操作成功！",1500,function(){render(true,currentPage,pageSize);});

                }else{
                    responseTip(json.errorCode,json.errorInfo,1500);
                }
            },
            error:errorResponse
        });
    }

    /**
     * 密码重置
     */
    function restPwd(){
        var id = $(this).attr("data-id");
        $.ajax({
            url:"./admin.php?c=base_user&a=resetPassword",
            type:"post",
            data:{"id":id},
            dataType:"json",
            success:function(json,statusText){
                if(json.errorCode == 0){

                    responseTip(json.errorCode,"恭喜您，操作成功！",1500,function(){render(true,currentPage,pageSize);});

                }else{
                    responseTip(json.errorCode,json.errorInfo,1500);
                }
            },
            error:errorResponse
        });
    }
    /**
     * 案例分页显示方法
     */
    function myPagination(){
        render(true,1,pageSize);
        //调用公共分页方法
        pagination("#page-selection",{pageSize:pageSize,total:total},render);

    }
    /**
     * 获取模糊参数
     */
    function getSelectInfo(){
        var nick_name = $.trim($(".search-area .nickname").val());
        var subscribe = $.trim($(".search-area #subscribe").val());
        var name = $.trim($(".search-area .name").val());
        var phone = $.trim($(".search-area .phone").val());
        var selectInfo = {
            "nick_name":nick_name,
            "name":name,
            "phone":phone,
            "subscribe":subscribe
        };
        return selectInfo;
    }
    /**
     * 分页动态渲染数据
     * @param async ajax请求是否异步
     * @param pageIndex 当前显示页
     * @param pageSize 每页显示记录数
     */
    function render(async,pageIndex,pageSize){
        var selectInfo = getSelectInfo();
        selectInfo.pageIndex = pageIndex;
        selectInfo.pageSize = pageSize;
        $.ajax({
            async:async,
            type:'post',
            url:'./admin.php?c=base_user&a=pagingUser',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                    total = result.data.pageInfo.total_page;
                    total_count = result.data.pageInfo.total_count;
                    $("#page-selection").bootpag({total:total,total_count:total_count});//重新计算总页数,总记录数

                    currentPage = result.data.pageInfo.current_page;
                    var userList = result.data.dataList;

                    html+='<tr><th class="th1">序号</th><th class="th2">头像</th><th class="th3">昵称</th><th class="th6">姓名</th><th class="th5">性别</th><th class="th6">手机号</th><th class="th7">地址</th><th class="th8">是否关注</th><th class="th10">关注时间</th><th class="th11">操作</th></tr>';
                    var colspan = $(html).find("th").length;
                    for(var i = 0; i < userList.length;i++){
                        var obj = userList[i];
                        var number = (pageIndex - 1)*pageSize + i + 1;//序号
                        var head_url = obj.head_img_url;
                        var account = obj.account;
                        var subscribeTimes = obj.subscribe_times;
                        var subscribe = (obj.subscribe == 1) ?"已关注":"未关注";
                        var phone = obj.phone?obj.phone:"--";
                        var nickname = obj.nick_name;
                        var name = obj.name ?obj.name:"--";
                        var sex = (obj.sex == 1)?"男":(obj.sex == 2?"女":"未知");//1男 2女 0未知
                        var subscribe_time = obj.subscribe_time || "--";
                        var addr = obj.country+"-"+obj.province+"-"+obj.city;
                        var points = obj.points;
                        var id = obj.id;
                        var admin = (obj.is_admin == 1) ? "" : "<a href='javascript:;'  class='user-admin btn btn-xs btn-primary' data-id='"+id+"'>设为管理员</a>";
                        html+='<tr>'
                        		+'<td>'+number+'</td>'
                        		+'<td>'+(head_url?'<img width="50" src="'+head_url+'">':'无头像')+'</td>'
                        		+'<td><span class="limit-text" title="'+nickname+'">'+nickname+'</span></td>'
                        		+'<td>'+name+'</td>'
                        		+'<td>'+sex+'</td>'
                        		+'<td>'+phone+'</td>'
                        		+'<td><span class="limit-text" title="'+addr+'">'+addr+'</span></td>'
                        		+'<td>'+subscribe+'</td>'
                        		//+'<td>'+subscribeTimes+'</td>'
                        		+'<td>'+subscribe_time+'</td>'
                        		+'<td>'
                                + admin
                        			+'<a href="javascript:;" class="user-detail btn btn-xs btn-primary" data-id="'+id+'">查看</a>'
                        		+'</td>'
                    		+'</tr>';
                    }
                    if(userList.length == 0){
                        html += '<tr><td colspan="'+colspan+'"><p class="text-danger">暂无数据。</p></td></tr>';
                    }
                    $(".inner-section #list-table tbody").html(html);
                    $(".user-admin").click(userAdmin);
                    $(".user-detail").click(userDetail);
                    $(".reset-pwd").click(restPwd);

                }else{
                    responseTip(result.errorCode,json.errorInfo,1500);
                }

            },
            error:errorResponse
        });
    }
    init();
});