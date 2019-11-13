<?php /* Smarty version Smarty-3.0.8, created on 2019-09-29 14:10:27
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/common/fen/page/incomeCount.html" */ ?>
<?php /*%%SmartyHeaderCode:411732795d904ad3b648e8-19320539%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '826f6bfc65919013b87b6b56f436ecf1482fd231' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/common/fen/page/incomeCount.html',
      1 => 1536998164,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '411732795d904ad3b648e8-19320539',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>收益统计</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet" href="<?php echo @TEMPLATE_PATH;?>
/front/common/fen/css/incomeCount.css">
</head>

<body>
    <!--主容器-->
    <div id="container" class="container-fluid">
        <!--主体内容-->
        <div id="content">
        	<div class="space"></div>
	        <!--页面头部-->
	        <div class="top-area">
			    
			</div>
			<div class="get-more hidden" data-start="1" data-num="10" >下拉加载更多</div>
        </div>
    </div>
    <!--提示框-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <!--公用js文件-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/common/fen/js/incomeCount.js"></script>

</body>
</html>