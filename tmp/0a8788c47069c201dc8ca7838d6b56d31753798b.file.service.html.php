<?php /* Smarty version Smarty-3.0.8, created on 2019-09-26 15:58:49
         compiled from "./template/front/common/service/page/service.html" */ ?>
<?php /*%%SmartyHeaderCode:18948808295d8c6fb9c05410-20002077%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0a8788c47069c201dc8ca7838d6b56d31753798b' => 
    array (
      0 => './template/front/common/service/page/service.html',
      1 => 1535701988,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18948808295d8c6fb9c05410-20002077',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_smarty_tpl->getVariable('serviceList')->value){?>
<!--客服服务-->
<link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/common/service/css/service.css">
<div id="service-area">
	<div class="shadow"></div>
	<div class="bottom-tag-list">
		<?php  $_smarty_tpl->tpl_vars['service'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('serviceList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['service']->key => $_smarty_tpl->tpl_vars['service']->value){
?>
		<?php if ($_smarty_tpl->tpl_vars['service']->value['type']=='1'){?><div class="bottom-tag service" ><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $_smarty_tpl->tpl_vars['service']->value['number'];?>
&site=qq&menu=yes"><img class="icon-img" src="<?php echo $_smarty_tpl->tpl_vars['service']->value['head_img'];?>
"><span class="name"><?php echo $_smarty_tpl->tpl_vars['service']->value['name'];?>
</span></a></div><?php }?>
		<?php if ($_smarty_tpl->tpl_vars['service']->value['type']=='2'){?>
			<div class="bottom-tag service wechat-service" data-img="<?php echo $_smarty_tpl->tpl_vars['service']->value['number'];?>
"><img class="icon-img" src="<?php echo $_smarty_tpl->tpl_vars['service']->value['head_img'];?>
"><span class="name"><?php echo $_smarty_tpl->tpl_vars['service']->value['name'];?>
</span></div>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['service']->value['type']=='3'){?>
			<div class="bottom-tag service" ><a href="tel:<?php echo $_smarty_tpl->tpl_vars['service']->value['number'];?>
"><img  class="icon-img" src="<?php echo $_smarty_tpl->tpl_vars['service']->value['head_img'];?>
"><span class="name"><?php echo $_smarty_tpl->tpl_vars['service']->value['name'];?>
</span></a></div><?php }?>
		<?php }} ?>
	</div>
	<div class="wechat-code-show">
		<div class="wechat-code-area">
			<div class="wechat-code-title">请长按二维码识别<span class="leave-wechat-code"><i class="icon iconfont">&#xe613;</i></span></div>
			<div class="service-wechat-code"><img src=""></div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/common/service/js/service.js"></script>
<?php }?>
