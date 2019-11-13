$(function(){
    /**
     * 初始化
     */
    function init(){

        initChart();
        bindEvent();
    }

    /**
     * 绑定事件
     */
    function bindEvent(){
        $("#line-chart-area .line-chart-top .chart-range").click(function(event){
            if($(this).hasClass("selected")){
                return false;
            }
            $(".line-chart-top .chart-range").removeClass("selected");
            $(this).addClass("selected");
            var daycount = $(this).attr("data-daycount");
            initLineChart("#line-chart-1",daycount);
            //event.stopPropagation();
            return false;
        });
        $(".column-chart-area .line-chart-top.fee-column .chart-range").click(function(event){
            if($(this).hasClass("selected")){
                return false;
            }
            $(".line-chart-top .chart-range").removeClass("selected");
            $(this).addClass("selected");
            var daycount = $(this).attr("data-daycount");
            initBarChart(".line-chart-wrap #column-chart-1",1,daycount);
            //event.stopPropagation();
            return false;
        });
        $(".column-chart-area .line-chart-top.num-column .chart-range").click(function(event){
            if($(this).hasClass("selected")){
                return false;
            }
            $(".line-chart-top .chart-range").removeClass("selected");
            $(this).addClass("selected");
            var daycount = $(this).attr("data-daycount");
            initBarChart(".line-chart-wrap #column-chart-0",0,daycount);
            return false;
        });
    }
    
    /**
     * 初始化表格
     */
    function initChart(){
        //initPieChart("#pie-chart-1",0);//预计交易
//      initPieChart("#pie-chart-left",0);//商城销售占比
//        initPieChart("#pie-chart-right",1);//交易完成
        initLineChart(".line-chart-wrap #line-chart-1");//默认当前7天
        initBarChart(".line-chart-wrap #column-chart-0",0,'day');//默认30天
        initBarChart(".line-chart-wrap #column-chart-1",1,'day');//默认30天
    }

    //初始化折线图
    /**
     * id 绘图选择器id
     * dayCount 自此至前的时间段
     * start 起始日期
     * end 结束日期
     */
    function initLineChart(id,dayCount,start,end){
        //折线图请求
        $.ajax({
            async:true,
            type:'post',
            url:'./admin.php?c=fen_data&a=getQuantityData',
            data:{"dayCount":dayCount,"start":start,"end":end},
            dataType:'json',
            success:function(json){
                if(json.errorCode == 0){
                    drawChart(id,json.data.xData,json.data.yData);
                }else if(json.errorCode ==1){
                    //操作失败
                    responseTip(json.errorCode,json.errorInfo,1500);
                }else if(json.errorCode ==2){
                    //数据为空时
                    $(id).html("<p class='text-danger'>暂无数据。</p>");
                }
            },
            error:errorResponse
        });
    }
    
    //初始化饼状图
    function initPieChart(id,state){
        //饼图请求
        $.ajax({
            async:true,
            type:'post',
            url:'./admin.php?c=fen_data&a=getOrderPieData',
            data:{"state":state},
            dataType:'json',
            success:function(result){
                if(result.errorCode == 0){
                    drawPie(id,result.data);
                }else if(result.errorCode == 1){//为空是饼图不加载}else{
                    $("#myModal .modal-body").html("<p class='text-danger'>"+result.errorInfo+"</p>");
                    $("#myModal").modal('show');
                    //定时器，1.5秒后模态框自动关闭
                    setTimeout(function(){
                        $("#myModal").modal('hide');
                    },1500);
                }else if(result.errorCode ==2){
                    //数据为空时
                    //$(id).html("<p class='text-danger'>抱歉，查询结果为空</p>");
                }
            },
            error:errorResponse
        });
    }
    
    /**
     * 绘制表格
     */
    function drawChart(id,xData,yData){
    	$(id).highcharts({
            title: {
                text: '销售额统计',
                x: -20 //center
            },
            subtitle: {
                text: ' 对销售额趋势分析',
                x: -20
            },
            credits: {
                text: '千界科技',
                href:'http://www.changekeji.com'
            },
            xAxis: {
                categories: xData
            },
            yAxis: {
                title: {
                    text: '金额'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '元'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'right',
                borderWidth: 0
            },
            series: yData
        });
    }

    var colors1 = ['#DDDF00', '#24CBE5', '#64E572','#058DC7', '#50B432', '#ED561B', '#FF9655', '#FFF263', '#6AF9C4'];
    var colors2 = ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4']
    /**
     * 绘制表格
     */
    function drawPie(id,data){
        var text = "";
        var name = "";
        var colors = [];
        if(id == "#pie-chart-center"){
            text = "预计成交量商品类别比例图";
            name = "商城销售占比";
        }else if(id == "#pie-chart-left"){
            text = "正在交易商品类别比例图";
            name = "商城销售占比";
            colors = colors1;
        }else if(id == "#pie-chart-right"){
            text = "交量完成商品类别比例图";
            name = "商城销售占比";
            colors = colors2;
        }
    	$(id).highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: text
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>'
            },
            credits: {
                text: '千界科技',
                href:'http://www.changekeji.com'
            },
            colors: colors,
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        format: '<b>{point.name}</b>: {point.percentage:.2f} %'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: name,
                data: data
            }]
        });
    }
    
     /**
     * 优秀分销商
     * id:选择器
     * state:类型 
     * dayCount:时间类型
     */
    function initBarChart(id,state,dayCount){
    	$.ajax({
    		async:true,
    		type:'post',
    		url:'./admin.php?c=fen_data&a=getDealBarData',
    		data:{state:state,date:dayCount},
    		dataType:'json',
    		success:function(result){
    			if(result.errorCode == 0){
    				var text_info = state == 0 ?'发展人员':'销售额';
    				var unit = state == 0 ? '人':'元';
    				drawBarChart(id,result.data.dateList,result.data.dataList,text_info,unit);
    			}else if(result.errorCode == 1){
                    $(id).html("<p class='text-danger'>暂无数据</p>");
    			}
    		},
    		error:errorResponse
    	})
    }
    
    /**
     * 绘制柱状图
     */
    function drawBarChart(id,Xchart,Ychart,text,unit){
    	$(id).highcharts({
    		chart: {
                type: 'column'
            },
            title: {
                text: '优秀分销商'+text+'统计柱状图'
            },
            subtitle: {
                text: '注：针对分销商直接'+text+'(下一级)的数据排名（前三名）'
            },
            credits: {
                text: '千界科技',
                href:'http://www.changekeji.com'
            },
            xAxis: {
                categories: Xchart
            },
            yAxis: {
                min: 0,
                title: {
                    text: text+'('+unit+')'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{point.title}: </td>' +
                    '<td style="padding:0"><b>{point.y'+(unit == '人'?'':':.2f')+'} '+unit+'</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: Ychart
    	});
    }
    init();
});