<?php /* Smarty version Smarty-3.0.8, created on 2019-09-24 17:06:47
         compiled from "./template/front/common/head.html" */ ?>
<?php /*%%SmartyHeaderCode:3556910545d89dca78ac757-13660743%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e5c22bdcee7786875c5bfc626aa2a4d51f4a510a' => 
    array (
      0 => './template/front/common/head.html',
      1 => 1536231844,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3556910545d89dca78ac757-13660743',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>

    <!--2016-08-24 the lastest version designed by jjhu of change-->

    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
	<meta name="description" content="<?php if ($_smarty_tpl->getVariable('config')->value['site_description']){?><?php echo $_smarty_tpl->getVariable('config')->value['site_description'];?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('config')->value['site_name'];?>
<?php }?><?php if ($_smarty_tpl->getVariable('selectedCate')->value){?>|<?php echo $_smarty_tpl->getVariable('selectedCate')->value['description'];?>
<?php }?><?php if ($_smarty_tpl->getVariable('page')->value){?>|<?php echo $_smarty_tpl->getVariable('page')->value['description'];?>
<?php }?>">
    <meta name="keywords" content="<?php if ($_smarty_tpl->getVariable('config')->value['site_keywords']){?><?php echo $_smarty_tpl->getVariable('config')->value['site_keywords'];?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('config')->value['site_name'];?>
<?php }?><?php if ($_smarty_tpl->getVariable('selectedCate')->value){?>|<?php echo $_smarty_tpl->getVariable('selectedCate')->value['keywords'];?>
<?php }?><?php if ($_smarty_tpl->getVariable('page')->value){?>|<?php echo $_smarty_tpl->getVariable('page')->value['keywords'];?>
<?php }?>">
    <meta name="author" content="安徽千界信息科技有限公司">
	<meta name="robots" content="index,follow">
	<meta name="application-name" content="www.changekeji.com">
	<link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/common/iconfont2/iconfont.css">
	<link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/common/common.css">
