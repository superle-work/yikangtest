$(function(){
	var search = window.location.search;
	var did = search.split("&did=")[1];
	
    /**
     * 初始化
     */
    function init(){
        initChart();
//        cartGoodsRankList();
        bindEvent();
    }

    /**
     * 绑定事件
     */
    function bindEvent(){
    	//三级分销人数变动折线图
        $("#line-chart-area .num-line .chart-range").click(function(event){
            if($(this).hasClass("selected")){
                return false;
            }
            $(".line-chart-top .chart-range").removeClass("selected");
            $(this).addClass("selected");
            var daycount = $(this).attr("data-daycount");
            initLineChart("#line-chart-1",0,daycount);
            //event.stopPropagation();
            return false;
        });
        
        //三级分销销售额变动折线图
        $("#line-chart-area .fee-line .chart-range").click(function(event){
            if($(this).hasClass("selected")){
                return false;
            }
            $(".line-chart-top .chart-range").removeClass("selected");
            $(this).addClass("selected");
            var daycount = $(this).attr("data-daycount");
            initLineChart(".line-chart-wrap #line-chart-2",1,daycount);
            //event.stopPropagation();
            return false;
        });
        
        //三级分销人数和销售额比例饼状图
        $("#line-chart-area .pie-chart .chart-range").click(function(event){
            if($(this).hasClass("selected")){
                return false;
            }
            $(".line-chart-top .chart-range").removeClass("selected");
            $(this).addClass("selected");
            var daycount = $(this).attr("data-daycount");
            initPieChart(".line-chart-wrap #pie-chart-1",0,daycount);//人数占比
        	initPieChart(".line-chart-wrap #pie-chart-2",1,daycount);//销售额占比
            return false;
        });
    }

    /**
     * 初始化表格
     */
    function initChart(){
        initLineChart(".line-chart-wrap #line-chart-1",0,'day');//默认当前7天折线图
        initLineChart(".line-chart-wrap #line-chart-2",1,'day');
        initPieChart(".line-chart-wrap #pie-chart-1",0);//人数占比
        initPieChart(".line-chart-wrap #pie-chart-2",1);//销售额占比
    }
    
    /**
     * 所有考生的分数分布
     * id:选择器
     */
    function initPieChart(id,state,daycount){
    	var daycount = daycount ?daycount : 0;
    	$.ajax({
    		async:true,
    		type:'post',
    		url:'./admin.php?c=fen_distributor&a=dealPieChartData',
    		data:{did:did,"state":state,date:daycount},
    		dataType:'json',
    		success:function(result){
    			var textInfo = (state == 0)?"各级分销商人数":"各级分销商销售额";
    			var unit = (state == 0)?"人":"元";
    			if(result.errorCode == 0){
    				drawPieChart(id,result.data,textInfo,unit);
    			}else if(result.errorCode == 1){
    				responseTip(1,result.errorInfo);
    			}else if(result.errorCode == 2){
    				//数据为空时
                    $(id).html("<p class='text-danger'>"+textInfo+"暂无数据。</p>");
    			}
    		},
    		error:errorResponse
    	})
    }
    /**
     * 每日考试人数折线图
     * id:选择器
     * dayCount:时间段
     */
    function initLineChart(id,state,dayCount){
        $.ajax({
            async:true,
            type:'post',
            url:'./admin.php?c=fen_distributor&a=dealLineChartData',
            data:{did:did,state:state,"date":dayCount},
            dataType:'json',
            success:function(result){
                if(result.errorCode == 0){
                	var title = state == 0?'下级分销人员发展统计图（三级分销）':'分销商销售额数据统计图（三级分销）';
                	var units = state == 0?'分销商人数':'销售额';
                	var unit = state == 0?'人':'元';
                    drawLineChart(id,result.data.categories,result.data.series,title,units,unit);
                }else if(result.errorCode ==1){
                    //操作失败
                    $(id).html("<p class='text-danger'>"+result.errorInfo+"</p>");
                }else if(result.errorCode ==2){
                    //数据为空时
                    $(id).html("<p class='text-danger'>暂无数据。</p>");
                }
            },
            error:errorResponse
        });
    }
    /**
     * 绘制饼状图
     * @param id 选择器
     */
    function drawPieChart(id,pieData,textInfo,unit){
    	$(id).highcharts({
    		 chart: {
    	            plotBackgroundColor: null,
    	            plotBorderWidth: null,
    	            plotShadow: false
    	        },
    	        title: {
    	            text: textInfo+'统计饼状图'
    	        },
    	        subtitle: {
                    text:"注：只对下三级分销商数据进行统计，其余关联分销商不做统计"
                },
                credits: {
                    text: '千界科技',
                    href:'http://www.changekeji.com'
                },
    	        tooltip: {
    	    	    pointFormat: '{series.name}: <b>{point.num}'+unit+'</b>'
    	        },
    	        plotOptions: {
    	            pie: {
    	                allowPointSelect: true,
//  	                innerSize:120,
    	                cursor: 'pointer',
    	                dataLabels: {
    	                    enabled: true,
    	                    color: '#fff',
    	                    connectorColor: 'green',
    	                    format: '<b>{point.name}</b>: {point.percentage:.2f} %',
    	                    style: {
		                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
		                    }
    	                }
    	            }
    	        },
    	        series: [{
    	            type: 'pie',
    	            name: '人数',
    	            data: pieData
    	        }]
    	});
    }
    /**
     * 绘制折线表格
     */
    function drawLineChart(id,categoriesData,seriesData,title,units,unit){
    	$(id).highcharts({
            title: {
                text: title,
                x: -20 //center
            },
            subtitle: {
                text: '注：只对该分销商的下三级分销数据进行统计',
                x: -20
            },
            credits: {
                text: '千界科技',
                href:'http://www.changekeji.com'
            },
            xAxis: {
                categories: categoriesData
            },
            yAxis: {
                title: {
                    text: units
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: unit
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'right',
                borderWidth: 0
            },
            series: seriesData
        });
    }

    init();
});