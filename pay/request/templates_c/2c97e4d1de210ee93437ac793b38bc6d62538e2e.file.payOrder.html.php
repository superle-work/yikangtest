<?php /* Smarty version Smarty-3.0.8, created on 2018-11-10 11:45:09
         compiled from "/data/wwwroot/yxj/template/front/store/ma17004/page/order/payOrder.html" */ ?>
<?php /*%%SmartyHeaderCode:12054053685be654458da1c8-00307179%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2c97e4d1de210ee93437ac793b38bc6d62538e2e' => 
    array (
      0 => '/data/wwwroot/yxj/template/front/store/ma17004/page/order/payOrder.html',
      1 => 1541821277,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12054053685be654458da1c8-00307179',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!--提交订单-->
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>确认订单</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/order/payOrder.css">
</head>

<body>
<!--主容器-->
<div id="container" class="container-fluid">
    <!--主体内容-->
    <div id="content">
        <div class="member-list">
        	<?php if ($_smarty_tpl->getVariable('memberInfo')->value){?>
    		<div class="info" data-id="<?php echo $_smarty_tpl->getVariable('memberInfo')->value['id'];?>
">
    			<p class="name"><?php echo $_smarty_tpl->getVariable('memberInfo')->value['name'];?>
</p>
    			<p>出生年月日: <?php echo $_smarty_tpl->getVariable('memberInfo')->value['idCard'];?>
</p>
    			<p>手机号码: <?php echo $_smarty_tpl->getVariable('memberInfo')->value['phone'];?>
</p>
    		</div>
	    	<div class="action">
	    		<i class="icon iconfont reSel">&#xe618;</i>
	    	</div>	
	    	<?php }else{ ?>
	    	<div class="addMem-area">
	    		<button class="addCheckMember"><i class="icon iconfont add-icon">&#xe62f;</i>添加体检人员</button>
	    	</div>
	    	<?php }?>
    	</div>
    	<div class="space"></div>
        
        <input type="hidden" id="cgids" value="<?php echo $_smarty_tpl->getVariable('cgids')->value;?>
">
        <input type="hidden" id="gids" value="<?php echo $_smarty_tpl->getVariable('gids')->value;?>
">
        <input type="hidden" id="counts" value="<?php echo $_smarty_tpl->getVariable('counts')->value;?>
">
       	
        <!--待付款订单的商品列表-->
        <?php  $_smarty_tpl->tpl_vars['goods'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('goodsList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['goods']->key => $_smarty_tpl->tpl_vars['goods']->value){
?>
        <div class="order-goods-list">
            <div class="goods-list">
            	<a href="http://yikang.chuyuanshengtai.com/index.php?c=store&a=goodsDetail&id=<?php echo $_smarty_tpl->tpl_vars['goods']->value['id'];?>
">
					<div class="goods-name">
						<div class="name"><?php echo $_smarty_tpl->tpl_vars['goods']->value['name'];?>
</div>
						<div class="price">￥<?php echo $_smarty_tpl->tpl_vars['goods']->value['price'];?>
</div>
					</div>
					<div class="count">x<?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_count'];?>
&nbsp;</div>
            	</a>
            </div>
        </div>
        <div class="space2"></div>
        <?php }} ?>
        <div class="space3"></div>
        
        
        <div class="clinic-area">
        	<span class="title">推荐ID</span>
        	<input type="text" name="clinicID" class="clinicID" placeholder="推荐诊所(可选填)"/>
        </div>
        
        
        <div class="order-bottom-area">
            <div class="goods-total">
            	<span class="goods-count">共<span class="number"><?php echo count($_smarty_tpl->getVariable('goodsList')->value);?>
</span>件商品</span>
            	<span>小计: <i class="icon iconfont icon-price">&#xe645;</i><span id="total-price-1" class="money"><?php echo sprintf("%.2f",$_smarty_tpl->getVariable('totalPrice')->value);?>
</span></span>
            </div>
        </div>
    </div>
    
    
    <div class="bottom-nav">
        <div class="row">	
            <a class="goods-info" href="javascript:;">
            	<span class="total-money">总金额：<i class="icon iconfont icon-price">&#xe645;</i><span class="money"><?php echo sprintf("%.2f",$_smarty_tpl->getVariable('totalPrice')->value);?>
</span></span>
            </a>            	
            <a class="pay-method" href="javascript:;"  data-user="<?php echo $_smarty_tpl->getVariable('uid')->value;?>
" data-payType="2"><span>立即支付</span></a>
        </div>
    </div>

    <!--页面脚部-->

</div>
<!--提示框-->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<!--公用js文件-->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/wechat/jweixin-1.0.0.js"></script>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/js/order/payOrder.js"></script>

</body>
</html>