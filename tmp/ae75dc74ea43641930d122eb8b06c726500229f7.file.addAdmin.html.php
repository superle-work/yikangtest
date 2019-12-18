<?php /* Smarty version Smarty-3.0.8, created on 2019-12-17 08:10:06
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/base/admin/page/addAdmin.html" */ ?>
<?php /*%%SmartyHeaderCode:243575df88d5e9b2330-63306502%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ae75dc74ea43641930d122eb8b06c726500229f7' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/base/admin/page/addAdmin.html',
      1 => 1573626494,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '243575df88d5e9b2330-63306502',
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
<!---------------- 添加管理员 ------------------->

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
</i>添加管理员</h3>
            <div class="inner-section row">
                <div class="items add-div">
                    <form id="myForm" action="#" method="post" enctype="multipart/form-data">
                        <div class="item table-responsive">
                            <table class="table table-striped table-hover table-bordered table-base">
                                <tbody>
                                    <tr>
                                        <td>账号<label class="must-tag">*</label></td>
                                        <td>
                                            <input type="text" name="account" id="account">
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>名称<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <input type="text" name="admin_name" id="admin_name">
                                        </td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>密码<label class="must-tag">*</label></td>
                                        <td>
                                            <input type="password" name="password" id="password">
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>再输一次<label class="must-tag">*</label></td>
                                        <td>
                                            <input type="password" name="confirmPwd" id="confirmPwd">
                                        </td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>邮箱<label class="must-tag">&nbsp;</label></td>
                                        <td>
                                            <input type="text"  name="email" id="email">
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>账户类型<label class="must-tag">*</label></td>
                                        <td>
                                            <input type="radio" name="type" value="1"> 管理员
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="type" value="2"> 子账户
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr class="hidden">
                                        <td>选择代理<label class="must-tag">*</label></td>
                                        <td>
                                            <?php if ($_smarty_tpl->getVariable('agentResult')->value){?>
                                            <select name="agent_id" id="">
                                                <option value="">--请选择--</option>
                                                <?php  $_smarty_tpl->tpl_vars['agent'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('agentResult')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['agent']->key => $_smarty_tpl->tpl_vars['agent']->value){
?>
                                                <option value="<?php echo $_smarty_tpl->tpl_vars['agent']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['agent']->value['name'];?>
----<?php echo $_smarty_tpl->tpl_vars['agent']->value['province'];?>
<?php echo $_smarty_tpl->tpl_vars['agent']->value['city'];?>
<?php echo $_smarty_tpl->tpl_vars['agent']->value['area'];?>
</option>
                                                <?php }} ?>
                                            </select>
                                            <?php }else{ ?>
                                            <font color="red" size="4">请先添加代理</font>
                                            &nbsp;&nbsp;<a href="admin.php?c=store_agent&a=agentList&mid=41">点击前去添加</a>
                                            <?php }?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                        </td>
                                        <td>
                                            <a class="btn btn-primary submit">提交</a>
                                            <a href="javascript:window.history.go(-1);" class="btn btn-default">返回</a>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
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
/base/admin/js/addAdmin.js"></script>
</body>
</html>