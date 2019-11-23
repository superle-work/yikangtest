<?php /* Smarty version Smarty-3.0.8, created on 2019-11-13 07:27:17
         compiled from "./template/admin/default/common/page/menu.html" */ ?>
<?php /*%%SmartyHeaderCode:19505350835d78ad5a408c60-57271811%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bc4afdc016fecd7acd787a9f26e160456d10545a' => 
    array (
      0 => './template/admin/default/common/page/menu.html',
      1 => 1573626494,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19505350835d78ad5a408c60-57271811',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>

<!--左边栏缩放-->
<div class="section-scale"><a class="btn-scale" href="javascript:;"><i class="icon iconfont">&#xe669;</i></a></div>
<!--左边栏菜单-->
<?php  $_smarty_tpl->tpl_vars['topMenu'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('menuList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['topMenu']->key => $_smarty_tpl->tpl_vars['topMenu']->value){
?>
<ul class="nav nav-stacks menu" data-top-id="<?php echo $_smarty_tpl->tpl_vars['topMenu']->value['id'];?>
" data-name="<?php echo $_smarty_tpl->tpl_vars['topMenu']->value['alias'];?>
" <?php if ($_smarty_tpl->tpl_vars['topMenu']->value['id']!=$_SESSION['currentMenu']['menu_top_id']){?>style="display:none;"<?php }?>>
    <li class="divider"></li>
    <?php  $_smarty_tpl->tpl_vars['block'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['topMenu']->value['subList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['block']->key => $_smarty_tpl->tpl_vars['block']->value){
?>
        <?php  $_smarty_tpl->tpl_vars['bmenu'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['block']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['bmenu']->key => $_smarty_tpl->tpl_vars['bmenu']->value){
?>
        <li role="presentation" <?php if ($_SESSION['currentMenu']['mid']==$_smarty_tpl->tpl_vars['bmenu']->value['id']){?>class="active"<?php }?>>
        <a href="<?php echo $_smarty_tpl->tpl_vars['bmenu']->value['url'];?>
" data-toggle="tooltip" data-placement="right" title="<?php echo $_smarty_tpl->tpl_vars['bmenu']->value['name'];?>
"><i class="icon iconfont"><?php echo $_smarty_tpl->tpl_vars['bmenu']->value['icon'];?>
</i><span class="menu-name"><?php echo $_smarty_tpl->tpl_vars['bmenu']->value['name'];?>
</span></span></a>
        </li>
        <?php }} ?>
        <li class="divider"></li>
    <?php }} ?>
</ul>
<?php }} ?>
<input type="hidden" id="menuId" value="<?php echo $_SESSION['currentMenu']['mid'];?>
">