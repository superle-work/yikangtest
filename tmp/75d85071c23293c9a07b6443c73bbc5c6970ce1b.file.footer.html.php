<?php /* Smarty version Smarty-3.0.8, created on 2019-12-25 08:58:53
         compiled from "./template/admin/default/common/page/footer.html" */ ?>
<?php /*%%SmartyHeaderCode:230665e0324cd412388-60336654%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '75d85071c23293c9a07b6443c73bbc5c6970ce1b' => 
    array (
      0 => './template/admin/default/common/page/footer.html',
      1 => 1575337095,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '230665e0324cd412388-60336654',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!-- 页脚 -->
<div class="footer<?php if ($_smarty_tpl->getVariable('leftBarScale')->value=='1'){?> scale<?php }?>">
    <div>COPYRIGHT © 2015 <a target="_blank" href="http://www.changekeji.com">千界科技</a> DESIGN. ALL RIGHTS RESERVED.</div>
</div>
<!-- 返回上部图标 -->
<div class="up-nav bottom-fixed">
	<div class="up-icon bottom-icon">
		<i class="icon iconfont">&#xe621;</i>
	</div>
</div>
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>