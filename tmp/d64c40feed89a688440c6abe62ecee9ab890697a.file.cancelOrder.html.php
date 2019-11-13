<?php /* Smarty version Smarty-3.0.8, created on 2019-09-27 10:18:37
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/logistics/cancelOrder.html" */ ?>
<?php /*%%SmartyHeaderCode:6296178415d8d717dac40e9-26565407%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd64c40feed89a688440c6abe62ecee9ab890697a' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/logistics/cancelOrder.html',
      1 => 1540878726,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6296178415d8d717dac40e9-26565407',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>核销记录</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/logistics/cancelOrder.css">
</head>

<body>
    <div id="content">
        <div id="content">
        	<div class="top-area">
        		<div class="cur all" data-state="">
        			全部
        		</div>
        		<div class="" data-state="2">
        			待送检
        		</div>
        		<div class="" data-state="3">
        			送检中
        		</div>
        		<div class="" data-state="5">
        			已完成
        		</div>
        	</div>
        	
        	<div class="space"></div>
        	
            <div class="order-list">
            	
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
    <script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/jquery.bootpag/jquery.bootpag.min.js"></script>
    <script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/js/logistics/cancelOrder.js"></script>
</body>
</html>