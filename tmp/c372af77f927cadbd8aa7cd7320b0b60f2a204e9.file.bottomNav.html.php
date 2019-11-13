<?php /* Smarty version Smarty-3.0.8, created on 2019-09-24 17:06:47
         compiled from "./template/front/store/ma17004/inner/bottomNav.html" */ ?>
<?php /*%%SmartyHeaderCode:17357481395d89dca78d41f0-13718071%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c372af77f927cadbd8aa7cd7320b0b60f2a204e9' => 
    array (
      0 => './template/front/store/ma17004/inner/bottomNav.html',
      1 => 1543221332,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17357481395d89dca78d41f0-13718071',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!--底部导航-->
<div class="bottom-nav">
    <div class="row">
        <a class="col-xs-3 col-sm-3 col-md-3 home<?php if ($_smarty_tpl->getVariable('bottomNav')->value=='index'){?> current<?php }?>" data-user="<?php echo $_SESSION['user']['account'];?>
" href="./index.php?c=store&a=index"><div class="nav-icon"><i class="icon iconfont">&#xe6e1;</i></div><div class="nav-name">首页</div></a>
        <a class="col-xs-3 col-sm-3 col-md-3 item<?php if ($_smarty_tpl->getVariable('bottomNav')->value=='goodsCate'){?> current<?php }?>"  data-user="<?php echo $_SESSION['user']['account'];?>
" href="./index.php?c=store&a=goodsCate"><div class="nav-icon"><i class="icon iconfont">&#xe625;</i></div><div class="nav-name">分类</div></a>
        <a class="col-xs-3 col-sm-3 col-md-3 item<?php if ($_smarty_tpl->getVariable('bottomNav')->value=='cartList'){?> current<?php }?>"  data-user="<?php echo $_SESSION['user']['account'];?>
" href="./index.php?c=store&a=cartList&type=1"><div class="nav-icon" style="padding-right:15px;"><span style="position:relative;left: 24px;top: -8px;background:red;border-radius:10px;display: inline-block;height: 15px;width: 14px;line-height: 16px;color:white;"><span><?php echo $_SESSION['cart']['cartNum'];?>
</span></span><i class="icon iconfont">&#xe646;</i></div><div class="nav-name">购物车</div></a>
        <a class="col-xs-3 col-sm-3 col-md-3 item<?php if ($_smarty_tpl->getVariable('bottomNav')->value=='userCenter'){?> current<?php }?>"  data-user="<?php echo $_SESSION['user']['account'];?>
" href="./index.php?c=store&a=userCenter"><div class="nav-icon"><i class="icon iconfont">&#xe676;</i></div><div class="nav-name">个人中心</div></a>

    </div>
</div>
<!--商品分类导航-->
