<?php /* Smarty version Smarty-3.0.8, created on 2019-09-24 17:07:42
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/user/checkMemberList.html" */ ?>
<?php /*%%SmartyHeaderCode:2130342565d89dcde1791e5-55601922%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '81761789612c71be2cd226c10658fd47522381b4' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/user/checkMemberList.html',
      1 => 1541493066,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2130342565d89dcde1791e5-55601922',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!--用户注册-->
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>体检人员信息</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/user/checkMemberList.css">
</head>

<body>
<!--主容器-->
<div id="container" class="container-fluid">
   	
    <!--主体内容-->
    <div id="content">
    	<div class="space"></div>
    	<div class="add-area">
    		<a href="index.php?c=store&a=addMember">
    			<i class="icon iconfont">&#xe6dc;</i>
    			<span>添加家庭成员</span>
    		</a>
    	</div>
    	
    	<!--体检人员信息展示区域-->
        <div class="member-area">
        	<?php if ($_smarty_tpl->getVariable('memList')->value){?>
        	<?php  $_smarty_tpl->tpl_vars['itemList'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('memList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['itemList']->key => $_smarty_tpl->tpl_vars['itemList']->value){
?>
        	<div class="space"></div>
        	<div class="member-list">
        		<div class="info">
        			<a href="index.php?c=store&a=showMember&id=<?php echo $_smarty_tpl->tpl_vars['itemList']->value['id'];?>
">
	        			<p class="name"><?php echo $_smarty_tpl->tpl_vars['itemList']->value['name'];?>
</p>
	        			<p>出生年月日: <?php echo $_smarty_tpl->tpl_vars['itemList']->value['idCard'];?>
</p>
	        			<p>手机号码: <?php echo $_smarty_tpl->tpl_vars['itemList']->value['phone'];?>
</p>
        			</a>
        		</div>
        		<hr />
        		<div class="action">
        			<!--判断是否默认选中-->
        			<?php if ($_smarty_tpl->tpl_vars['itemList']->value['state']==1){?>
        			<span class="setDefault" data-id="<?php echo $_smarty_tpl->tpl_vars['itemList']->value['id'];?>
"><i class="icon iconfont sel cur">&#xe648;</i><span class="set">设置默认成员</span></span>
        			<?php }else{ ?>
        			<span class="setDefault" data-id="<?php echo $_smarty_tpl->tpl_vars['itemList']->value['id'];?>
"><i class="icon iconfont sel">&#xe623;</i><span class="set">设置默认成员</span></span>
        			<?php }?>
        			<a href="index.php?c=store&a=editMember&id=<?php echo $_smarty_tpl->tpl_vars['itemList']->value['id'];?>
" class="edit">编辑</a>
        			<a href="javascript:;" class="delete" data-id="<?php echo $_smarty_tpl->tpl_vars['itemList']->value['id'];?>
">删除</a>
        		</div>
        	</div>
        	<?php }} ?>
        	<?php }?>
        </div>
    </div>
    
</div>
<!--公用js文件-->
<!--提示框-->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/js/user/checkMemberList.js"></script>
</body>
</html>