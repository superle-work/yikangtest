$(function(){
    /**
     * 初始化
     */
    function init(){
        bindEvent();
        initChart();
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
        initLineChart(".line-chart-wrap #line-chart-1");//默认当前7天

    }

    /**
     * id 绘图选择器id
     * dayCount 自此至前的时间段
     * start 起始日期
     * end 结束日期
     */
    function initLineChart(id,dayCount,start,end){
        $.ajax({
            async:true,
            type:'post',
            url:'./admin.php?c=store_data&a=getOrderData',
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
    /**
     * 绘制表格
     */
    function drawChart(id,xData,yData){
    	$(id).highcharts({
            title: {
                text: '订单数量统计',
                x: -20 //center
            },
            subtitle: {
                text: ' 对订单量趋势分析',
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
    
    init();
});