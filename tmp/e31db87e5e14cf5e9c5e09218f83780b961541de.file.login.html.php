<?php /* Smarty version Smarty-3.0.8, created on 2019-09-26 09:14:54
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/user/login.html" */ ?>
<?php /*%%SmartyHeaderCode:3462384545d8c110e9abad8-05658618%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e31db87e5e14cf5e9c5e09218f83780b961541de' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/user/login.html',
      1 => 1540878720,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3462384545d8c110e9abad8-05658618',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!--用户登录-->
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>手机验证</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/user/login.css">
</head>

<body>
<!--主容器-->
<div id="container" class="container-fluid">
    <!--主体内容-->
    <div id="content">
        <form class="form-horizontal">
            <div class="inp-area">
            	<div>
            		<label for="phone">请输入手机号码</label>
            		<input type="text" id="phone" name="phone"/>
            	</div>
            </div>
            <div class="inp-area">
            	<div>
            		<label for="img_yzm">请输入图形验证码</label>
            		<input type="text" id="img_yzm" name="img_yzm"/>
            	</div>
            	<div>
            		<img src="index.php?c=base_user&a=captcha" alt="" class="captcha-img"/>
            	</div>
            </div>
            <div class="inp-area">
            	<div>
            		<label for="yzm">请输入验证码</label>
            		<input type="text" id="yzm" name="yzm"/>
            	</div>
            	<div>
            		<a href="javascript:;" class="getYzm">获取验证码</a>
            	</div>
            </div>
            
            <div class="but-area">
            	<button id="send">提 交</button>
            </div>
        </form>
    </div>

    <!--页面脚部-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

</div>
<!--公用js文件-->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/js/user/login.js"></script>

</body>
</html>