$(function(){
    /**
     * 初始化
     */
    function init(){
        initChart();
        cartGoodsRankList();
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
        initLineChart(".line-chart-wrap #line-chart-1");//默认当前7天

    }

    function initLineChart(id,dayCount,start,end){
        $.ajax({
            async:true,
            type:'post',
            url:'./admin.php?c=store_data&a=getCartChart',
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
                text: '购物车商品分类数量和商品数量统计',
                x: -20 //center
            },
            subtitle: {
                text: '分类数量：加入购物车商品分类数;商品数量：加入购物车商品总数量',
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

    //购物车商品排行榜
    function cartGoodsRankList(){
        //$(".cart-rank-list")
        $.ajax({
            async:true,
            type:'post',
            url:'./admin.php?c=store_data&a=getCartRank',
            data:{"preview":10},//默认前10名排行
            dataType:'json',
            success:function(result){

                    var html ="";
                    var goodsTimesList = result.goodsTimesList;//购物车商品重复次数排行
                    html = "<tr><th>排行</th><th class='th1'>缩略图</th><th class='th2'>商品名称</th><th class='th3'>价格</th><th class='th4'>加入购物车次数</th></tr>";
                    if(goodsTimesList.length > 0){
                        for(var i = 0; i <goodsTimesList.length; i++){
                            var obj = goodsTimesList[i];
                            html +="<tr><td>"+(i+1)+"</td><td><img style='width: 30px;' src='"+obj.thumb+"'></td><td>"+obj.goods_name+"</td><td>"+obj.price+"</td><td>"+obj.total_count+"</td></tr>";
                        }
                    }else{
                        html +="<tr><td colspan='5'><p class='text-danger'>查询结果为空。</p></td></tr>";
                    }
                    $("#goodsTimesList").html(html);

                    var goodsCountList = result.goodsCountList;//购物车商品数量排行
                    html = "<tr><th>排行</th><th class='th1'>缩略图</th><th class='th2'>商品名称</th><th class='th3'>价格</th><th class='th4'>加入购物车数量</th></tr>";
                    if(goodsCountList.length > 0){
                        for(var i = 0; i <goodsCountList.length; i++){
                            var obj = goodsCountList[i];
                            html +="<tr><td>"+(i+1)+"</td><td><img style='width: 30px;' src='"+obj.thumb+"'></td><td>"+obj.goods_name+"</td><td>"+obj.price+"</td><td>"+obj.total_count+"</td></tr>";
                        }
                    }else{
                        html +="<tr><td colspan='5'><p class='text-danger'>暂无数据。</p></td></tr>";
                    }
                    $("#goodsCountList").html(html);

            },
            error:errorResponse
        });
    }
    init();
});