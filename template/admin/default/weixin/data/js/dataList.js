$(function(){
    /**
     * 初始化
     */
    function init(){

        initChart();
        bindEvent();
    }

    /**
     * 初始化表格
     */
    function initChart(){
        initPieChart("#pie-chart-left","#pie-chart-right",7);//默认7天
        initLineChart(".line-chart-wrap #line-chart-1",99,7);//默认7天
        initLineChartTotal(".line-chart-wrap #line-chart-2",7);//默认7天
    }
    /**
     * 绑定事件
     */
    function bindEvent(){
        //最近7天查询
        $("#line-chart-area .line-chart-top .chart-range").click(function(event){

            var daycount = $(this).attr("data-daycount");
            var userSource = $(this).parent().find("#userSource").val();
            var id = $(this).parents(".line-chart-area-wrap").find(".line-chart").attr("id");
            if(id  == "line-chart-1"){
                initLineChart("#line-chart-1",userSource,daycount);
            }else if(id == "line-chart-2"){
                initLineChartTotal("#line-chart-2",daycount);
            }
            //event.stopPropagation();
            return false;
        });

        //时间跨度查询
        $("#line-chart-area .line-chart-top .search-button").click(function(event){

            var start = '';
            var end = '';
            var dayCount = "";
            var id = $(this).parents(".line-chart-area-wrap").find(".line-chart").attr("id");
            if(id  == "line-chart-1"){
                start = $(this).parent().find("#startTime").val();
                end = $(this).parent().find("#endTime").val();
                if(start == "" || end == ""){
                    dayCount = 7;
                }
                var userSource = $(this).parent().find("#userSource").val();
                initLineChart("#line-chart-1",userSource,dayCount,start,end);
            }else if(id == "line-chart-2"){
                start = $(this).parent().find("#startDate").val();
                end = $(this).parent().find("#endDate").val();
                if(start == "" || end == ""){
                    dayCount = 7;
                }
                initLineChartTotal("#line-chart-2",dayCount,start,end);
            }
            //event.stopPropagation();
            return false;
        });
    }
    
    //初始化折线图
    /**
     * id 绘图选择器id
     * userSource 用户来源类型
     * start 起始日期
     * end 结束日期
     */
    function initLineChart(id,userSource,dayCount,start,end){
        //折线图请求
        $.ajax({
            async:true,
            type:'post',
            url:'./admin.php?c=weixin_data&a=getUserSummary',
            data:{"userSource":userSource,"dayCount":dayCount,"from":start,"to":end},
            dataType:'json',
            success:function(result){
                if(result.errorCode == 0){
                    drawChart(id,result.data.xData,result.data.yData);
                }else if(result.errorCode ==1){
                    responseTip(result.errorCode,result.errorInfo,1500);
                }else if(result.errorCode ==2){
                    //数据为空时
                    $(id).html("<p class='text-danger'>抱歉，查询结果为空</p>");
                }
            },
            error:errorResponse
        });
    }
    //初始化折线图
    /**
     * id 绘图选择器id
     * dayCount 时间天数 不超过7
     * start 起始日期
     * end 结束日期
     */
    function initLineChartTotal(id,dayCount,start,end){
        //折线图请求
        $.ajax({
            async:true,
            type:'post',
            url:'./admin.php?c=weixin_data&a=getUserCumulate',
            data:{"dayCount":dayCount,"from":start,"to":end},
            dataType:'json',
            success:function(result){
                if(result.errorCode == 0){
                	drawChartTotal(id,result.data.xData,result.data.yData);
                }else if(result.errorCode ==1){
                    //操作失败
                    responseTip(result.errorCode,result.errorInfo,1500);
                }else if(result.errorCode ==2){
                    //数据为空时
                    $(id).html("<p class='text-danger'>抱歉，查询结果为空</p>");
                }
            },
            error:errorResponse
        });
    }
    //初始化饼状图
    function initPieChart(idLeft,idRight,dayCount,start,end){
        //饼图请求
        $.ajax({
            async:true,
            type:'post',
            url:'./admin.php?c=weixin_data&a=getUserSourceRate',
            data:{"dayCount":dayCount,"start":start,"end":end},
            dataType:'json',
            success:function(result){
                if(result.errorCode == 0){
                		drawPie(idLeft,result.data.newDataList);
                		drawPie(idRight,result.data.cancelDataList);
                }else if(result.errorCode == 1){//为空是饼图不加载}else{
                    responseTip(result.errorCode,result.errorInfo,1500);
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
                text: '粉丝量增减数据统计',
                x: -20 //center
            },
            subtitle: {
                text: ' 粉丝量增减趋势分析',
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
                    text: '数量'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '个'
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
    
    /**
     * 绘制累计用户统计表格
     */
    function drawChartTotal(id,xData,yData){
    	$(id).highcharts({
            title: {
                text: '累计粉丝量数据统计',
                x: -20 //center
            },
            subtitle: {
                text: ' 累计粉丝量增减趋势分析',
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
                    text: '数量'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '个'
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
            name = "商品销量类别占比图";
        }else if(id == "#pie-chart-left"){
            text = "新增粉丝来源比例图";
            name = "新增粉丝来源比例图";
            colors = colors1;
        }else if(id == "#pie-chart-right"){
            text = "取消关注粉丝来源比例图";
            name = "取消关注粉丝来源比例图";
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
    
    init();
});