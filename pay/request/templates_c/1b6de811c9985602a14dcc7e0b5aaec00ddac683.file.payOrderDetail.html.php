<?php /* Smarty version Smarty-3.0.8, created on 2018-09-20 08:09:52
         compiled from "D:/wamp/www//yxj/template/front/store/ma17004/page/order/payOrderDetail.html" */ ?>
<?php /*%%SmartyHeaderCode:287775ba339b046aa57-98329176%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1b6de811c9985602a14dcc7e0b5aaec00ddac683' => 
    array (
      0 => 'D:/wamp/www//yxj/template/front/store/ma17004/page/order/payOrderDetail.html',
      1 => 1537423790,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '287775ba339b046aa57-98329176',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>订单详情</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/base.css">
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/order/orderDetail.css">
</head>

<body>
    <!--主容器-->
    <div id="container" class="container-fluid">
        <!--主体内容-->
        <div id="content">
        	<div class="orderDetail">
        		<div class="space"></div>
        		<div class="number-wrap2">
        			<span>您已拍下商品，请尽快支付~</span>
        		</div>
        		<div class="space"></div>
        		
        		<div class="receiver-info">
        			<div class="checkName">
        				<?php echo $_smarty_tpl->getVariable('memberInfo')->value['name'];?>

        			</div>
        			<div class="phone">
        				证件号码 :&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('memberInfo')->value['idCard'];?>
</div>
                    <div class="phone">
                       	手机号码 :&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('memberInfo')->value['phone'];?>

                    </div>
                </div>
                
                <div class="space"></div>
                
                
                
                <div class="goods-area">
                	<?php  $_smarty_tpl->tpl_vars['goods'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('orderInfo')->value['goods_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['goods']->key => $_smarty_tpl->tpl_vars['goods']->value){
?>
                	<div class="goods-info">
	                	<a href="">
	                		<div class="goods">
	                			<div class="thumb">
	                				<img src="<?php echo $_smarty_tpl->tpl_vars['goods']->value['thumb'];?>
" alt="" />
	                			</div>
	                			<div class="good-name">
	                				<div class="name"><?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_name'];?>
</div>
	                				<div class="price">￥<?php echo $_smarty_tpl->tpl_vars['goods']->value['price'];?>
</div>
	                			</div>
	                			<div class="state">
	                				<div class="state-type">
	                					<span class="state-0">待付款</span>
					    			</div>
	                				<div class="quantity">已售<?php echo $_smarty_tpl->tpl_vars['goods']->value['sale_quantity'];?>
</div>
	                			</div>
	                		</div>
	                	</a>
                	</div>
                	<div style="height:2px;background: #F5F5F5;"></div>
                	<?php }} ?>
                </div>
                
                <div class="space"></div>
                
                <div class="order-number-area">
                	<span>订单编号:&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('orderInfo')->value['order_num'];?>
</span>
                	<span>下单时间:&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('orderInfo')->value['add_time'];?>
</span>
                </div>
            
        	</div>
        </div>
        
    	<div class="last-area">
    		<button id="goPay" data-oid="<?php echo $_smarty_tpl->getVariable('orderInfo')->value['id'];?>
">立即支付</button>
    	</div>
    <br />
    <br />
    </div>
    <!--提示框-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <!--公用js文件-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/js/order/orderDetail.js"></script>

</body>
</html>