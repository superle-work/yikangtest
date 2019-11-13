$(function(){
	var map = new BMap.Map("allmap");
	var geoc = new BMap.Geocoder(); 
	map.enableScrollWheelZoom();//启用地图滚轮放大缩小
	map.addEventListener("click", showInfo,true);//监听地图信息
	
    /**
     * 页面初始化
     */
    function init(){
    	//addressInit('province', 'city', 'area','安徽');
    	setMap(); //初始化地图
        bindEvent();
        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $("#myForm").validate(validateInfo);
        
        $("#allmap").on("click",".BMap_Marker",function(event){
        	 event.stopPropagation();
        })
    }
    /**
     * 事件绑定
     */
    function bindEvent(){
        //添加商品
        $('#save').click(function() {
        	$("#myForm").validate(validateInfo);
            addGoods();
        });
        
        //搜索
    	$('.map-search-row .search-keyword').click(function(){
    		var keyword = $('.map-search-row .keyword').val().trim();
    		$(".content .inner-section #longitude,.content .inner-section #latitude").val('');
    		setMap(keyword);
    	});
		//enter键盘事件
        $(".inner-section .map-search-row  .keyword").keydown(function(event){
            event = event ? event: window.event;
            if(event.keyCode == 13){
                var keyword = $('.map-search-row .keyword').val().trim();
                if(keyword == "") return false;
	    		$(".content .inner-section #longitude,.content .inner-section #latitude").val('');
	    		setMap(keyword);
            }
        });
        
        //添加图片事件
        $("#imgurl").change(function(){
            var filepath=$(this).val();
            if(filepath == ""){
                return false;
            }
            var extStart=filepath.lastIndexOf(".");
            var ext=filepath.substring(extStart,filepath.length).toUpperCase();
            if(ext.toLowerCase()!=".jpg" && ext.toLowerCase()!=".jpeg"
                && ext.toLowerCase()!=".png" && ext.toLowerCase()!=".gif"){
                $(this).val("");
                responseTip(1,"文件格式不正确，仅支持jpg、jpeg、gif、png格式，文件小于5M！",2000);
            }

        });

    }

    /**
     * 添加商品
     */
    function addGoods(){
        $("#myForm").ajaxSubmit($.extend(true,{},formOptions,goodsFormOptions));
    }

    /**
     * 添加商品信息得到服务器响应的回调方法
     */
    function successResponse(json,statusText){
        if(json.errorCode == 0){
            responseTip(json.errorCode,"恭喜您，操作成功！",1500,function(){window.history.go(-1);});
        }else{
            responseTip(json.errorCode,json.errorInfo,1500);
        }
    }
    
    /**
     * 提交添加商品信息的表单配置
     */
    var  goodsFormOptions={
        url:'admin.php?c=store_hospital&a=insertHospital',
        success:successResponse,
        error:errorResponse
    };
    

    //表单验证信息
    var validateInfo ={
        rules:{
            name:{//商品名称
                required:true
            },
            hospital_ratio:{
            	required:true
            },
            phone:{
                required:true,
            },
            imgurl:{
                required:true,
                accept:"jpg,jpeg,gif,png"
            },
        },
        messages:{
            name:{//商品名称
                required:"必填项"
            },
            hospital_ratio:{
                required:"必填项"
            },
            phone:{
                required:"必填项",
            },
            imgurl:{
                required:"请选择图片",
                accept:"仅支持jpg、jpeg、gif、png格式"
            },
        },
        errorPlacement:function(error,element){
            //var name = element.attr("name");
            element.parent().next().append(error);
        }
    };
    
     /**
     * 设置地图
     */
	function setMap(keyword){
		if(!keyword){
			keyword = '合肥';
		}
		map.centerAndZoom(keyword, 13);//设置地图中心和比例尺
		if(keyword){
			var local = new BMap.LocalSearch(map, {
				renderOptions:{map: map}
			});
			$(".content #allmap .BMap_Marker").remove();//删除遗留样式
			local.search(keyword);
		}
		
	}
    
    /**
     * 显示信息
     * @param {Object} e
     */
	function showInfo(e){
		//删除遗留样式
		$("#allmap img[src='http://api0.map.bdimg.com/images/marker_red_sprite.png']").parents("span").remove();
		$("#allmap .BMap_Marker").remove();
		
		geoc.getLocation(e.point, function(rs){
			var addComp = rs.addressComponents;
			var content_text = addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber;
			//$(".content .inner-section .keyword").val(content_text);
			$("#city").val(addComp.city);
			$("#longitude").val(e.point.lng);
			$("#latitude").val(e.point.lat);
			if($('#longitude').val() != '' || $('#latitude').val() != ''){
        		$('#allmap').parent().next().html('');
        	}
			//标注点数组
		    markerArr = [{title:"医院地址",content:content_text,point:e.point.lng+"|" + e.point.lat,isOpen:1,icon:{w:21,h:21,l:0,t:0,x:6,lb:5}}];
			//$(".content .inner-section .map-search-row .keyword").val(content_text);
			addMarker(e);//向地图中添加marker
		});
	}
		
    /**
     * 创建marker
     */
    function addMarker(e){
        for(var i=0;i<markerArr.length;i++){
            var json = markerArr[i];
            var p0 = json.point.split("|")[0];
            var p1 = json.point.split("|")[1];
            var point = new BMap.Point(p0,p1);
			var iconImg = createIcon(json.icon);
            var marker = new BMap.Marker(point,{icon:iconImg});
			var iw = createInfoWindow(i);
			var label = new BMap.Label(json.title,{"offset":new BMap.Size(json.icon.lb-json.icon.x+10,-20)});
			marker.setLabel(label);
            map.addOverlay(marker);
            label.setStyle({
                        color:"red",
                        cursor:"pointer",
                        fontSize:"10px",
                        border:"none",
                        left:"-15px",
                        background:"#fff",
                        borderRadius:"3px",
                        textAlign:"center",
                        top:"-17px"
            });
			
			(function(){
				var index = i;
				var _iw = createInfoWindow(i);
				var _marker = marker;
				_marker.addEventListener("click",function(){
				    this.openInfoWindow(_iw);
					event.stopPropagation();
			    });
			    _iw.addEventListener("open",function(){
				    _marker.getLabel().hide();
			    })
			    _iw.addEventListener("close",function(){
				    _marker.getLabel().show();
			    })
				label.addEventListener("click",function(){
					_marker.openInfoWindow(_iw);
					event.stopPropagation();
			    })
				if(json.isOpen){
					_marker.openInfoWindow(_iw);
				}
			})()
        }
    }
	    
	//创建InfoWindow
    function createInfoWindow(i){
        var json = markerArr[i];
        var iw = new BMap.InfoWindow("<b class='iw_poi_title' title='" + json.title + "'>" + json.title + "</b><div class='iw_poi_content'>"+json.content+"</div>");
        return iw;
    }
	
	//创建一个Icon
    function createIcon(json){
        var icon = new BMap.Icon("./themes/image/shop_icon.png", new BMap.Size(json.w,json.h),{})
        return icon;
    }
    
    
    init();
});

