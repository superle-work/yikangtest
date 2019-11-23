<?php /* Smarty version Smarty-3.0.8, created on 2019-11-13 07:27:17
         compiled from "./template/admin/default/common/page/header.html" */ ?>
<?php /*%%SmartyHeaderCode:16812022665d78ad5a3a3556-61318560%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fb4e6bc139acbde8824934453f8b35d5aaeb3e97' => 
    array (
      0 => './template/admin/default/common/page/header.html',
      1 => 1573626494,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16812022665d78ad5a3a3556-61318560',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!-- 页头 -->
<div class="header container-fluid<?php if ($_smarty_tpl->getVariable('leftBarScale')->value=='1'){?> scale<?php }?>">
    <div class="row">
        <div class="col-md-2 col-sm-2 col-xs-12 logo">
            <a href="./admin.php"><img src="<?php echo @THEMES_PATH;?>
/image/logo.png"></a>
        </div>
        <div class="col-md-10 col-sm-10 col-xs-12 top">
            <div class="pull-left">
                <div class="top-nav clearfix">
                    <?php  $_smarty_tpl->tpl_vars['topMenu'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('menuList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['topMenu']->key => $_smarty_tpl->tpl_vars['topMenu']->value){
?>
                    <a class="nav-item <?php if ($_smarty_tpl->tpl_vars['topMenu']->value['id']==$_SESSION['currentMenu']['menu_top_id']){?>active<?php }?>" href="javascript:;" data-id="<?php echo $_smarty_tpl->tpl_vars['topMenu']->value['id'];?>
" data-name="<?php echo $_smarty_tpl->tpl_vars['topMenu']->value['alias'];?>
"><?php echo $_smarty_tpl->tpl_vars['topMenu']->value['name'];?>
</a>
                    <?php }} ?>
                </div>


            </div>
            <div class="pull-right">
                <div class="quick-action">
                    <a href="javascript:;" class="changeSkin" skin="<?php echo $_smarty_tpl->getVariable('curSkin')->value;?>
">换肤
                        <ul>
                            <li><span title="红色" class="red skin" skin="red"></span></li>
                            <li><span title="蓝色" class="blue skin" skin="blue"></span></li>
                            <li><span title="绿色" class="green skin" skin="green"></span></li>
                            <li><span title="紫色" class="purple skin" skin="purple"></span></li>
                            <li><span title="卡奇" class="kaqi skin" skin="kaqi"></span></li>
                            <li><span class="yellow skin" skin="yellow"></span></li>
                            <li><span class="orange skin" skin="orange"></span></li>
                            <li><span class="pink skin" skin="pink"></span></li>
                            <li><span class="skyBlue skin" skin="skyBlue"></span></li>
                        </ul>
                    </a>
                </div>
                <div class="user-action">
                    <?php if ($_smarty_tpl->getVariable('messageNum')->value){?><a href="?c=site_message&a=message&mid=13&is_read=0" title="未读留言"><i class="icon iconfont">&#xe60e;</i><span class="number"><?php echo $_smarty_tpl->getVariable('messageNum')->value;?>
</span></a><?php }?>
                    <?php if ($_smarty_tpl->getVariable('reserveNum')->value){?><a href="?c=site_reserve&a=reserve&mid=14&is_read=0" title="未读预约"><i class="icon iconfont">&#xe60d;</i><span class="number"><?php echo $_smarty_tpl->getVariable('reserveNum')->value;?>
</span></a><?php }?>
                    <a class="user"><?php echo $_SESSION['admin']['account'];?>
</a>
                    <a href="?c=base_main&a=logout" class="logo-out"><i class="icon iconfont">&#xe60c;</i>&nbsp;退出</a></div>
                </div>
        </div>
    </div>
</div>
