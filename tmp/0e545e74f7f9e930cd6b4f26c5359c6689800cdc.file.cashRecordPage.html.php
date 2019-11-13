<?php /* Smarty version Smarty-3.0.8, created on 2019-09-24 17:10:51
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/common/fen/page/cashRecordPage.html" */ ?>
<?php /*%%SmartyHeaderCode:7309930005d89dd9b101085-86347321%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0e545e74f7f9e930cd6b4f26c5359c6689800cdc' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/common/fen/page/cashRecordPage.html',
      1 => 1536721474,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7309930005d89dd9b101085-86347321',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>收支明细</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/fen/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/common/fen/css/cashRecordPage.css">
</head>

<body>
    <!--主容器-->
    <div id="container" class="container-fluid">
        <!--主体内容-->
        <div id="content">
        	<div class="top-area">
        		<div class="cur" data-type="1">
        			提现记录
        		</div>
        		<div class="" data-type="2">
        			收入记录
        		</div>
        	</div>
        	
        	<div class="space"></div>
        	
            <div class="cash-record-list">
                <table class="table-striped">
                	
                </table>
            </div>
            <div class="get-more hidden" data-start="1" data-num="10" >下拉加载更多</div>
        </div>

        <!--页面脚部-->
        <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/fen/inner/bottomNav.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    </div>
    <!--公用js文件-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/fen/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
	<script src="<?php echo @TEMPLATE_PATH;?>
/front/common/fen/js/cashRecordPage.js"></script>
</body>
</html>