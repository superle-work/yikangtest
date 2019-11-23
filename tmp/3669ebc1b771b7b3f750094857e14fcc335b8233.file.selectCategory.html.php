<?php /* Smarty version Smarty-3.0.8, created on 2019-11-13 07:58:29
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/store/page/goods/selectCategory.html" */ ?>
<?php /*%%SmartyHeaderCode:209945dcbb7a57649b2-37224331%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3669ebc1b771b7b3f750094857e14fcc335b8233' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/store/page/goods/selectCategory.html',
      1 => 1573626494,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '209945dcbb7a57649b2-37224331',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html>
<!-- head -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<link rel="stylesheet" href="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/css/goods/selectCategory.css" />
<body>
<!---------------- 商品列表 ------------------->

<!-- 页头 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<!-- 主体 -->
<div class="content container-fluid<?php if ($_smarty_tpl->getVariable('leftBarScale')->value=='1'){?> scale<?php }?>">
    <div class="row">
        <div class="col-md-2 left-section">
            <!-- 左导航菜单 -->
            <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/menu.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

        </div>
        <div class="col-md-10 right-section">
            <!--内容区域-->
            <h3><i class="icon iconfont"><?php echo $_SESSION['currentMenu']['icon'];?>
</i>分类列表
            </h3>
            <div class="inner-section row">
                <div id="content">
                    <div class="select-category">
                        <div class="category-txt">
                        	商品上架后将只能在分类编辑中选择修改
                        </div>
                        <div class="category-wrap clearfix">
                            <div class="category-item-wrap" rank='1'>
                                <ul class="category-list">
                                    <?php  $_smarty_tpl->tpl_vars['firCategory'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('firCateList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['firCategory']->key => $_smarty_tpl->tpl_vars['firCategory']->value){
?>
                                    <li><a href="javascript:;" data-id="<?php echo $_smarty_tpl->tpl_vars['firCategory']->value['id'];?>
" fir="<?php echo $_smarty_tpl->tpl_vars['firCategory']->value['fir'];?>
"><span class="category-name" data-name="<?php echo $_smarty_tpl->tpl_vars['firCategory']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['firCategory']->value['name'];?>
</span><span class="selected-category">选为分类</span></a></li>
                                    <?php }} ?>
                                </ul>
                            </div>
                            <div class="category-item-wrap hidden" rank='2'>
                                <ul class="category-list">
                                    <?php  $_smarty_tpl->tpl_vars['secCategory'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('secCateList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['secCategory']->key => $_smarty_tpl->tpl_vars['secCategory']->value){
?>
                                    <li><a href="javascript:;" data-id="<?php echo $_smarty_tpl->tpl_vars['secCategory']->value['id'];?>
" fir="<?php echo $_smarty_tpl->tpl_vars['secCategory']->value['fir'];?>
" sec="<?php echo $_smarty_tpl->tpl_vars['secCategory']->value['sec'];?>
"><span class="category-name" data-name=""><?php echo $_smarty_tpl->tpl_vars['secCategory']->value['name'];?>
</span><span class="selected-category">选为分类</span></a></li>
                                    <?php }} ?>
                                </ul>
                            </div>
                            <div class="category-item-wrap hidden" rank='3'>
                                <ul class="category-list">
                                    <?php  $_smarty_tpl->tpl_vars['thrCategory'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('thrCateList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['thrCategory']->key => $_smarty_tpl->tpl_vars['thrCategory']->value){
?>
                                    <li><a href="javascript:;" data-id="<?php echo $_smarty_tpl->tpl_vars['thrCategory']->value['id'];?>
" fir="<?php echo $_smarty_tpl->tpl_vars['thrCategory']->value['fir'];?>
" sec="<?php echo $_smarty_tpl->tpl_vars['thrCategory']->value['sec'];?>
" thr="<?php echo $_smarty_tpl->tpl_vars['thrCategory']->value['thr'];?>
" ><span class="category-name" data-name=""><?php echo $_smarty_tpl->tpl_vars['thrCategory']->value['name'];?>
</span><span class="selected-category">选为分类</span></a></li>
                                    <?php }} ?>
                                </ul>
                            </div>
                            <div class="category-item-wrap hidden" rank='4'>
                                <ul class="category-list">
                                    <?php  $_smarty_tpl->tpl_vars['fouCategory'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('fouCateList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['fouCategory']->key => $_smarty_tpl->tpl_vars['fouCategory']->value){
?>
                                    <li><a href="javascript:;" data-id="<?php echo $_smarty_tpl->tpl_vars['fouCategory']->value['id'];?>
" fir="<?php echo $_smarty_tpl->tpl_vars['fouCategory']->value['fir'];?>
" sec="<?php echo $_smarty_tpl->tpl_vars['fouCategory']->value['sec'];?>
" thr="<?php echo $_smarty_tpl->tpl_vars['fouCategory']->value['thr'];?>
" fou="<?php echo $_smarty_tpl->tpl_vars['fouCategory']->value['fou'];?>
"><span class="category-name" data-name=""><?php echo $_smarty_tpl->tpl_vars['fouCategory']->value['name'];?>
</span><span class="selected-category">选为分类</span></a></li>
                                    <?php }} ?>
                                </ul>
                            </div>
                        </div>
                        <div class="choosed-title">
                        	<div>已选分类</div>
                        </div>
                        <div class="choose-category-area row">
                        </div>
                        <div class="action-area">
                        	<input type="hidden" class="ids" id="ids" name="ids" value="">
                        	<a href="javascript:;" class="next">下一步</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 页脚 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<!-- jsfiles -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/js/goods/selectCategory.js"></script>
</body>
</html>