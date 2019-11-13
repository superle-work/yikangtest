<?php /* Smarty version Smarty-3.0.8, created on 2019-09-28 18:06:31
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/user/orderList.html" */ ?>
<?php /*%%SmartyHeaderCode:6234004905d8f30a7d9abe9-74707883%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3922f32090426a645fa57f09407907f6edd1f802' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/user/orderList.html',
      1 => 1569664597,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6234004905d8f30a7d9abe9-74707883',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>我的订单</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/user/orderList.css">
    <link rel="stylesheet"	href="<?php echo @JS_PATH;?>
/public/swiper/css/swiper.min.css">
</head>

<body>
    <!--主容器-->
    <div id="container" class="container-fluid">
        <!--主体内容-->
        <div id="content">
        	<!--订单状态-->
            <input type="hidden" id="state" value="<?php echo $_smarty_tpl->getVariable('state')->value;?>
">
            <!--支付方式-->
            <input type="hidden" id="payType" value="<?php echo $_smarty_tpl->getVariable('configInfo')->value['pay_type'];?>
">
            <!--是否开启退款-->
            <input type="hidden" id="is_refund" value="<?php echo $_smarty_tpl->getVariable('configInfo')->value['is_refund'];?>
">
            
            <div id="myBottomNav" data-http ="<?php echo $_smarty_tpl->getVariable('HttpHost')->value;?>
">
	            <div class="swiper-container swiper-container-horizontal swiper-container-free-mode">
					<div class="swiper-wrapper">
					    <div class="swiper-slide loan-list all <?php if ($_smarty_tpl->getVariable('state')->value==''){?>cur<?php }?>" data-state="">全部</div>				        					
					    <div class="swiper-slide loan-list <?php if ($_smarty_tpl->getVariable('state')->value=='0'){?>cur<?php }?>" data-state="0">待付款</div>	
					    <div class="swiper-slide loan-list <?php if ($_smarty_tpl->getVariable('state')->value=='1'){?>cur<?php }?>" data-state="1">待采样</div>				        
					    <div class="swiper-slide loan-list <?php if ($_smarty_tpl->getVariable('state')->value=='2'){?>cur<?php }?>" data-state="2">待送检</div>				        
					    <div class="swiper-slide loan-list <?php if ($_smarty_tpl->getVariable('state')->value=='3'){?>cur<?php }?>" data-state="3">送检中</div>				        
					    <div class="swiper-slide loan-list <?php if ($_smarty_tpl->getVariable('state')->value=='4'){?>cur<?php }?>" data-state="4">检测中</div>				        
					    <div class="swiper-slide loan-list <?php if ($_smarty_tpl->getVariable('state')->value=='5'){?>cur<?php }?>" data-state="5">已完成</div>
					</div>
				</div>	            
	        </div>
	        <div class="space2"></div>
            
            <div class="order-list">
            	
            </div>	
            <div class="get-more hidden" data-start="1" data-num="8" data-id="<?php echo $_smarty_tpl->getVariable('estate_id')->value;?>
"></div>	
        </div>
    </div>
    <!--提示框-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <!--公用js文件-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/jquery.bootpag/jquery.bootpag.min.js"></script>
    <script src="<?php echo @JS_PATH;?>
/public/swiper/js/swiper.jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/js/user/orderList.js"></script>

</body>
</html>