<?php /* Smarty version Smarty-3.0.8, created on 2019-09-24 17:10:51
         compiled from "./template/front/common/fen/inner/bottomNav.html" */ ?>
<?php /*%%SmartyHeaderCode:6993565655d89dd9b15a136-83538519%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ee3c007f27ae10eefc35702093b5be51f323ceed' => 
    array (
      0 => './template/front/common/fen/inner/bottomNav.html',
      1 => 1535701988,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6993565655d89dd9b15a136-83538519',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!--底部导航-->
<div class="bottom-nav">
    <div class="row">
        <a class="col-xs-6 col-sm-6 col-md-6 home<?php if ($_smarty_tpl->getVariable('bottomNav')->value=='index'){?> current<?php }?>" data-user="<?php echo $_SESSION['user']['account'];?>
" href="./index.php?c=<?php echo $_SESSION['fen']['bottomNav_module'];?>
&a=index"><div class="nav-icon"><i class="icon iconfont">&#xe600;</i></div><div class="nav-name">首页</div></a>
        <a class="col-xs-6 col-sm-6 col-md-6 item<?php if ($_smarty_tpl->getVariable('bottomNav')->value=='distributorCenter'){?> current<?php }?>"  data-user="<?php echo $_SESSION['user']['account'];?>
" href="./index.php?c=fen&a=distributorCenter"><div class="nav-icon"><i class="icon iconfont">&#xe626;</i></div><div class="nav-name">分销中心</div></a>
    </div>
</div>
<!--商品分类导航-->
