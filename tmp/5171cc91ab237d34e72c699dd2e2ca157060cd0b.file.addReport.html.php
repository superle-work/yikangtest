<?php /* Smarty version Smarty-3.0.8, created on 2019-09-28 15:22:30
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/order/addReport.html" */ ?>
<?php /*%%SmartyHeaderCode:13575788005d8f0a3658c439-53877878%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5171cc91ab237d34e72c699dd2e2ca157060cd0b' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/order/addReport.html',
      1 => 1540878724,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13575788005d8f0a3658c439-53877878',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet" href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/order/addReport.css">
    <title>上传报告</title>
</head>
<body>
    <div id="content">
    	<div class="top-area">
    		<textarea name="report_desc" id="report_desc" ></textarea>
    	</div>
    	
    	<div class="img-area">
    		<div class="image-list row">								
				<div class="upload-image clearfix">
    				
				</div>
			</div>
    	</div>
    	
    	<div class="btn-area">
    		<input type="hidden" name="oid" value="<?php echo $_smarty_tpl->getVariable('oid')->value;?>
" id="oid"/>
    		<button id="upload">提交</button>
    	</div>
    </div>
<!--提示框-->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<script src="<?php echo @JS_PATH;?>
/public/form/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/js/order/addReport.js"></script>
</body>
</html>