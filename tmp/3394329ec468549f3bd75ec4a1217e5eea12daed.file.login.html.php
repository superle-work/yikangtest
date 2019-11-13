<?php /* Smarty version Smarty-3.0.8, created on 2019-09-11 16:56:43
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/login.html" */ ?>
<?php /*%%SmartyHeaderCode:5025988995d78b6cbc0c563-86109329%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3394329ec468549f3bd75ec4a1217e5eea12daed' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/login.html',
      1 => 1535701988,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5025988995d78b6cbc0c563-86109329',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>登录 | 千界微信后台管理系统</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{$description}">
    <meta name="keywords" content="{$keywords}">
    <meta name="author" content="安徽千界信息科技有限公司">
    <meta name="robots" content="index,follow">
    <meta name="application-name" content="www.changekeji.com">
    <!-- local CSS -->
    <link href="<?php echo @BASE_PATH;?>
/js/public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/common/css/login.css">
	<link rel="stylesheet" href="<?php echo @THEMES_PATH;?>
/iconfont/iconfont.css">
    <!-- Favicons -->
    <link rel="icon" href="<?php echo @BASE_PATH;?>
/themes/image/favicon.ico" sizes="16x16 32x32">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="<?php echo @BASE_PATH;?>
/js/public/respond.min.js" type="text/javascript"></script>
    <script src="<?php echo @BASE_PATH;?>
/js/public/html5shiv.min.js" type="text/javascript"></script>
    <![endif]-->
</head>
<body>
<div id="header">
    <div class="logo fl">
        <a href="http://www.changekeji.com" target="_blank">
            <img src="<?php echo @BASE_PATH;?>
/themes/image/logo2.png">
        </a>
    </div>
    <div class="line fl"></div>
    <div class="welcome-login fl">你好！欢迎来到千界科技后台登录系统</div>
</div>
<div id="container">
    <!--登录主图-->
    <div class="login-image-bg">
        <img src="<?php echo @BASE_PATH;?>
/themes/image/login-bg.jpg">
    </div>
    <!--登录区域-->
    <div class="login-area">
        <h3>管理登录</h3>
         <ul>
         	<div class="notice">
         		<i class="icon iconfont">&#xe6c0;</i>公众场所不建议自动登录，以防丢失账号信息
         	</div>
             <li class="error-info"></li>
             <li class="clearfix">
                 <div class="account-info login-info">
                     <span class="fl title-info" title="账号"><i class="icon iconfont">&#xe64b;</i></span>
                     <input type="text" class="fl" tabindex="1" id="account" placeholder="请输入账号" <?php if ($_COOKIE['remember']==1){?>value="<?php echo $_COOKIE['account'];?>
"<?php }?>>
                 </div>
             </li>
             <li class="clearfix">
                 <div class="password-info login-info">
                     <span class="fl title-info" title="密码"><i class="icon iconfont">&#xe64a;</i></span>
                     <input type="password" class="fl" tabindex="2" id="password" placeholder="请输入密码"  <?php if ($_COOKIE['remember']==1){?>value="<?php echo $_COOKIE['password'];?>
"<?php }?>>
                 </div>
             </li>
             
             <li class="clearfix">
                 <div class="help">
                     <a class="fl" href="#" style="display: none;">帮助中心</a>
					 <div class="checkbox remember-me fl">
                         <label>
                             <input type="checkbox" tabindex="4" id="remember" name="remember" value="1"  <?php if ($_COOKIE['remember']==1){?>checked<?php }?>> <span>记住密码</span>
                         </label>
                     </div>
                     <a class="fr" href="javascript:;" data-toggle="popover" data-trigger="hover click" data-placement="left" data-content="请联系我们重置密码">忘记密码？</a>
                 </div>
             </li>
             <li class="clearfix">
                 <a class="btn-login" tabindex="3" id="btnLogin" href="javascript:;">登录</a>
             </li>
         </ul>
    </div>
</div>
<div id="footer">
    <div class="copyright">
        <a href="http://www.changekeji.com" target="_blank" class="border-left">技术支持：千界科技</a>
    </div>
</div>
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<script src="<?php echo @BASE_PATH;?>
/js/public/jquery/jquery-1.11.0.js" type="text/javascript"></script>
<script src="<?php echo @BASE_PATH;?>
/js/public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/common/js/login.js" type="text/javascript"></script>

</body>
</html>