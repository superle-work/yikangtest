<?php /* Smarty version Smarty-3.0.8, created on 2019-11-12 07:13:59
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/base/admin/page/adminList.html" */ ?>
<?php /*%%SmartyHeaderCode:60845dca5bb72dfb74-80301275%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '25a0d8ae5e9be7538107a782119a29ae6260d10d' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/base/admin/page/adminList.html',
      1 => 1535701986,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '60845dca5bb72dfb74-80301275',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html>
<!-- head -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<body>
<!---------------- 网站管理员 ------------------->

<!-- 页头 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<!-- 主体 -->
<div class="content container-fluid<?php if ($_smarty_tpl->getVariable('leftBarScale')->value=='1'){?> scale<?php }?>">
    <div class="row">
        <div class="col-md-2 left-section">
            <!-- 左导航菜单 -->
            <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/menu.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

        </div>
        <div class="col-md-10 right-section">
            <!--内容区域-->
            <h3><i class="icon iconfont"><?php echo $_SESSION['currentMenu']['icon'];?>
</i>网站管理员
                <div class="operation-div">
                    <a href="admin.php?c=base_admin&a=addAdmin" class="btn btn-success">添加管理员</a>
                </div>
            </h3>
            <div class="inner-section row">

               <?php  $_smarty_tpl->tpl_vars['admin'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('adminList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['admin']['iteration']=0;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['admin']->key => $_smarty_tpl->tpl_vars['admin']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['admin']['iteration']++;
?>
                <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['admin']['iteration']%4=='1'){?>
                <div class="col-md-3 quick-nav admin">
                    <a href="javascript:;" aid="<?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
" class="edit">
                        <div class="icon-image  blue-background"><i class="icon iconfont">&#xe617;</i></div>
                        <div class="title  blue-background"><?php echo $_smarty_tpl->tpl_vars['admin']->value['account'];?>
-<?php echo $_smarty_tpl->tpl_vars['admin']->value['admin_name'];?>
</div>
                    </a>
                    <div class="action">
                        <a href="javascript:;" aid="<?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
" class="edit">编辑</a>
                        <?php if ($_smarty_tpl->tpl_vars['admin']->value['account']!=$_SESSION['admin']['account']){?>
                        &nbsp;|&nbsp;<a  href="javascript:;" aid="<?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
" class="delete" account="<?php echo $_smarty_tpl->tpl_vars['admin']->value['account'];?>
">删除</a>
                        <?php }else{ ?>
                        &nbsp;|&nbsp;<a  href="javascript:;" aid="<?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
" class="editPwd" account="<?php echo $_smarty_tpl->tpl_vars['admin']->value['account'];?>
">修改密码</a>
                        <?php }?>
                    </div>

                </div>

                <?php }elseif($_smarty_tpl->getVariable('smarty')->value['foreach']['admin']['iteration']%4=='2'){?>
                <div class="col-md-3 quick-nav admin">
                    <a href="javascript:;" aid="<?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
" class="edit">
                        <div class="icon-image green-background"><i class="icon iconfont">&#xe617;</i></div>
                        <div class="title green-background"><?php echo $_smarty_tpl->tpl_vars['admin']->value['account'];?>
-<?php echo $_smarty_tpl->tpl_vars['admin']->value['admin_name'];?>
</div>
                    </a>
                    <div class="action">
                        <a href="javascript:;" aid="<?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
" class="edit">编辑</a>
                        <?php if ($_smarty_tpl->tpl_vars['admin']->value['account']!=$_SESSION['admin']['account']){?>
                        &nbsp;|&nbsp;<a  href="javascript:;" aid="<?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
" class="delete" account="<?php echo $_smarty_tpl->tpl_vars['admin']->value['account'];?>
">删除</a>
                        <?php }else{ ?>
                        &nbsp;|&nbsp;<a  href="javascript:;" aid="<?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
" class="editPwd" account="<?php echo $_smarty_tpl->tpl_vars['admin']->value['account'];?>
">修改密码</a>
                        <?php }?>
                    </div>

                </div>
                <?php }elseif($_smarty_tpl->getVariable('smarty')->value['foreach']['admin']['iteration']%4=='3'){?>
                <div class="col-md-3 quick-nav admin">
                    <a href="javascript:;" aid="<?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
" class="edit">
                        <div class="icon-image purple-background"><i class="icon iconfont">&#xe617;</i></div>
                        <div class="title purple-background"><?php echo $_smarty_tpl->tpl_vars['admin']->value['account'];?>
-<?php echo $_smarty_tpl->tpl_vars['admin']->value['admin_name'];?>
</div>
                    </a>
                    <div class="action">
                        <a href="javascript:;" aid="<?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
" class="edit">编辑</a>
                        <?php if ($_smarty_tpl->tpl_vars['admin']->value['account']!=$_SESSION['admin']['account']){?>
                        &nbsp;|&nbsp;<a  href="javascript:;" aid="<?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
" class="delete" account="<?php echo $_smarty_tpl->tpl_vars['admin']->value['account'];?>
">删除</a>
                        <?php }else{ ?>
                        &nbsp;|&nbsp;<a  href="javascript:;" aid="<?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
" class="editPwd" account="<?php echo $_smarty_tpl->tpl_vars['admin']->value['account'];?>
">修改密码</a>
                        <?php }?>
                    </div>

                </div>
                <?php }elseif($_smarty_tpl->getVariable('smarty')->value['foreach']['admin']['iteration']%4=='0'){?>
                <div class="col-md-3 quick-nav admin">
                    <a href="javascript:;" aid="<?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
" class="edit">
                        <div class="icon-image red-background"><i class="icon iconfont">&#xe617;</i></div>
                        <div class="title red-background"><?php echo $_smarty_tpl->tpl_vars['admin']->value['account'];?>
-<?php echo $_smarty_tpl->tpl_vars['admin']->value['admin_name'];?>
</div>
                    </a>
                    <div class="action">
                        <a href="javascript:;" aid="<?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
" class="edit">编辑</a>
                        <?php if ($_smarty_tpl->tpl_vars['admin']->value['account']!=$_SESSION['admin']['account']){?>
                        &nbsp;|&nbsp;<a  href="javascript:;" aid="<?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
" class="delete" account="<?php echo $_smarty_tpl->tpl_vars['admin']->value['account'];?>
">删除</a>
                        <?php }else{ ?>
                        &nbsp;|&nbsp;<a  href="javascript:;" aid="<?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
" class="editPwd" account="<?php echo $_smarty_tpl->tpl_vars['admin']->value['account'];?>
">修改密码</a>
                        <?php }?>
                    </div>

                </div>
                <?php }?>

                <?php }} ?>

            </div>
        </div>
    </div>
</div>

<!-- 页脚 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<!-- jsfiles -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/base/admin/js/adminList.js"></script>
</body>
</html>