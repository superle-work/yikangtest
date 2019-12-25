$(function(){
    /**
     * 分页初始条件
     */
    var total_count = 1;//分页总记录数
    var total = 1;//分页总页面数
    var currentPage = 1;//当前页
    var pageSize = pageOption.pageSize;//每页显示的记录数
    var idList = [];//批量选择id所存的数组
    printerid = '';
    function chooseid(){
        printerid=$(this).attr("oid");
    }
    /**
     * 页面初始化
     */
    function init(){
        myPagination();
        bindEvent();
    }

    /**
     * 事件绑定
     */
    function bindEvent(){
        //根据条件查询商品信息
        $('.inner-section #search_btn').click(function() {
            render(true,1,pageSize);
            return false;
        });
        //enter键盘事件
        $(".inner-section .search-param-form input").keydown(function(event){
            event = event ? event: window.event;
            if(event.keyCode == 13){
                render(true,1,pageSize);
                return false;
            }
        });
        
        $('.row #import_btn').click(function() {
            var selectInfo = getSelectInfo();
            var data = $.param(selectInfo);
            window.location.href = "./admin.php?c=store_order&a=importExcel&"+data;
        });
          
      //批量删除
        $(".content .right-section .delete-batch").click(function(){
        	var ids = idList.join(',');
            if(ids == ""){
        		$("#myModal .modal-body").html("<p class='text-danger'>您尚未选择要删除的选项，请先选择！</p>");
                $("#myModal").modal('show');
                //定时器，1.5秒后模态框自动关闭
                setTimeout(function(){
                    $("#myModal").modal('hide');
                },1500);
        	}else{
	            myConfirmModal("确定要批量删除订单吗？",function(){
		            $.ajax({
		                url:"./admin.php?c=store_order&a=batchDeleteOrder",
		                type:"post",
		                data:{"ids":ids},
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
		                    	if(currentPage != 1 && (total_count - idList.length) % pageSize == 0){
	                                currentPage = currentPage - 1;
	                            }
	                            idList = [];//初始化idList的值
	                            render(true,currentPage,pageSize);
		                    }else{
		                        responseTip(1,json.errorInfo);
		                    }
		                },
		                error:errorResponse
		            });
	            });
        	}
        });
    }

    /**
     * 获取模糊参数
     */
    function getSelectInfo(){

        var selectInfo = {
            order_num : $.trim($("#ordernum").val()),//订单编号
            phone : $.trim($("#phone").val()),//手机号
            state : $('#state').val(),//订单状态
            userstate : $('#userstate').val(),//用戶类型
            clinic_name : $('#clinic_name').val(),//诊所名称
            hospital_name : $.trim($("#hospital_name").val()),
            from : $('#startTime').val(),
            to : $('#endTime').val(),
        };
        return selectInfo;
    }
    /**
     * 分页显示方法
     */
    function myPagination(){
        render(true,1,pageSize);
        //调用公共分页方法
        pagination("#page-selection",{pageSize:pageSize,total:total},render);

    }
    /**
     * 分页动态渲染数据
     * @param async ajax请求是否异步
     * @param pageIndex 当前显示页
     * @param pageSize 每页显示记录数
     */
    function render(async,pageIndex,pageSize){
        var selectInfo = getSelectInfo();console.log(selectInfo);
        selectInfo.pageIndex = pageIndex;
        selectInfo.pageSize = pageSize;
        $.ajax({
            async:async,
            type:'post',
            url:'./admin.php?c=store_order&a=pagingOrder',
            data:selectInfo,//从1开始计数
            dataType:'json',
            success:function(result){
                var html ='';
                if(result.errorCode == 0){
                    total = result.pageInfo.total_page;
                    total_count = result.pageInfo.total_count;
                    $("#page-selection").bootpag({total:total,total_count:total_count});//重新计算总页数,总记录数

                    currentPage = result.pageInfo.current_page;
                    var myList = result.orderList;

                    html+='<tr><th class="th1"><input type="checkbox" class="select-all my-icheckbox"></th><th class="th1">序号</th><th class="th2">昵称</th><th class="th3">订单号</th><th class="th4">订单总价</th><th class="th11">手机号</th><th class="th7">订单状态</th><th class="th7">推荐诊所</th><th class="th7">采样诊所</th><th class="th7">检测医院</th><th class="th11">医院核销人员</th><th class="th8">创建日期</th><th class="th3">操作</th></tr>';
                    var colspan = $(html).find("th").length;
                    for(var i = 0; i < myList.length;i++){
                        var obj = myList[i];
                        var num = (pageIndex-1)*pageSize + i+1;
                        var ordernum = obj.order_num;
                        var phone = obj.phone?obj.phone:'---';
                        var nick_name = obj.nick_name;  //下单用户昵称
                        var account = obj.account;     //下单用户openid
                        var totalprice = obj.total_price;
                        var state = obj.state;
                        var userstate = obj.userstate;
                        var createtime = obj.add_time;
                        
                        var clinic_name = obj.clinic_name?obj.clinic_name:'---';  //诊所名称
                        var tj_name = obj.tj_name?obj.tj_name:'---';  //推荐诊所名称
                        var cid = obj.clinic_id;                       //诊所id
                        
                        var hospital_name = obj.hospital_name?obj.hospital_name:'---'; //医院名称
                        var hid = obj.hospital_id;                     //医院id
                        var hospital_worker=obj.hospital_worker;  //医院核销人员id
                        var hospital_worker_name=obj.hospital_worker_name?obj.hospital_worker_name:'---';  //医院核销人员昵称
                        
                        var logistics_worker_name = obj.logistics_worker_name?obj.logistics_worker_name:'---'; //物流工作人员名称
                        var lid = obj.logistics_worker;   //物流工作人员id

                        var oid = obj.id;
                        
                        //订单状态
                        var state_str="";
                        var operate="";
                        if(state==0){
                        	state_str='<span class="state-0">待付款</span>';
                        }
                        else if(state==1){
                        	state_str='<span class="state-1">待采样</span>';
                        }
                        else if(state==2){
                        	state_str='<span class="state-2">待送检</span>';
                        }
                        else if(state==3){
                        	state_str='<span class="state-3">送检中</span>';
                        }
                        else if(state==4){
                        	state_str='<span class="state-4">检测中</span>';
                        }
                        else if(state==5){
                        	state_str='<span class="state-5">已完成</span>'
                            operate='<a href="admin.php?c=store_order&a=showReport&oid='+oid+'" class="btn btn-info btn-xs showReport">查看报告</a>';
                        }
                        
                        //var operation = orderOperatoin(state,oid);
						
                        var checked = (idList.indexOf(oid) >= 0) ? "checked":"";//判断当前记录先前有没有被选中


                        html+='<tr>'
                        	+'<td><input type="checkbox" class="select-single my-icheckbox" value="'+oid+'" '+checked+'></td>'
                            +'<td class="th1">'+num+'</td>'
                            +'<td class="th2"><a href="javascript:;" title="查看用户详情" class="user-detail" account="'+account+'">'+nick_name+'</a></td>'
                            +'<td class="th3"><a href="javascript:;" title="查看订单详情" class="order-detail" oid="'+oid+'">'+ordernum+'</a></td>'
                            +'<td class="th4">'+totalprice+'</td>'
                            +'<td class="th11">'+phone+'</td>'
                            +'<td>'+state_str+'</td>'
                            +'<td>'+tj_name+'</td>'
                            +'<td class="th11"><a href="javascript:;" class="clinic_detail" title="查看诊所详情" cid="'+cid+'">'+clinic_name+'</a></td>'
                            +'<td class="th11"><a href="javascript:;" class="hospital_detail" title="查看医院详情" hid="'+hid+'">'+hospital_name+'</a></td>'
                            //+'<td class="th11"><a href="javascript:;" class="logistics_detail" title="查看物流人员详情" lid="'+lid+'">'+logistics_worker_name+'</a></td>'
                            +'<td class="th11"><a href="javascript:;" class="hospital_worker_detail" title="查看医院人员详情" hwid="'+hospital_worker+'">'+hospital_worker_name+'</a></td>'
                            +'<td>'+createtime+'</td>'
                            +'<td>'+operate+'<a oid="'+oid+'" class="btn btn-default btn-xs print-order chooseid" data-toggle="modal" href="./admin.php?c=store_order&a=showBindUser&id='+oid+'" data-target="#bindLabelDialog">打印</a>'+'<a href="javascript:;" oid="'+oid+'" class="btn btn-default btn-xs delete-order">删除</a></td>'
                            +'</tr>';
                    }

                    if(myList.length == 0){
                        html += '<tr><td colspan="'+colspan+'"><p class="text-danger">暂无数据。</p></td></tr>';
                        $("#list-table tbody").html(html);
                    }else{
                        $("#list-table tbody").html(html);
                        myCheck();
                        batchSelect(idList,".inner-section #list-table .select-all",".inner-section #list-table .select-single");

                        //订单详情
                        $(".order-detail").click(function(){
                            var oid = $(this).attr("oid");
                            window.location.href = "admin.php?c=store_order&a=orderDetailList&oid="+oid;
                        });
                        
                        //用户详情
                        $(".user-detail").click(function(){
                            var account = $(this).attr("account");
                            window.location.href = "admin.php?c=base_user&a=orderUserDetail&account="+account;
                        });
                        
                        //诊所详情
                        $(".clinic_detail").click(function(){
                            var cid = $(this).attr("cid");
                            if(cid !='' && cid !='null' && cid !=null){
                            	window.location.href = "admin.php?c=store_clinic&a=clinicDetail&id="+cid;
                            }
                        });
                        
                        //医院详情
                        $(".hospital_detail").click(function(){
                            var hid = $(this).attr("hid");
                            if(hid !='' && hid !=null && hid !='null'){
                            	window.location.href = "admin.php?c=store_hospital&a=hospitalDetail&id="+hid;
                            }
                        });
                        
                        //医院核销人员详情
                        $(".hospital_worker_detail").click(function(){
                            var hwid = $(this).attr("hwid");
                            if(hwid !='' && hwid !=null && hwid !='null'){
                            	window.location.href = "admin.php?c=base_user&a=userDetail&id="+hwid;
                            }
                        });
                        
                        //物流人员详情
                        $(".logistics_detail").click(function(){
                            var lid = $(this).attr("lid");
                            if(lid !='' && lid !=null && lid !='null'){
                            	window.location.href = "admin.php?c=base_user&a=userDetail&id="+lid;
                            }
                        });
                        
                        /*
                        //关闭订单
                        $(".close-order").click(function(){
                            var oid = $(this).attr('oid');
                            myConfirmModal("确认关闭订单吗？",function(){
                            	setOrderState(oid,4);	
                            });
                        }); 
                        
                        //修改订单
                        $(".modify-order").click(function(){
                        	var oid = $(this).attr("oid");
                        	window.location.href = "./admin.php?c=store_order&a=orderModify&oid="+oid;
                        });
                        
                        //查看物流信息
                        $(".check-express").click(function(){
                        	var code = $(this).attr('data-code');
                        	var number =  $(this).attr('data-number');
//                        	var httpHost = $('#myBottomNav').attr('data-http');
//                        	var callBackUrl = "http://192.168.1.124/cws/admin.php?c=store_order_a_orderList_mid_29";
                        	window.location.href = "https://m.kuaidi100.com/index_all.html?type="+code+"&postid="+number;
                        });
                        */
                       
                        //删除订单
                        $(".delete-order").click(deleteOrder);
                        $(".chooseid").click(chooseid);
                        
                    }

                }else{
                    responseTip(result.errorCode,result.errorInfo,1500);
                }
            },
            error:errorResponse
        });
    }

    // /**
    // *打印订单
    // *
    // **/
    // function printOrder(){
    //     alert("111111111");
    // }

    /**
     * 删除订单
     * @param oid
     * @param state
     */
    function deleteOrder(){
        var oid = $(this).attr('oid');
        myConfirmModal("确定删除当前订单吗？",function(){
            $.ajax(
                {
                    type:"post",
                    url:"./admin.php?c=store_order&a=deleteOrder",
                    data:{"id":oid},
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
                }
            );
        });
    }
    
    /**
     * 设置订单状态
     */
    function setOrderState(oid,state){
    	$.ajax(
            {
                type:"post",
                url:"./admin.php?c=store_order&a=setOrderState",
                data:{'oid':oid,'state':state},
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
                        render(true,currentPage,pageSize);
                    }else{
                    	responseTip(json.errorCode,json.errorInfo,1500);
                    }
                },
                error:errorResponse
            }
        );
        
    }

    /**
     * 设置订单的中文状态
     * //0待付款  1待发货 2已发货 3已收货 4交易关闭 5交易完成
     */
    function setState(state,express_code,express_number){
        var html = "";
        switch (state)
        {
            case '0':
                html ="<span class='text-info'>待付款</span>";
                break;
            case '1':
                html ="<span style='color:#EE0000'>待发货</span>";
                break;
            case '2':
//                html ="<span class='text-primary check-express' data-code='"+express_code+"' data-number = '"+express_number+"'>已发货(物流)</span>";
            	html ="<a href='javascript:;'  data-code='"+express_code+"' data-number = '"+express_number+"' class='check-express' title='查看物流'><span class='text-primary'>已发货</span></a>";
            	break;
            case '3':
                html ="<a href='javascript:;'  data-code='"+express_code+"' data-number = '"+express_number+"' class='check-express' title='查看物流'><span class='text-primary'>已收货</span></a>";
                break;
            case '4':
                html ="<span style='color:gray'>交易关闭</span>";
                break;
            case '5':
                html ="<a href='javascript:;'  data-code='"+express_code+"' data-number = '"+express_number+"' class='check-express' title='查看物流'><span class='text-success'>交易完成</span></a>";
                break;
            case '6':
            	html ="<span class='text-danger'>申请退款</span>";
            	break;
            case '7':
            	html ="<span class='text-info'>退款成功</span>";
            	break;
            //默认状态是已删除  ——安全性要求
            default :html="--";
        }
        return html;
    }

    /**
     * 获取订单操作
     * @param state 订单状态
     * @param oid 订单id
     */
    function orderOperatoin(state,oid){
        var html = "";
        switch (state)
        {
            case '0'://待付款
            	html +="<a  href='javascript:;' oid ='"+ oid+"' class='btn btn-primary btn-xs modify-order'title='订单修改'>订单修改</a>";
                html +="<a  href='javascript:;' oid ='"+ oid+"' class='btn btn-danger btn-xs close-order'title='关闭订单'>关闭</a>";
                break;
            case '1'://待发货
            	html +="<a  href='javascript:;' oid ='"+ oid+"' class='btn btn-primary btn-xs modify-order'title='设置'>设置</a>";
            	html +="<a  href='javascript:;'  oid ='"+oid+"'  class='btn btn-primary btn-xs send-goods'>发货</a>";
                html +="<a  href='javascript:;'  oid ='"+oid+"'  class='btn btn-danger btn-xs close-order title='关闭订单'>关闭</a>";
                break;
            case '2'://已发货
                html+="<a  href='javascript:;'  oid ='"+oid+"' class='btn btn-success btn-xs accept-goods' title='确认收货'>确认</a>";
//                html+="<a href='javascript:;'  data-code='"+express_code+"' data-number = '"+express_number+"' class='check-express btn btn-xs btn-primary' title='查看物流'>物流</a>";
                html+="<a  href='javascript:;'  oid ='"+oid+"' class='btn btn-danger btn-xs close-order' title='关闭订单'>关闭</a>";
                break;
            case '3'://已收货
                html+="<a  href='javascript:;'  oid ='"+oid+ "' class='btn btn-success btn-xs complete-order' title='交易完成'>完成</a>";
                html+="<a  href='javascript:;'  oid ='"+oid+"'  class='btn btn-danger btn-xs close-order' title='关闭订单'>关闭</a>";
                break;
            case '4'://交易关闭
                html+="<a  href='javascript:;'  oid ='"+oid+"' class='btn btn-default btn-xs delete-order'>删除</a>";
                break;
            case '5'://交易成功
                html+="<a  href='javascript:;'  oid ='"+oid+"' class='btn btn-default btn-xs delete-order'>删除</a>";
                break;
            case '6'://申请退款
            	html+="<a  href='javascript:;'  oid ='"+oid+"'  class='btn btn-danger btn-xs refund-order' title='同意退款'>退款</a>";
            	html +="<a  href='javascript:;' oid ='"+ oid+"' class='btn btn-primary btn-xs modify-order'title='设置'>设置</a>";
            	html +="<a  href='javascript:;'  oid ='"+oid+"'  class='btn btn-primary btn-xs send-goods'>发货</a>";
            	break;
            case '7'://退款成功
            	html+="<a  href='javascript:;'  oid ='"+oid+"' class='btn btn-default btn-xs delete-order'>删除</a>";
            	break;
            //默认状态是已删除  ——安全性要求
            default:html+="--"; //已删除

        }
        return html ;
    }
    init();
});