<?php /* Smarty version Smarty-3.0.8, created on 2019-10-03 11:59:22
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/user/showMember.html" */ ?>
<?php /*%%SmartyHeaderCode:15923497315d95721add9156-18003259%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '42aade1f808c20cfabff6f2b624d95e77d95d7de' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/user/showMember.html',
      1 => 1541493097,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15923497315d95721add9156-18003259',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!--用户注册-->
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>成员信息</title>
    <link rel="stylesheet" href='<?php echo @JS_PATH;?>
/public/SUI_Mobile_dev/dist/css/sm.min.css'>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/user/showMember.css">
</head>

<body>
<!--主容器-->
<div>
    <!--主体内容-->
    <div id="content">
    	<div class="add-area">
    		<div class="name">姓名</div>
    		<div>
    			<input type="text" placeholder="请输入姓名" name="name" value="<?php echo $_smarty_tpl->getVariable('memberInfo')->value['name'];?>
"/>
    		</div>
    	</div>
    	<div class="add-area">
    		<div class="name">性别</div>
    		<div class="inp">
    			<input type="text" id="sex" name="sex" value="<?php echo $_smarty_tpl->getVariable('memberInfo')->value['sex'];?>
"/>
    		</div>
    	</div>
    	<div class="add-area">
    		<div class="name">出生年月日</div>
    		<div>
    			<input type="text" placeholder="请输入出生年月日" name="idCard" value="<?php echo $_smarty_tpl->getVariable('memberInfo')->value['idCard'];?>
"/>
    		</div>
    	</div>
    	<div class="add-area">
    		<div class="name">手机号码</div>
    		<div>
    			<input type="text" placeholder="请输入手机号码" name="phone" value="<?php echo $_smarty_tpl->getVariable('memberInfo')->value['phone'];?>
"/>
    		</div>
    	</div>
    </div>
    <div class="but-area">
    	<button class="send">确定</button>	
    </div>
</div>
<!--公用js文件-->
<!--提示框-->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script>jQuery.noConflict()</script>
<script type='text/javascript' src='<?php echo @JS_PATH;?>
/public/SUI_Mobile_dev/dist/js/Zepto.js'></script>
<script type='text/javascript' src='<?php echo @JS_PATH;?>
/public/SUI_Mobile_dev/dist/js/sm.min.js'></script>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/js/user/showMember.js"></script>
</body>
</html>