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
    }
    
    /**
     * 初始化表格
     */
    function initChart(){
        //initPieChart("#pie-chart-1",0);//预计交易
        initPieChart("#pie-chart-left",3);//正在交易
        initPieChart("#pie-chart-right",5);//交易完成
        initLineChart(".line-chart-wrap #line-chart-1");//默认当前7天
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
            url:'./admin.php?c=store_data&a=getQuantityData',
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
            url:'./admin.php?c=store_data&a=getOrderPieData',
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
            name = "商品销量类别占比图";
        }else if(id == "#pie-chart-left"){
            text = "正在交易商品类别比例图";
            name = "商品销量类别占比图";
            colors = colors1;
        }else if(id == "#pie-chart-right"){
            text = "交量完成商品类别比例图";
            name = "商品销量类别占比图";
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