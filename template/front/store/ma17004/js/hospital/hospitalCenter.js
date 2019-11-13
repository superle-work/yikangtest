$(function(){

    //点击核销
	$(".order-area .clear").click(function(){
		var id=$(this).attr("data-id");
		location.href="index.php?c=store&a=cancelOtherOrder&hid="+id;
	})
});