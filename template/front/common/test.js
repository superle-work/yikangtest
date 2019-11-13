/**
 * 测试
 */
$(function(){
	function init(){
		bindEvent();		
	}
	
	function bindEvent(){
		$("#container .btn-1").click(function(){
			showDialog("#loadingDialog");
		});
		
		$("#container .btn-2").click(function(){
			showDialog("#skipDialog");
		});
		
		$("#container .btn-3").click(function(){
			showDialog("#successDialog",'','','',3000);
		});
		
		$("#container .btn-4").click(function(){
			showDialog("#alertDialog");
		});
		$("#container .btn-5").click(function(){
			showDialog("#errorDialog");
		});
		$("#container .btn-6").click(function(){
			showDialog("#normalDialog");
		});
		$("#container .btn-7").click(function(){
			showConfirmDialog("#dangerConfirmDialog");
		});
		$("#container .btn-8").click(function(){
			showConfirmDialog("#normalConfirmDialog");
		});
		$("#container .btn-9").click(function(){
			showConfirmDialog("#successConfirmDialog","","","","",function(){alert("...");});
		});
		
	}
	
	init();
});
