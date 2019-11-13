<?php /* Smarty version Smarty-3.0.8, created on 2019-09-11 16:18:11
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/base/index.html" */ ?>
<?php /*%%SmartyHeaderCode:13491362565d78adc318dbf0-92741580%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dc67da4403edff5cdef3662eb50133371b014670' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/base/index.html',
      1 => 1535701986,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13491362565d78adc318dbf0-92741580',
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
<body>
<!---------------- 控制面板 ------------------->
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
             <h3>
                 <i class="icon iconfont"><?php echo $_SESSION['currentMenu']['icon'];?>
</i><?php echo $_SESSION['currentMenu']['menuTitle'];?>

                 <div class="operation-div">
                     <a class="btn btn-danger clear-cache" href="javascript:;">清除缓存</a>
                 </div>
             </h3>
             <div class="inner-section row">
                 <?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('panel')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menu']['iteration']=0;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['menu']['iteration']++;
?>
                 <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['menu']['iteration']%5=='1'){?>
                 <div class="col-md-3 quick-nav admin">
                     <a href="<?php echo $_smarty_tpl->tpl_vars['menu']->value['url'];?>
" >
                         <div class="icon-image  blue-background"><i class="icon iconfont"><?php echo $_smarty_tpl->tpl_vars['menu']->value['icon'];?>
</i></div>
                         <div class="title  blue-background"><?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
</div>
                     </a>
                 </div>

                 <?php }elseif($_smarty_tpl->getVariable('smarty')->value['foreach']['menu']['iteration']%5=='2'){?>
                 <div class="col-md-3 quick-nav admin">
                     <a href="<?php echo $_smarty_tpl->tpl_vars['menu']->value['url'];?>
" >
                         <div class="icon-image  green-background"><i class="icon iconfont"><?php echo $_smarty_tpl->tpl_vars['menu']->value['icon'];?>
</i></div>
                         <div class="title  green-background"><?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
</div>
                     </a>
                 </div>
                 <?php }elseif($_smarty_tpl->getVariable('smarty')->value['foreach']['menu']['iteration']%5=='3'){?>
                 <div class="col-md-3 quick-nav admin">
                     <a href="<?php echo $_smarty_tpl->tpl_vars['menu']->value['url'];?>
" >
                         <div class="icon-image  purple-background"><i class="icon iconfont"><?php echo $_smarty_tpl->tpl_vars['menu']->value['icon'];?>
</i></div>
                         <div class="title  purple-background"><?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
</div>
                     </a>
                 </div>
                 <?php }elseif($_smarty_tpl->getVariable('smarty')->value['foreach']['menu']['iteration']%5=='4'){?>
                 <div class="col-md-3 quick-nav admin">
                     <a href="<?php echo $_smarty_tpl->tpl_vars['menu']->value['url'];?>
" >
                         <div class="icon-image  red-background"><i class="icon iconfont"><?php echo $_smarty_tpl->tpl_vars['menu']->value['icon'];?>
</i></div>
                         <div class="title  red-background"><?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
</div>
                     </a>
                 </div>
                 <?php }elseif($_smarty_tpl->getVariable('smarty')->value['foreach']['menu']['iteration']%5=='0'){?>
                 <div class="col-md-3 quick-nav admin">
                     <a href="<?php echo $_smarty_tpl->tpl_vars['menu']->value['url'];?>
" >
                         <div class="icon-image  kaqi-background"><i class="icon iconfont"><?php echo $_smarty_tpl->tpl_vars['menu']->value['icon'];?>
</i></div>
                         <div class="title  kaqi-background"><?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
</div>
                     </a>
                 </div>
                 <?php }?>
                 <?php }} ?>

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
/base/js/index.js"></script>
</body>
</html>