<?php /* Smarty version Smarty-3.0.8, created on 2019-10-16 10:45:32
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/common/fen/page/accountList.html" */ ?>
<?php /*%%SmartyHeaderCode:1983594835da6844c86a257-49868343%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a26abf20da333e4981f9a64d52ae89548d0ee383' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/common/fen/page/accountList.html',
      1 => 1536547888,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1983594835da6844c86a257-49868343',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>账户管理</title>
   <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/fen/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/common/fen/css/accountList.css">
</head>

<body>
    <!--主容器-->
    <div id="content">
    	<input type="hidden" id="id" value="<?php echo $_smarty_tpl->getVariable('id')->value;?>
">
    	<input type="hidden" id="type" value="<?php echo $_smarty_tpl->getVariable('type')->value;?>
">
    	<div class="list">账户列表 <span>编辑</span></div>
    	<div class="wrap">

    	</div>
    	<div class="get-more" data-start="0" data-num="10"></div>
    	
    </div>
     <!--页面脚部-->
    <div class="button">
    	<a href="./index.php?c=fen&a=addAccount&id=<?php echo $_smarty_tpl->getVariable('id')->value;?>
&type=<?php echo $_smarty_tpl->getVariable('type')->value;?>
" class="add_account"><i class="icon iconfont">&#xe62f;</i>&nbsp;&nbsp;&nbsp;添加账户</a>    	
    </div>   
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/fen/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    
    <!--公用js文件-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/fen/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/common/fen/js/accountList.js"></script>
</body>
</html>